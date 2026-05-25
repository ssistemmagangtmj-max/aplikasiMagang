<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\InternshipApplication;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function dashboard()
    {
        $pendingUsers = User::where('status', 'pending')->count();
        $pendingApps = InternshipApplication::where('status', 'pending')->count();
        $totalCompanies = Company::count();
        $totalUsers = User::count();

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'pendingUsers' => $pendingUsers,
                'pendingApps' => $pendingApps,
                'totalCompanies' => $totalCompanies,
                'totalUsers' => $totalUsers,
            ],
        ]);
    }

    // === PENGGUNA ===
    public function pengguna()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        $pendingUsers = User::where('status', 'pending')->get();

        return Inertia::render('Admin/Pengguna', [
            'users' => $users,
            'pendingUsers' => $pendingUsers,
        ]);
    }

    public function tambahUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'id_pengguna' => 'required|numeric|digits:8|unique:users,id_pengguna',
            'role' => 'required|in:admin,kops,dosen,mahasiswa',
            'jurusan' => 'nullable|string|in:Teknik Informatika dan Komputer,Teknik Elektro,Administrasi Niaga',
            'prodi' => 'nullable|string|in:D4 Teknik Multimedia dan Jaringan,D4 Teknik Komputer dan Jaringan,D4 Teknik Listrik,D4 Teknologi Rekayasa Sistem Elektronika,D3 Administrasi Bisnis,D4 Manajemen Bisnis Jasa Pariwisata',
        ]);

        if (in_array($validated['role'], ['mahasiswa', 'dosen'])) {
            $request->validate([
                'jurusan' => 'required|string',
                'prodi' => 'required|string',
            ]);
        }

        User::create([
            'name' => $validated['name'],
            'id_pengguna' => $validated['id_pengguna'],
            'role' => $validated['role'],
            'jurusan' => $validated['jurusan'] ?? null,
            'prodi' => $validated['prodi'] ?? null,
            'password' => Hash::make($validated['id_pengguna']),
            'status' => 'approved',
        ]);

        return back()->with('success', 'User berhasil ditambahkan!');
    }

    public function approveUser(User $user)
    {
        $user->update(['status' => 'approved']);

        Notification::send($user->id, 'akun_disetujui', [
            'message' => 'Selamat! Akun Anda telah disetujui oleh Admin. Silakan login.',
        ]);

        return back()->with('success', "Akun {$user->name} berhasil disetujui.");
    }

    public function rejectUser(User $user)
    {
        $user->update(['status' => 'rejected']);
        return back()->with('success', "Akun {$user->name} ditolak.");
    }

    // === PERUSAHAAN ===
    public function perusahaan()
    {
        $companies = Company::orderBy('name')->get();
        return Inertia::render('Admin/Perusahaan', [
            'companies' => $companies,
        ]);
    }

    public function tambahPerusahaan(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo_url' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'operational_hours' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        Company::create($validated);
        return back()->with('success', 'Perusahaan berhasil ditambahkan!');
    }

    public function updatePerusahaan(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo_url' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'operational_hours' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $company->update($validated);
        return back()->with('success', 'Perusahaan berhasil diperbarui!');
    }

    public function deletePerusahaan(Company $company)
    {
        $company->delete();
        return back()->with('success', 'Perusahaan berhasil dihapus!');
    }

    // === VERIFIKASI MAGANG ===
    public function verifikasi()
    {
        $applications = InternshipApplication::with(['user', 'company'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Admin/Verifikasi', [
            'applications' => $applications,
        ]);
    }

    public function approveApplication(InternshipApplication $application)
    {
        $application->update(['status' => 'approved_admin']);

        // Notify Kops
        $kopsList = User::where('role', 'kops')->where('status', 'approved')->get();
        foreach ($kopsList as $kops) {
            Notification::send($kops->id, 'verifikasi_magang', [
                'message' => "Pengajuan magang mahasiswa '{$application->user->name}' telah disetujui Admin dan menunggu verifikasi Anda.",
                'application_id' => $application->id,
            ]);
        }

        Notification::send($application->user_id, 'status_magang', [
            'message' => "Pengajuan magang Anda telah disetujui oleh Admin. Menunggu verifikasi Koordinator.",
        ]);

        return back()->with('success', 'Pengajuan magang diterima dan diteruskan ke Koordinator.');
    }

    public function rejectApplication(Request $request, InternshipApplication $application)
    {
        $request->validate(['rejection_reason' => 'required|string']);

        $application->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        Notification::send($application->user_id, 'status_magang', [
            'message' => "Pengajuan magang Anda ditolak. Alasan: {$request->rejection_reason}",
        ]);

        return back()->with('success', 'Pengajuan magang ditolak.');
    }
}
