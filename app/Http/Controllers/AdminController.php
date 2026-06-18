<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\InternshipApplication;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function dashboard()
    {
        $pendingApps = InternshipApplication::where('status', 'pending')->count();
        $totalCompanies = Company::count();
        $totalUsers = User::count();

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
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

        return Inertia::render('Admin/Pengguna', [
            'users' => $users,
        ]);
    }

    public function tambahUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'id_pengguna' => 'required|string|unique:users,id_pengguna',
            'role' => 'required|in:admin,dosen,mahasiswa',
        ]);

        User::create([
            'name' => $validated['name'],
            'id_pengguna' => $validated['id_pengguna'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['id_pengguna']),
        ]);

        return back()->with('success', 'User berhasil ditambahkan!');
    }

    public function detailPengguna(User $user)
    {
        $user->load(['internshipApplications.company', 'supervisedStudents.user', 'supervisedStudents.company']);
        return Inertia::render('Admin/DetailPengguna', [
            'user' => $user,
        ]);
    }

    public function updatePengguna(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'id_pengguna' => 'required|string|unique:users,id_pengguna,' . $user->id,
            'role' => 'required|in:mahasiswa,dosen,admin',
            'prodi' => 'nullable|string|max:255',
            'jurusan' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'id_pengguna' => $validated['id_pengguna'],
            'role' => $validated['role'],
            'prodi' => $validated['prodi'] ?? null,
            'jurusan' => $validated['jurusan'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
        }

        $user->update($updateData);

        return back()->with('success', 'Data pengguna berhasil diperbarui!');
    }

    public function deletePengguna(User $user)
    {
        $user->delete();
        return redirect()->route('admin.pengguna')->with('success', 'Pengguna berhasil dihapus!');
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

    // === MAHASISWA MAGANG ===
    public function daftarMahasiswaMagang()
    {
        $applications = InternshipApplication::with(['user', 'company', 'supervisor'])
            ->whereIn('status', ['approved_admin', 'approved'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        $dosens = User::where('role', 'dosen')->get();

        return Inertia::render('Admin/MahasiswaMagang', [
            'applications' => $applications,
            'dosens' => $dosens,
        ]);
    }



    // === DAFTAR PENGAJUAN MAGANG ===
    public function daftarPengajuanMagang()
    {
        $applications = InternshipApplication::with(['user', 'company'])
            ->where('status', '!=', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Admin/DaftarPengajuanMagang', [
            'applications' => $applications,
        ]);
    }
    
    public function detailPengajuan(InternshipApplication $application)
    {
        $application->load(['user', 'company', 'supervisor']);
        $dosens = User::where('role', 'dosen')->get();

        return Inertia::render('Admin/PengajuanMagangDetail', [
            'application' => $application,
            'dosens' => $dosens,
        ]);
    }

    public function tolakPengajuan(Request $request, InternshipApplication $application)
    {
        $request->validate(['rejection_reason' => 'required|string']);
        $application->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason
        ]);
        
        Notification::send($application->user_id, 'pengajuan_magang', [
            'message' => "Pengajuan magang Anda ditolak: {$request->rejection_reason}",
        ]);
        return redirect()->route('admin.pengajuan_magang')->with('success', 'Pengajuan berhasil ditolak.');
    }
    
    public function terbitkanSurat(Request $request, InternshipApplication $application)
    {
        $request->validate([
            'dosen_id' => 'required|exists:users,id',
            'letter_file' => 'required|file|max:5120|mimes:pdf,doc,docx',
        ]);

        $path = $request->file('letter_file')->store('surat-pengantar', 'public');

        $application->update([
            'status' => 'surat_diterbitkan',
            'letter_file' => $path,
            'supervisor_id' => $request->dosen_id,
        ]);
        
        Notification::send($application->user_id, 'surat_pengantar_terbit', [
            'message' => "Surat pengantar magang telah diterbitkan dan Dosen Pembimbing ditetapkan. Silakan unduh surat dan unggah bukti penerimaan perusahaan.",
        ]);

        Notification::send($request->dosen_id, 'mahasiswa_assigned', [
            'message' => "Anda telah ditugaskan sebagai pembimbing untuk mahasiswa {$application->user->name}.",
        ]);
        
        return back()->with('success', 'Surat pengantar berhasil diterbitkan dan dosen pembimbing telah ditetapkan.');
    }

    public function approveBukti(InternshipApplication $application)
    {
        $application->update(['status' => 'approved']);
        Notification::send($application->user_id, 'pengajuan_magang', [
            'message' => "Bukti penerimaan Anda telah disetujui. Anda sekarang aktif magang.",
        ]);
        return back()->with('success', 'Bukti penerimaan disetujui, mahasiswa aktif magang.');
    }

    public function rejectBukti(Request $request, InternshipApplication $application)
    {
        $request->validate(['rejection_reason' => 'required|string']);
        $application->update([
            'status' => 'bukti_ditolak',
            'rejection_reason' => $request->rejection_reason,
        ]);
        Notification::send($application->user_id, 'pengajuan_magang', [
            'message' => "Bukti penerimaan perusahaan ditolak: " . $request->rejection_reason . ". Silakan periksa dan unggah kembali.",
        ]);
        return back()->with('success', 'Bukti penerimaan ditolak.');
    }

    public function downloadBuktiPenerimaan(InternshipApplication $application)
    {
        $filePath = $application->acceptance_proof_file;

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            abort(404, 'File bukti penerimaan tidak ditemukan.');
        }

        $fullPath = Storage::disk('public')->path($filePath);
        $fileName = 'Bukti_Penerimaan_' . ($application->user->name ?? $application->id) . '_' . basename($filePath);

        return response()->download($fullPath, $fileName);
    }
}

