<template>
    <Head title="Detail Pengguna" />
    <AppLayout title="Detail Pengguna" :menu-items="menuItems">
        <div class="space-y-6 animate-fade-in-up">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-4">
                    <button @click="goBack" class="btn-ghost btn-sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Kembali
                    </button>
                    <h2 class="text-xl font-bold text-white">Detail Pengguna</h2>
                </div>
                <div class="flex gap-2">
                    <button @click="showEdit=true" class="btn-ghost btn-sm text-blue-400 hover:text-blue-300 border border-blue-500/30">Edit</button>
                    <button @click="showDelete=true" class="btn-ghost btn-sm text-red-400 hover:text-red-300 border border-red-500/30">Hapus</button>
                </div>
            </div>
            
            <div class="glass-card p-6">
                <h3 class="font-semibold text-white mb-4 border-b border-white/10 pb-2">Informasi Dasar</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-white/50">Nama Lengkap</p>
                        <p class="text-sm text-white font-medium">{{ user.name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-white/50">NIP / NIM (ID Pengguna)</p>
                        <p class="text-sm text-white">{{ user.id_pengguna }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-white/50">Role</p>
                        <p class="text-sm mt-1">
                            <span class="badge badge-info capitalize">{{ user.role }}</span>
                        </p>
                    </div>
                    <div v-if="user.role === 'mahasiswa'">
                        <p class="text-xs text-white/50">Program Studi / Jurusan</p>
                        <p class="text-sm text-white">{{ user.prodi || '-' }} / {{ user.jurusan || '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Bagian Khusus Mahasiswa -->
            <div v-if="user.role === 'mahasiswa' && user.internship_applications?.length > 0" class="glass-card p-6 mt-6">
                <h3 class="font-semibold text-white mb-4 border-b border-white/10 pb-2">Riwayat Pengajuan Magang</h3>
                <div class="space-y-4">
                    <div v-for="app in user.internship_applications" :key="app.id" class="p-4 bg-white/5 border border-white/10 rounded-lg flex flex-col md:flex-row gap-4 justify-between md:items-center">
                        <div>
                            <p class="text-sm font-semibold text-white">{{ app.company?.name || app.custom_company_name }}</p>
                            <p class="text-xs text-white/60">Periode: {{ formatDate(app.period_start) }} - {{ formatDate(app.period_end) }}</p>
                        </div>
                        <div>
                            <span :class="['text-xs px-2 py-1 rounded border inline-block', statusClass(app.status)]">{{ statusLabel(app.status) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bagian Khusus Dosen -->
            <div v-if="user.role === 'dosen' && user.supervised_students?.length > 0" class="glass-card p-6 mt-6">
                <h3 class="font-semibold text-white mb-4 border-b border-white/10 pb-2">Mahasiswa Bimbingan Magang</h3>
                <div class="space-y-4">
                    <div v-for="app in user.supervised_students" :key="app.id" class="p-4 bg-white/5 border border-white/10 rounded-lg flex flex-col md:flex-row gap-4 justify-between md:items-center">
                        <div>
                            <p class="text-sm font-semibold text-white">{{ app.user?.name }} <span class="text-xs text-white/50">({{ app.user?.id_pengguna }})</span></p>
                            <p class="text-xs text-white/60 mt-1">Perusahaan: {{ app.company?.name || app.custom_company_name }}</p>
                        </div>
                        <div>
                            <span :class="['text-xs px-2 py-1 rounded border inline-block', statusClass(app.status)]">{{ statusLabel(app.status) }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Edit Modal -->
        <Modal :show="showEdit" @close="showEdit=false">
            <h3 class="text-lg font-bold text-white mb-4">Edit Pengguna</h3>
            <form @submit.prevent="submitEdit" class="space-y-3">
                <div><label class="block text-sm text-white/70 mb-1">Nama Lengkap</label><input v-model="editForm.name" class="input-field" required /></div>
                <div><label class="block text-sm text-white/70 mb-1">NIP / NIM</label><input v-model="editForm.id_pengguna" type="text" class="input-field" required /><p v-if="editForm.errors.id_pengguna" class="text-red-400 text-xs mt-1">{{ editForm.errors.id_pengguna }}</p></div>
                <div><label class="block text-sm text-white/70 mb-1">Role</label>
                    <select v-model="editForm.role" class="input-field">
                        <option value="mahasiswa">Mahasiswa</option>
                        <option value="dosen">Dosen Pembimbing</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div v-if="editForm.role === 'mahasiswa'">
                    <label class="block text-sm text-white/70 mb-1">Program Studi</label><input v-model="editForm.prodi" class="input-field" />
                </div>
                <div v-if="editForm.role === 'mahasiswa'">
                    <label class="block text-sm text-white/70 mb-1">Jurusan</label><input v-model="editForm.jurusan" class="input-field" />
                </div>
                <div>
                    <label class="block text-sm text-white/70 mb-1">Password Baru (Opsional)</label>
                    <input v-model="editForm.password" type="password" class="input-field" placeholder="Kosongkan jika tidak ingin mengubah password" />
                </div>
                <div class="flex gap-3 justify-end pt-4">
                    <button type="button" @click="showEdit=false" class="btn-ghost">Batal</button>
                    <button type="submit" :disabled="editForm.processing" class="btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </Modal>

        <!-- Delete Modal -->
        <Modal :show="showDelete" @close="showDelete=false">
            <h3 class="text-lg font-bold text-red-500 mb-4">Hapus Pengguna</h3>
            <p class="text-white/80 text-sm mb-6">Apakah Anda yakin ingin menghapus pengguna <strong>{{ user.name }}</strong>? Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex gap-3 justify-end">
                <button @click="showDelete=false" class="btn-ghost">Batal</button>
                <button @click="confirmDelete" class="px-4 py-2 bg-red-500/20 text-red-500 border border-red-500/50 rounded hover:bg-red-500 hover:text-white transition">Ya, Hapus</button>
            </div>
        </Modal>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Components/AppLayout.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    user: Object,
});

const showEdit = ref(false);
const showDelete = ref(false);

const editForm = useForm({
    name: props.user.name,
    id_pengguna: props.user.id_pengguna,
    role: props.user.role,
    prodi: props.user.prodi || '',
    jurusan: props.user.jurusan || '',
    password: '',
});

const submitEdit = () => {
    editForm.put(route('admin.pengguna.update', props.user.id), {
        onSuccess: () => {
            showEdit.value = false;
            editForm.password = '';
        }
    });
};

const confirmDelete = () => {
    router.delete(route('admin.pengguna.destroy', props.user.id), {
        onSuccess: () => showDelete.value = false
    });
};

const goBack = () => {
    window.history.back();
};

const formatDate = (dateString) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' });
};

const statusLabel = (status) => {
    const map = {
        'pending': 'Menunggu Verifikasi',
        'surat_diterbitkan': 'Menunggu Bukti Diterima',
        'bukti_diunggah': 'Memverifikasi Bukti',
        'bukti_ditolak': 'Bukti Ditolak',
        'approved': 'Aktif Magang',
        'rejected': 'Ditolak',
    };
    return map[status] || status;
};

const statusClass = (status) => {
    const map = {
        'pending': 'bg-amber-500/20 text-amber-500 border-amber-500/30',
        'surat_diterbitkan': 'bg-blue-500/20 text-blue-500 border-blue-500/30',
        'bukti_diunggah': 'bg-purple-500/20 text-purple-500 border-purple-500/30',
        'bukti_ditolak': 'bg-red-500/20 text-red-500 border-red-500/30',
        'approved': 'bg-emerald-500/20 text-emerald-500 border-emerald-500/30',
        'rejected': 'bg-red-500/20 text-red-500 border-red-500/30',
    };
    return map[status] || 'bg-white/10 text-white/70 border-white/20';
};

const menuItems = [
    { label:'Dashboard', routeName:'admin.dashboard', href:route('admin.dashboard'), icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>' },
    { label:'Pengguna', routeName:'admin.pengguna', href:route('admin.pengguna'), icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>' },
    { label:'Daftar Perusahaan', routeName:'admin.perusahaan', href:route('admin.perusahaan'), icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>' },
    { label:'Pengajuan Surat Permohonan Magang', routeName:'admin.pengajuan_magang', href:route('admin.pengajuan_magang'), icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' },
    { label:'Daftar Mahasiswa Magang', routeName:'admin.mahasiswa_magang', href:route('admin.mahasiswa_magang'), icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>' },
];
</script>
