<template>
    <Head title="Dashboard Mahasiswa" />
    <AppLayout title="Dashboard" :menu-items="menuItems">
        <div class="space-y-6 animate-fade-in-up">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="glass-card p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-primary-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.193 23.193 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-white/50 text-sm">Status Magang</p>
                            <p class="text-white font-semibold">{{ activeApplication ? statusLabel(activeApplication.status) : 'Belum mendaftar' }}</p>
                        </div>
                    </div>
                    <div v-if="activeApplication" class="mt-4 pt-4 border-t border-white/10 space-y-2">
                        <p class="text-sm text-white/60">Perusahaan: <span class="text-white">{{ activeApplication.company?.name || activeApplication.custom_company_name || '-' }}</span></p>
                        <p v-if="activeApplication.supervisor" class="text-sm text-white/60">Dosen Pembimbing: <span class="text-white">{{ activeApplication.supervisor.name }}</span></p>
                    </div>
                </div>
                <div class="glass-card p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-accent-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-white/50 text-sm">Laporan</p>
                            <p class="text-white font-semibold">{{ latestReport ? reportStatusLabel(latestReport.status) : 'Belum diupload' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Instruksi Alur Magang -->
            <div class="glass-card p-6">
                <h3 class="font-semibold text-white mb-4">Alur & Informasi Magang</h3>
                <ul class="list-disc pl-5 text-sm text-white/80 space-y-2">
                    <li>Pilih tempat magang dari <strong class="text-white">Daftar Perusahaan</strong> (jika perusahaan belum terdaftar, Anda dapat menambahkannya dari halaman tersebut).</li>
                    <li>Setelah diajukan, Admin akan memverifikasi dan menetapkan Dosen Pembimbing serta menerbitkan <strong>Surat Pengantar</strong>.</li>
                    <li>Unduh Surat Pengantar dan serahkan ke perusahaan tujuan.</li>
                    <li>Mintalah <strong>Bukti Penerimaan</strong> dari perusahaan (surat balasan/SK/dokumen sejenis), lalu unggah ke sistem ini.</li>
                    <li>Setelah Admin memverifikasi bukti tersebut, status Anda akan berubah menjadi <strong>Aktif Magang</strong>.</li>
                </ul>
            </div>

            <div v-if="activeApplication && activeApplication.status !== 'rejected'" class="glass-card p-6">
                <h3 class="font-semibold text-white mb-6">Progres Pengajuan Magang</h3>
                <div class="relative flex flex-col sm:flex-row justify-between mb-8">
                    <!-- connecting line (horizontal) -->
                    <div class="hidden sm:block absolute top-4 left-[10%] right-[10%] h-0.5 bg-white/10 z-0"></div>
                    
                    <!-- Step 1: Pengajuan -->
                    <div class="flex flex-row sm:flex-col items-center gap-4 sm:gap-2 z-10 w-full sm:w-1/4 mb-6 sm:mb-0 relative">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm bg-primary-500 text-white shadow-[0_0_15px_rgba(59,130,246,0.5)] ring-4 ring-slate-900/50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div class="sm:text-center mt-2">
                            <p class="text-sm font-bold text-primary-700">1. Pengajuan</p>
                        </div>
                    </div>
                    
                    <!-- Step 2: Surat Diterbitkan -->
                    <div class="flex flex-row sm:flex-col items-center gap-4 sm:gap-2 z-10 w-full sm:w-1/4 mb-6 sm:mb-0 relative">
                        <div :class="['w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm transition-colors ring-4 ring-slate-900/50', stepCompleted(2) ? 'bg-primary-500 text-white shadow-[0_0_15px_rgba(59,130,246,0.5)]' : (activeApplication.status === 'pending' ? 'bg-primary-500/20 text-primary-600 border border-primary-500/30' : 'bg-white/10 text-white/40')]">
                            <svg v-if="stepCompleted(2)" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            <span v-else>2</span>
                        </div>
                        <div class="sm:text-center mt-2">
                            <p :class="['text-sm font-bold', stepCompleted(2) ? 'text-primary-700' : 'text-white/80']">2. Surat Terbit</p>
                            <div v-if="activeApplication.letter_file" class="mt-2">
                                <a :href="'/storage/' + activeApplication.letter_file" target="_blank" class="inline-flex items-center gap-1 text-[10px] sm:text-xs font-semibold bg-primary-500/10 border border-primary-500/20 text-primary-600 px-2 py-1 rounded hover:bg-primary-500/20 transition">
                                    Unduh Surat
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Bukti Diunggah -->
                    <div class="flex flex-row sm:flex-col items-center gap-4 sm:gap-2 z-10 w-full sm:w-1/4 mb-6 sm:mb-0 relative">
                        <div :class="['w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm transition-colors ring-4 ring-slate-900/50', stepCompleted(3) ? 'bg-primary-500 text-white shadow-[0_0_15px_rgba(59,130,246,0.5)]' : 'bg-white/10 text-white/40']">
                            <svg v-if="stepCompleted(3)" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            <span v-else>3</span>
                        </div>
                        <div class="sm:text-center mt-2">
                            <p :class="['text-sm font-bold', stepCompleted(3) ? 'text-primary-700' : 'text-white/50']">3. Bukti Penerimaan</p>
                        </div>
                    </div>

                    <!-- Step 4: Approved -->
                    <div class="flex flex-row sm:flex-col items-center gap-4 sm:gap-2 z-10 w-full sm:w-1/4 relative">
                        <div :class="['w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm transition-colors ring-4 ring-slate-900/50', stepCompleted(4) ? 'bg-primary-500 text-white shadow-[0_0_15px_rgba(59,130,246,0.5)]' : 'bg-white/10 text-white/40']">
                            <svg v-if="stepCompleted(4)" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            <span v-else>4</span>
                        </div>
                        <div class="sm:text-center mt-2">
                            <p :class="['text-sm font-bold', stepCompleted(4) ? 'text-primary-700' : 'text-white/50']">4. Aktif Magang</p>
                        </div>
                    </div>
                </div>

                <!-- Aksi Khusus: Form Upload Bukti Penerimaan (Jika status: surat_diterbitkan atau bukti_ditolak) -->
                <div v-if="['surat_diterbitkan', 'bukti_ditolak'].includes(activeApplication.status)" class="mt-8 p-5 bg-primary-500/5 border border-primary-500/20 rounded-xl">
                    <h4 class="font-bold text-white mb-3 text-lg flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Tindak Lanjut yang Harus Dilakukan
                    </h4>
                    <ol class="list-decimal pl-5 text-sm text-white/80 space-y-2 mb-6">
                        <li>Unduh surat pengantar magang melalui tombol pada langkah 2 di atas.</li>
                        <li>Serahkan surat pengantar magang kepada perusahaan tujuan.</li>
                        <li>Peroleh bukti bahwa perusahaan bersedia menerima magang (surat balasan, SK, dsb).</li>
                        <li>Unggah bukti penerimaan tersebut ke sistem.</li>
                        <li>Tunggu verifikasi dan persetujuan akhir dari admin.</li>
                    </ol>

                    <div v-if="activeApplication.status === 'bukti_ditolak'" class="mb-4 p-3 bg-red-500/10 border border-red-500/20 text-red-400 rounded-lg text-sm">
                        <strong>Perhatian:</strong> Bukti sebelumnya ditolak dengan alasan: {{ activeApplication.rejection_reason }}. Silakan unggah ulang.
                    </div>

                    <form @submit.prevent="submitBukti" class="mt-4 pt-4 border-t border-primary-500/20">
                        <label class="block text-sm font-semibold text-white mb-2">Unggah Bukti Penerimaan (PDF/Word/JPG/PNG)</label>
                        <FileUpload accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" @file-selected="f => form.acceptance_proof_file = f" />
                        <p v-if="form.errors.acceptance_proof_file" class="text-red-400 text-xs mt-1">{{ form.errors.acceptance_proof_file }}</p>
                        
                        <div class="mt-4 text-right">
                            <button type="submit" :disabled="form.processing || !form.acceptance_proof_file" class="btn-primary">
                                {{ form.processing ? 'Mengunggah...' : 'Unggah Bukti' }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Pesan Menunggu Verifikasi Bukti -->
                <div v-if="activeApplication.status === 'bukti_diunggah'" class="mt-8 p-5 bg-orange-500/10 border border-orange-500/30 rounded-xl text-center">
                    <svg class="w-12 h-12 text-orange-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <h4 class="font-bold text-white text-lg mb-1">Bukti Sedang Diverifikasi</h4>
                    <p class="text-sm text-white/70">Terima kasih telah mengunggah bukti penerimaan. Admin akan segera memverifikasinya. Anda akan menjadi peserta magang aktif setelah bukti disetujui.</p>
                </div>
            </div>
            
            <div v-if="activeApplication && activeApplication.status === 'rejected'" class="glass-card p-6 border border-red-500/30">
                <h3 class="font-semibold text-red-400 mb-2">Pengajuan Ditolak</h3>
                <p class="text-sm text-white/80">Pengajuan magang Anda ditolak oleh Admin. Alasan: <strong>{{ activeApplication.rejection_reason }}</strong></p>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Components/AppLayout.vue';
import FileUpload from '@/Components/FileUpload.vue';

const props = defineProps({ activeApplication: Object, latestReport: Object });

const form = useForm({
    acceptance_proof_file: null
});

const submitBukti = () => {
    form.post(route('mahasiswa.magang.upload_bukti'), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => form.reset()
    });
};

const stepCompleted = (step) => {
    const s = props.activeApplication?.status;
    if (!s) return false;
    if (step === 1) return true;
    if (step === 2) return ['surat_diterbitkan', 'bukti_diunggah', 'bukti_ditolak', 'approved'].includes(s);
    if (step === 3) return ['bukti_diunggah', 'approved'].includes(s); // if ditolak, step 3 goes back to pending/incomplete visually
    if (step === 4) return s === 'approved';
    return false;
};

const menuItems = [
    { label: 'Dashboard', routeName: 'mahasiswa.dashboard', href: route('mahasiswa.dashboard'), icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>' },
    { label: 'Daftar Perusahaan', routeName: 'mahasiswa.magang', href: route('mahasiswa.magang'), icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>' },
    { label: 'Laporan Magang', routeName: 'mahasiswa.laporan', href: route('mahasiswa.laporan'), icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' },
];

const statusLabel = (s) => ({ pending: 'Pengajuan Terkirim', processing: 'Diproses Admin', surat_diterbitkan: 'Surat Diterbitkan', bukti_diunggah: 'Menunggu Verifikasi', bukti_ditolak: 'Bukti Ditolak', approved: 'Aktif Magang', rejected: 'Ditolak' }[s] || s);
const reportStatusLabel = (s) => ({ uploaded: 'Terkirim', reviewed: 'Sedang Direview', approved: 'Disetujui', revision: 'Perlu Revisi' }[s] || s);
</script>
