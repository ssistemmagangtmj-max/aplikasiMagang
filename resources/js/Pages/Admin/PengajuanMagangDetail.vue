<template>
    <Head title="Detail Pengajuan Magang" />
    <AppLayout title="Detail Pengajuan Magang" :menu-items="menuItems">
        <div class="space-y-6 animate-fade-in-up">
            <div class="flex items-center gap-4">
                <Link :href="['approved', 'approved_admin'].includes(application.status) ? route('admin.mahasiswa_magang') : route('admin.pengajuan_magang')" class="btn-ghost btn-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali
                </Link>
                <h2 class="text-xl font-bold text-white">Detail Pengajuan</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Info Mahasiswa -->
                <div class="glass-card p-6">
                    <h3 class="font-semibold text-white mb-4 border-b border-white/10 pb-2">Informasi Mahasiswa</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-white/50">Nama</p>
                            <p class="text-sm text-white">{{ application.user?.name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-white/50">NIM</p>
                            <p class="text-sm text-white">{{ application.user?.id_pengguna }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-white/50">Program Studi / Jurusan</p>
                            <p class="text-sm text-white">{{ application.user?.prodi }} / {{ application.user?.jurusan }}</p>
                        </div>
                        <div v-if="application.supervisor" class="pt-2 border-t border-white/5 mt-2">
                            <p class="text-xs text-white/50">Dosen Pembimbing</p>
                            <p class="text-sm text-white font-medium">{{ application.supervisor.name }}</p>
                        </div>
                    </div>
                </div>

                <!-- Info Magang -->
                <div class="glass-card p-6">
                    <h3 class="font-semibold text-white mb-4 border-b border-white/10 pb-2">Informasi Tujuan Magang</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-white/50">Nama Perusahaan</p>
                            <p class="text-sm text-white font-medium">{{ application.company?.name || application.custom_company_name }}</p>
                        </div>
                        <div v-if="application.custom_company_address">
                            <p class="text-xs text-white/50">Alamat Perusahaan</p>
                            <p class="text-sm text-white">{{ application.custom_company_address }}</p>
                        </div>
                        <div class="flex gap-4">
                            <div>
                                <p class="text-xs text-white/50">Mulai</p>
                                <p class="text-sm text-white">{{ formatDate(application.period_start) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-white/50">Selesai</p>
                                <p class="text-sm text-white">{{ formatDate(application.period_end) }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs text-white/50">Status Pengajuan</p>
                            <span :class="['text-xs px-2 py-1 rounded border inline-block mt-1', statusClass(application.status)]">{{ statusLabel(application.status) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tahap 2: Verifikasi & Penerbitan Surat -->
            <div v-if="['pending', 'processing'].includes(application.status)" class="glass-card p-6 border border-primary-500/30 shadow-[0_0_15px_rgba(59,130,246,0.1)] relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <svg class="w-24 h-24 text-primary-500" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14z"/></svg>
                </div>
                
                <h3 class="font-semibold text-white mb-2 relative z-10">Verifikasi & Terbitkan Surat Pengantar</h3>
                <p class="text-sm text-white/60 mb-6 relative z-10">Pilih dosen pembimbing dan unggah surat pengantar magang untuk diserahkan ke mahasiswa.</p>
                
                <form @submit.prevent="submitTerbitkan" class="relative z-10 space-y-4">
                    <div>
                        <label class="block text-sm text-white/70 mb-1">Dosen Pembimbing</label>
                        <select v-model="formTerbit.dosen_id" class="input-field w-full md:w-1/2">
                            <option value="">-- Pilih Dosen --</option>
                            <option v-for="d in dosens" :key="d.id" :value="d.id">{{ d.name }}</option>
                        </select>
                        <p v-if="formTerbit.errors.dosen_id" class="text-red-400 text-xs mt-1">{{ formTerbit.errors.dosen_id }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm text-white/70 mb-1">Surat Pengantar (PDF/Word)</label>
                        <FileUpload accept=".pdf,.doc,.docx" @file-selected="f => formTerbit.letter_file = f" />
                        <p v-if="formTerbit.errors.letter_file" class="text-red-400 text-xs mt-1">{{ formTerbit.errors.letter_file }}</p>
                    </div>
                    
                    <div class="flex gap-3 pt-4">
                        <button type="submit" :disabled="formTerbit.processing" class="btn-primary">Terbitkan Surat</button>
                        <button type="button" @click="showTolakModal = true" class="btn-ghost text-red-400 hover:text-red-300 border-red-500/30">Tolak Pengajuan</button>
                    </div>
                </form>
            </div>

            <!-- Tahap 4: Verifikasi Bukti Penerimaan -->
            <div v-if="['bukti_diunggah'].includes(application.status)" class="glass-card p-6 border border-orange-500/30 shadow-[0_0_15px_rgba(249,115,22,0.1)] relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <svg class="w-24 h-24 text-orange-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 22h20L12 2zm0 4.5l6.5 13.5h-13L12 6.5zM11 10v4h2v-4h-2zm0 6v2h2v-2h-2z"/></svg>
                </div>
                
                <h3 class="font-semibold text-white mb-2 relative z-10">Verifikasi Bukti Penerimaan</h3>
                <p class="text-sm text-white/60 mb-6 relative z-10">Mahasiswa telah mengunggah bukti penerimaan dari perusahaan. Silakan tinjau dan verifikasi dokumen tersebut.</p>
                
                <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center relative z-10">
                    <a :href="route('admin.pengajuan_magang.download_bukti', application.id)" target="_blank" class="btn-primary bg-orange-500 hover:bg-orange-600 border-orange-500/50">
                        Unduh Dokumen Bukti
                    </a>
                    
                    <div class="flex gap-2">
                        <button @click="approveBukti" :disabled="isProcessingBukti" class="btn-primary bg-emerald-500 hover:bg-emerald-600 border-emerald-500/50">Setujui (Aktifkan Magang)</button>
                        <button @click="showTolakBuktiModal = true" class="btn-ghost border-red-500/30 text-red-400 hover:bg-red-500/10">Tolak Bukti</button>
                    </div>
                </div>
            </div>
            
            <!-- Info Status Lanjut -->
            <div v-if="['surat_diterbitkan', 'bukti_ditolak'].includes(application.status)" class="glass-card p-6 flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-white mb-1">Menunggu Mahasiswa</h3>
                    <p class="text-sm text-white/60">Menunggu mahasiswa mengunggah Bukti Penerimaan dari perusahaan.</p>
                    <p v-if="application.status === 'bukti_ditolak'" class="text-xs text-red-400 mt-2">Status terakhir: Bukti ditolak ({{ application.rejection_reason }}). Mahasiswa perlu mengunggah ulang.</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-white/5 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            
            <div v-if="application.status === 'approved'" class="glass-card p-6 flex items-center justify-between border border-emerald-500/30">
                <div>
                    <h3 class="font-semibold text-emerald-400 mb-1">Magang Aktif</h3>
                    <p class="text-sm text-white/60">Pengajuan ini telah disetujui sepenuhnya dan mahasiswa sedang melaksanakan magang.</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-emerald-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
            </div>
            
            <div v-if="application.status === 'rejected'" class="glass-card p-6 border border-red-500/30">
                <h3 class="font-semibold text-red-400 mb-1">Pengajuan Ditolak</h3>
                <p class="text-sm text-white/80">Alasan: <span class="font-medium">{{ application.rejection_reason }}</span></p>
            </div>
        </div>

        <!-- Modal Tolak Pengajuan -->
        <Modal :show="showTolakModal" @close="showTolakModal = false">
            <h3 class="text-lg font-bold text-white mb-2">Tolak Pengajuan Magang</h3>
            <p class="text-white/60 text-sm mb-4">Berikan alasan mengapa pengajuan magang mahasiswa ini ditolak.</p>
            <form @submit.prevent="submitTolak">
                <div class="mb-4">
                    <label class="block text-sm text-white/70 mb-1">Alasan Penolakan</label>
                    <textarea v-model="formTolak.rejection_reason" class="input-field w-full h-24" placeholder="Misal: Kuota penuh, perusahaan tidak valid, dll" required></textarea>
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" @click="showTolakModal = false" class="btn-ghost">Batal</button>
                    <button type="submit" :disabled="formTolak.processing" class="btn-primary bg-red-500 hover:bg-red-600">Konfirmasi Penolakan</button>
                </div>
            </form>
        </Modal>

        <!-- Modal Tolak Bukti -->
        <Modal :show="showTolakBuktiModal" @close="showTolakBuktiModal = false">
            <h3 class="text-lg font-bold text-white mb-2">Tolak Bukti Penerimaan</h3>
            <p class="text-white/60 text-sm mb-4">Berikan alasan mengapa bukti penerimaan dari perusahaan ditolak (misal: dokumen buram, salah upload, dll). Mahasiswa harus mengunggah ulang.</p>
            <form @submit.prevent="submitTolakBukti">
                <div class="mb-4">
                    <label class="block text-sm text-white/70 mb-1">Alasan Penolakan</label>
                    <textarea v-model="formTolakBukti.rejection_reason" class="input-field w-full h-24" placeholder="Misal: Dokumen buram, bukan surat resmi, dll" required></textarea>
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" @click="showTolakBuktiModal = false" class="btn-ghost">Batal</button>
                    <button type="submit" :disabled="formTolakBukti.processing" class="btn-primary bg-red-500 hover:bg-red-600 border-red-500/50">Konfirmasi Penolakan Bukti</button>
                </div>
            </form>
        </Modal>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Components/AppLayout.vue';
import Modal from '@/Components/Modal.vue';
import FileUpload from '@/Components/FileUpload.vue';

const props = defineProps({
    application: Object,
    dosens: Array
});

const formatDate = d => d ? new Date(d).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) : '-';

const statusClass = (status) => {
    switch(status) {
        case 'pending': return 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20';
        case 'processing': return 'bg-blue-500/10 text-blue-500 border-blue-500/20';
        case 'surat_diterbitkan': return 'bg-purple-500/10 text-purple-400 border-purple-500/20';
        case 'bukti_diunggah': return 'bg-orange-500/10 text-orange-400 border-orange-500/20';
        case 'approved_admin': return 'bg-green-500/10 text-green-500 border-green-500/20';
        case 'approved': return 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20';
        case 'bukti_ditolak':
        case 'rejected': return 'bg-red-500/10 text-red-500 border-red-500/20';
        default: return 'bg-white/10 text-white border-white/20';
    }
};

const statusLabel = (status) => {
    switch(status) {
        case 'pending': return 'Menunggu Verifikasi';
        case 'processing': return 'Sedang Diproses';
        case 'surat_diterbitkan': return 'Surat Diterbitkan';
        case 'bukti_diunggah': return 'Menunggu Verifikasi Bukti';
        case 'approved_admin': return 'Disetujui (Admin)';
        case 'approved': return 'Aktif Magang';
        case 'bukti_ditolak': return 'Bukti Ditolak';
        case 'rejected': return 'Ditolak';
        default: return status;
    }
};

// Form Terbit Surat
const formTerbit = useForm({ dosen_id: '', letter_file: null });
const submitTerbitkan = () => {
    formTerbit.post(route('admin.pengajuan_magang.terbitkan', props.application.id), {
        forceFormData: true,
        preserveScroll: true
    });
};

// Form Tolak Pengajuan
const showTolakModal = ref(false);
const formTolak = useForm({ rejection_reason: '' });
const submitTolak = () => {
    formTolak.patch(route('admin.pengajuan_magang.tolak', props.application.id), {
        onSuccess: () => { showTolakModal.value = false; }
    });
};

// Approve Bukti
const isProcessingBukti = ref(false);
const approveBukti = () => {
    if(confirm('Setujui bukti penerimaan ini? Status mahasiswa akan menjadi aktif magang.')) {
        isProcessingBukti.value = true;
        router.patch(route('admin.pengajuan_magang.approve_bukti', props.application.id), {}, {
            onFinish: () => isProcessingBukti.value = false
        });
    }
};

// Form Tolak Bukti
const showTolakBuktiModal = ref(false);
const formTolakBukti = useForm({ rejection_reason: '' });
const submitTolakBukti = () => {
    formTolakBukti.patch(route('admin.pengajuan_magang.reject_bukti', props.application.id), {
        onSuccess: () => { showTolakBuktiModal.value = false; }
    });
};

const menuItems = [
    { label:'Dashboard', routeName:'admin.dashboard', href:route('admin.dashboard'), icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>' },
    { label:'Pengguna', routeName:'admin.pengguna', href:route('admin.pengguna'), icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>' },
    { label:'Daftar Perusahaan', routeName:'admin.perusahaan', href:route('admin.perusahaan'), icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>' },
    { label:'Daftar Mahasiswa Magang', routeName:'admin.mahasiswa_magang', href:route('admin.mahasiswa_magang'), icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>' },
    { label:'Daftar Pengajuan Magang', routeName:'admin.pengajuan_magang', href:route('admin.pengajuan_magang'), icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' },
];
</script>
