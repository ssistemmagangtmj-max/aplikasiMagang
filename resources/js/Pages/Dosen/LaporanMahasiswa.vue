<template>
    <Head :title="'Laporan - ' + student.name" />
    <AppLayout :title="'Laporan: ' + student.name" :menu-items="menuItems">
        <div class="max-w-3xl mx-auto space-y-6 animate-fade-in-up">
            <Link :href="route('dosen.laporan')" class="text-primary-400 text-sm inline-flex items-center gap-1 hover:text-primary-300 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Daftar Mahasiswa
            </Link>

            <!-- Info Mahasiswa -->
            <div class="glass-card p-5 flex items-center gap-4">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center text-white text-xl font-bold flex-shrink-0">
                    {{ student.name.charAt(0) }}
                </div>
                <div>
                    <h3 class="font-semibold text-white text-lg">{{ student.name }}</h3>
                    <p class="text-sm text-white/50">{{ student.id_pengguna }} · {{ student.jurusan }} · {{ student.prodi }}</p>
                    <p v-if="application" class="text-xs text-white/30 mt-1">Magang di: {{ application.company?.name || application.custom_company_name }}</p>
                </div>
            </div>

            <!-- Status & Isi Laporan -->
            <div class="glass-card overflow-hidden">
                <div class="p-4 border-b border-white/10 flex items-center justify-between">
                    <h3 class="font-semibold text-white">Laporan Magang</h3>
                    <span v-if="report" :class="['text-xs px-2 py-1 rounded border', statusClass(report.status)]">{{ statusLabel(report.status) }}</span>
                </div>

                <!-- Belum ada laporan -->
                <div v-if="!report" class="p-12 text-center text-white/40">
                    <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <p>Mahasiswa belum mengunggah laporan.</p>
                </div>

                <!-- Laporan tersedia -->
                <div v-else class="p-5 space-y-5">
                    <!-- File laporan -->
                    <div class="flex items-center gap-4 p-4 bg-white/5 rounded-lg border border-white/10">
                        <div class="w-10 h-10 rounded-lg bg-primary-500/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-white truncate">{{ fileName(report.file_path) }}</p>
                            <p class="text-xs text-white/40">Diunggah: {{ formatDate(report.updated_at) }}</p>
                        </div>
                        <a :href="'/storage/' + report.file_path" target="_blank" class="btn-primary btn-sm flex-shrink-0">Buka File</a>
                    </div>

                    <!-- Catatan revisi yang sudah diberikan -->
                    <div v-if="report.revision_notes" class="p-4 bg-yellow-500/5 border border-yellow-500/20 rounded-lg">
                        <h4 class="text-xs font-semibold text-yellow-400 mb-1">Catatan Revisi Terakhir</h4>
                        <p class="text-sm text-white/80 whitespace-pre-line">{{ report.revision_notes }}</p>
                        <div v-if="report.revision_file_path" class="mt-3 pt-3 border-t border-yellow-500/20">
                            <p class="text-xs text-white/50 mb-1">File lampiran revisi:</p>
                            <a :href="'/storage/' + report.revision_file_path" target="_blank" class="inline-flex items-center gap-2 text-xs text-yellow-400 hover:text-yellow-300 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                {{ fileName(report.revision_file_path) }}
                            </a>
                        </div>
                    </div>

                    <!-- Aksi Dosen -->
                    <div v-if="report.status !== 'approved'" class="flex flex-wrap gap-3 pt-2">
                        <button @click="confirmApprove" class="btn-primary bg-emerald-500 border-emerald-500/50 hover:bg-emerald-400">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Setujui Laporan (ACC)
                        </button>
                        <button @click="showReviseModal = true" class="btn-ghost border-yellow-500/30 text-yellow-400 hover:bg-yellow-500/10">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            Minta Revisi
                        </button>
                    </div>

                    <!-- Status sudah disetujui -->
                    <div v-if="report.status === 'approved'" class="flex items-center gap-3 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-lg">
                        <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-sm text-emerald-400 font-medium">Laporan ini telah disetujui (ACC).</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Revisi -->
        <Modal :show="showReviseModal" @close="closeReviseModal">
            <h3 class="text-lg font-bold text-white mb-1">Kirim Catatan Revisi</h3>
            <p class="text-sm text-white/50 mb-4">Catatan dan file lampiran (jika ada) akan dikirimkan ke mahasiswa.</p>
            <form @submit.prevent="doRevise" class="space-y-4">
                <div>
                    <label class="block text-sm text-white/70 mb-1">Catatan Revisi <span class="text-red-400">*</span></label>
                    <textarea v-model="reviseForm.revision_notes" class="input-field min-h-[120px] w-full" placeholder="Tuliskan catatan revisi secara detail..." required></textarea>
                    <p v-if="reviseForm.errors.revision_notes" class="text-red-400 text-xs mt-1">{{ reviseForm.errors.revision_notes }}</p>
                </div>
                <div>
                    <label class="block text-sm text-white/70 mb-1">File Lampiran Revisi <span class="text-white/30">(opsional - PDF, DOC, JPG, PNG)</span></label>
                    <FileUpload accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" @file-selected="f => reviseForm.revision_file = f" />
                    <p v-if="reviseForm.errors.revision_file" class="text-red-400 text-xs mt-1">{{ reviseForm.errors.revision_file }}</p>
                </div>
                <div class="flex gap-3 justify-end pt-2">
                    <button type="button" @click="closeReviseModal" class="btn-ghost">Batal</button>
                    <button type="submit" :disabled="reviseForm.processing || !reviseForm.revision_notes" class="btn-primary bg-yellow-500 border-yellow-500/50 hover:bg-yellow-400">
                        {{ reviseForm.processing ? 'Mengirim...' : 'Kirim Revisi' }}
                    </button>
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

const props = defineProps({ student: Object, report: Object, application: Object });

// Approve
const confirmApprove = () => {
    if (confirm(`Setujui (ACC) laporan dari ${props.student.name}?`)) {
        router.patch(route('dosen.laporan.approve', props.report.id));
    }
};

// Revise
const showReviseModal = ref(false);
const reviseForm = useForm({ revision_notes: '', revision_file: null });

const closeReviseModal = () => {
    showReviseModal.value = false;
    reviseForm.reset();
    reviseForm.clearErrors();
};

const doRevise = () => {
    reviseForm.post(route('dosen.laporan.revise', props.report.id), {
        forceFormData: true,
        onSuccess: () => closeReviseModal()
    });
};

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
    { label: 'Dashboard', routeName: 'dosen.dashboard', href: route('dosen.dashboard'), icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>' },
    { label: 'Laporan Magang', routeName: 'dosen.laporan', href: route('dosen.laporan'), icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' },
];
</script>
