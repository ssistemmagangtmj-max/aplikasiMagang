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
        $user->load(['internshipApplications.company']);
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
        $applications = InternshipApplication::with(['user', 'company'])
            ->whereIn('status', ['approved_admin', 'approved'])
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Admin/MahasiswaMagang', [
            'applications' => $applications,
        ]);
    }



    // === DAFTAR PENGAJUAN MAGANG ===
    public function daftarPengajuanMagang()
    {
        $mahasiswas = User::where('role', 'mahasiswa')
            ->with(['internshipApplications' => function ($query) {
                $query->with('company')->latest()->limit(1);
            }])
            ->get();

        return Inertia::render('Admin/DaftarPengajuanMagang', [
            'mahasiswas' => $mahasiswas,
        ]);
    }
    
    public function accPengajuan(InternshipApplication $application)
    {
        $application->update(['status' => 'approved']);
        Notification::send($application->user_id, 'pengajuan_magang', [
            'message' => "Surat Terbit, Proses surat sudah jadi silahkan ke akademik mengisi pengajuan akademik.",
        ]);
        return back()->with('success', 'Pengajuan berhasil di-ACC.');
    }
}

