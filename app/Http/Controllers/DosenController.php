<?php

namespace App\Http\Controllers;

use App\Models\InternshipApplication;
use App\Models\InternshipReport;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class DosenController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $studentCount = InternshipApplication::where('status', 'approved')->count();

        return Inertia::render('Dosen/Dashboard', [
            'stats' => [
                'studentCount' => $studentCount,
            ],
        ]);
    }

    public function laporan()
    {
        $user = auth()->user();

        $applications = InternshipApplication::with(['user', 'company'])
            ->where('status', 'approved')
            ->get();

        return Inertia::render('Dosen/DaftarMahasiswa', [
            'applications' => $applications,
        ]);
    }

    public function showMahasiswaLaporan(User $user)
    {
        // Ambil satu laporan aktif mahasiswa
        $report = InternshipReport::where('user_id', $user->id)->first();

        $application = InternshipApplication::with('company')
            ->where('user_id', $user->id)
            ->where('status', 'approved')
            ->first();

        return Inertia::render('Dosen/LaporanMahasiswa', [
            'student' => $user->only(['id', 'name', 'id_pengguna', 'jurusan', 'prodi']),
            'report' => $report,
            'application' => $application,
        ]);
    }

    public function approveReport(InternshipReport $report)
    {
        $report->update(['status' => 'approved']);

        Notification::send($report->user_id, 'laporan_disetujui', [
            'message' => 'Laporan magang Anda telah disetujui (ACC) oleh dosen pembimbing.',
            'report_id' => $report->id,
        ]);

        return back()->with('success', 'Laporan mahasiswa telah di-ACC.');
    }

    public function reviseReport(Request $request, InternshipReport $report)
    {
        $request->validate([
            'revision_notes' => 'required|string',
            'revision_file' => 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx',
        ]);

        $updateData = [
            'status' => 'revision',
            'revision_notes' => $request->revision_notes,
        ];

        // Jika ada file revisi baru, hapus yang lama dan simpan yang baru
        if ($request->hasFile('revision_file')) {
            if ($report->revision_file_path) {
                Storage::disk('public')->delete($report->revision_file_path);
            }
            $revFile = $request->file('revision_file');
            $updateData['revision_file_path'] = $revFile->storeAs(
                'revisi-laporan',
                $revFile->getClientOriginalName(),
                'public'
            );
        }

        $report->update($updateData);

        Notification::send($report->user_id, 'laporan_revisi', [
            'message' => "Laporan Anda perlu direvisi. Catatan: {$request->revision_notes}",
            'report_id' => $report->id,
        ]);

        return back()->with('success', 'Catatan revisi telah dikirim ke mahasiswa.');
    }

}
