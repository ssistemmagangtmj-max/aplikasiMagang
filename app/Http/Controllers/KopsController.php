<?php

namespace App\Http\Controllers;

use App\Models\InternshipApplication;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KopsController extends Controller
{
    public function dashboard()
    {
        $totalMahasiswa = User::where('role', 'mahasiswa')->where('status', 'approved')->count();
        $totalDosen = User::where('role', 'dosen')->where('status', 'approved')->count();
        $pendingApps = InternshipApplication::where('status', 'approved_admin')->count();

        return Inertia::render('Kops/Dashboard', [
            'stats' => [
                'totalMahasiswa' => $totalMahasiswa,
                'totalDosen' => $totalDosen,
                'pendingApps' => $pendingApps,
            ],
        ]);
    }

    public function daftarMahasiswa()
    {
        $mahasiswa = User::where('role', 'mahasiswa')
            ->where('status', 'approved')
            ->with(['internshipApplications' => fn($q) => $q->latest()->limit(1)])
            ->get();

        return Inertia::render('Kops/DaftarMahasiswa', [
            'mahasiswa' => $mahasiswa,
        ]);
    }

    public function daftarDosen()
    {
        $dosen = User::where('role', 'dosen')
            ->where('status', 'approved')
            ->withCount('supervisedStudents')
            ->get();

        return Inertia::render('Kops/DaftarDosen', [
            'dosen' => $dosen,
        ]);
    }

    public function verifikasi()
    {
        $applications = InternshipApplication::with(['user', 'company'])
            ->where('status', 'approved_admin')
            ->orderBy('created_at', 'desc')
            ->get();

        $dosenList = User::where('role', 'dosen')->where('status', 'approved')->get();

        return Inertia::render('Kops/Verifikasi', [
            'applications' => $applications,
            'dosenList' => $dosenList,
        ]);
    }

    public function approveApplication(Request $request, InternshipApplication $application)
    {
        $request->validate([
            'supervisor_id' => 'required|exists:users,id',
        ]);

        $supervisor = User::findOrFail($request->supervisor_id);

        $application->update([
            'status' => 'approved_kops',
            'supervisor_id' => $request->supervisor_id,
        ]);

        Notification::send($application->user_id, 'status_magang', [
            'message' => "Selamat! Magang Anda telah disetujui. Dosen pembimbing: {$supervisor->name}.",
        ]);

        Notification::send($supervisor->id, 'mahasiswa_bimbingan', [
            'message' => "Anda ditugaskan membimbing mahasiswa '{$application->user->name}' untuk magang.",
            'student_id' => $application->user_id,
        ]);

        return back()->with('success', 'Pengajuan magang diterima dan dosen pembimbing telah ditugaskan.');
    }

    public function rejectApplication(Request $request, InternshipApplication $application)
    {
        $request->validate(['rejection_reason' => 'required|string']);

        $application->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        Notification::send($application->user_id, 'status_magang', [
            'message' => "Pengajuan magang Anda ditolak oleh Koordinator. Alasan: {$request->rejection_reason}",
        ]);

        return back()->with('success', 'Pengajuan magang ditolak.');
    }
}
