<template>
    <Head title="Laporan Magang" />
    <AppLayout title="Laporan Magang" :menu-items="menuItems">
        <div class="space-y-6 animate-fade-in-up">
            
            <!-- Download Template -->
            <a :href="route('mahasiswa.laporan.template')" class="glass-card p-5 flex items-center gap-4 hover:bg-white/5 transition group">
                <div class="w-12 h-12 rounded-xl bg-primary-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <p class="font-semibold text-white group-hover:text-primary-300">Download Template Laporan</p>
                    <p class="text-xs text-white/40">Unduh template laporan magang</p>
                </div>
            </a>



            <!-- Status Laporan Aktif -->
            <div class="glass-card overflow-hidden">
                <div class="p-4 border-b border-white/10 flex items-center justify-between">
                    <h3 class="font-semibold text-white">Status Laporan Magang</h3>
                    <span v-if="report" :class="['text-xs px-2 py-1 rounded border', statusClass(report.status)]">{{ statusLabel(report.status) }}</span>
                </div>

                <!-- Belum ada laporan -->
                <div v-if="!report" class="p-8 text-center text-white/40">
                    <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <p>Belum ada laporan yang diunggah.</p>
                </div>

                <!-- Laporan Disetujui -->
                <div v-else-if="report.status === 'approved'" class="p-8 text-center">
                    <div class="w-16 h-16 rounded-full bg-emerald-500/20 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h4 class="text-lg font-bold text-emerald-400 mb-1">Laporan Telah Disetujui</h4>
                    <p class="text-sm text-white/60 mb-4">Laporan magang Anda telah diterima dan disetujui oleh dosen pembimbing.</p>
                    <a :href="'/storage/' + report.file_path" target="_blank" class="inline-flex items-center gap-2 btn-ghost text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Lihat Laporan Anda
                    </a>
                </div>

                <!-- Laporan ada (uploaded / revision) -->
                <div v-else class="p-5 space-y-5">
                    <!-- Info file laporan yang diunggah -->
                    <div class="flex items-center gap-4 p-4 bg-white/5 rounded-lg border border-white/10">
                        <div class="w-10 h-10 rounded-lg bg-primary-500/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-white truncate">{{ fileName(report.file_path) }}</p>
                            <p class="text-xs text-white/40">Diunggah: {{ formatDate(report.updated_at) }}</p>
                        </div>
                        <a :href="'/storage/' + report.file_path" target="_blank" class="btn-ghost btn-sm flex-shrink-0">Lihat</a>
                    </div>

                    <!-- Catatan Revisi (jika status revision) -->
                    <div v-if="report.status === 'revision'" class="p-4 bg-yellow-500/5 border border-yellow-500/20 rounded-lg">
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-5 h-5 text-yellow-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            <h4 class="font-semibold text-yellow-400">Catatan Revisi dari Dosen</h4>
                        </div>
                        <p class="text-sm text-white/80 whitespace-pre-line">{{ report.revision_notes }}</p>

                        <!-- File revisi dari dosen -->
                        <div v-if="report.revision_file_path" class="mt-4 pt-3 border-t border-yellow-500/20">
                            <p class="text-xs text-white/50 mb-2">File lampiran revisi:</p>
                            <a :href="'/storage/' + report.revision_file_path" target="_blank" class="inline-flex items-center gap-2 text-sm text-yellow-400 hover:text-yellow-300 transition bg-yellow-500/10 border border-yellow-500/20 px-3 py-2 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                {{ fileName(report.revision_file_path) }}
                            </a>
                        </div>

                        <div class="mt-4 pt-3 border-t border-yellow-500/20">
                            <p class="text-xs text-yellow-300/70 mb-3">Silakan unggah ulang laporan setelah melakukan perbaikan:</p>
                            <button @click="showUpload = true" class="btn-primary bg-yellow-500 border-yellow-500/50 hover:bg-yellow-400 text-sm">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                Upload Ulang Laporan
                            </button>
                        </div>
                    </div>
                    
                    <!-- Status uploaded (menunggu review) -->
                    <div v-if="report.status === 'uploaded'" class="p-3 bg-blue-500/5 border border-blue-500/20 rounded-lg flex items-center gap-3">
                        <svg class="w-5 h-5 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-sm text-blue-300">Laporan sedang menunggu review dari dosen pembimbing.</p>
                    </div>
                </div>
            </div>

            <!-- Tombol Upload (hanya saat belum ada laporan atau sudah reviewed, bukan revision) -->
            <div v-if="!report || report.status === 'uploaded'" class="flex">
                <button @click="showUpload = true" class="btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    {{ report ? 'Upload Ulang Laporan' : 'Upload Laporan' }}
                </button>
            </div>
        </div>

        <!-- Modal Upload -->
        <Modal :show="showUpload" @close="showUpload = false">
            <h3 class="text-lg font-bold text-white mb-1">Upload Laporan Magang</h3>
            <p class="text-sm text-white/50 mb-4">File baru akan menggantikan laporan sebelumnya (jika ada).</p>
            <form @submit.prevent="submitUpload">
                <FileUpload accept=".pdf,.doc,.docx" hint="PDF, DOC, DOCX. Maks 10MB" @file-selected="f => uploadForm.file = f" :has-error="!!uploadForm.errors.file" :error-message="uploadForm.errors.file" />
                <div class="flex gap-3 justify-end mt-4">
                    <button type="button" @click="showUpload = false" class="btn-ghost">Batal</button>
                    <button type="submit" :disabled="uploadForm.processing || !uploadForm.file" class="btn-primary">
                        {{ uploadForm.processing ? 'Mengunggah...' : 'Upload' }}
                    </button>
                </div>
            </form>
        </Modal>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Components/AppLayout.vue';
import Modal from '@/Components/Modal.vue';
import FileUpload from '@/Components/FileUpload.vue';

defineProps({ report: Object });

const showUpload = ref(false);
const uploadForm = useForm({ file: null });

const submitUpload = () => uploadForm.post(route('mahasiswa.laporan.upload'), {
    forceFormData: true,
    onSuccess: () => { showUpload.value = false; uploadForm.reset(); }
});

const fileName = (path) => path ? path.split('/').pop() : '-';
const formatDate = (d) => d ? new Date(d).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : '-';

const statusLabel = s => ({ uploaded: 'Menunggu Review', reviewed: 'Sedang Direview', approved: 'Disetujui', revision: 'Perlu Revisi' }[s] || s);
const statusClass = s => ({
    uploaded: 'bg-blue-500/10 text-blue-400 border-blue-500/20',
    reviewed: 'bg-blue-500/10 text-blue-400 border-blue-500/20',
    approved: 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
    revision: 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
}[s] || 'bg-white/10 text-white border-white/20');

const menuItems = [
    { label: 'Dashboard', routeName: 'mahasiswa.dashboard', href: route('mahasiswa.dashboard'), icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>' },
    { label: 'Daftar Perusahaan', routeName: 'mahasiswa.magang', href: route('mahasiswa.magang'), icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>' },
    { label: 'Laporan Magang', routeName: 'mahasiswa.laporan', href: route('mahasiswa.laporan'), icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' },
];
</script>
