<template>
    <Head title="Pengguna" />
    <AppLayout title="Kelola Pengguna" :menu-items="menuItems">
        <div class="space-y-6 animate-fade-in-up">
            <div v-if="pendingUsers.length" class="glass-card p-4 border-yellow-500/20 bg-yellow-500/5">
                <h3 class="font-semibold text-yellow-300 mb-3">Menunggu Persetujuan ({{ pendingUsers.length }})</h3>
                <div v-for="u in pendingUsers" :key="u.id" class="flex items-center justify-between p-3 rounded-lg bg-white/5 mb-2 last:mb-0">
                    <div><p class="text-sm text-white font-medium">{{ u.name }}</p><p class="text-xs text-white/40">{{ u.id_pengguna }} · {{ u.jurusan }}</p></div>
                    <div class="flex gap-2">
                        <button @click="$inertia.patch(route('admin.pengguna.approve',u.id))" class="btn-accent !py-1.5 !px-3 text-xs">Setujui</button>
                        <button @click="$inertia.patch(route('admin.pengguna.reject',u.id))" class="btn-danger !py-1.5 !px-3 text-xs">Tolak</button>
                    </div>
                </div>
            </div>
            <div class="flex justify-between items-center">
                <h3 class="font-semibold text-white">Semua Pengguna</h3>
                <button @click="showAdd=true" class="btn-primary text-sm">+ Tambah User</button>
            </div>
            <DataTable :columns="cols" :data="users" searchable :search-keys="['name','id_pengguna','role']">
                <template #cell-status="{value}">
                    <span :class="['badge', value==='approved'?'badge-approved':value==='pending'?'badge-pending':'badge-rejected']">{{ value }}</span>
                </template>
                <template #cell-role="{value}">
                    <span class="badge badge-info capitalize">{{ value }}</span>
                </template>
            </DataTable>
        </div>
        <Modal :show="showAdd" @close="showAdd=false">
            <h3 class="text-lg font-bold text-white mb-4">Tambah User</h3>
            <form @submit.prevent="submitAdd" class="space-y-3">
                <div><label class="block text-sm text-white/70 mb-1">Nama Lengkap</label><input v-model="addForm.name" class="input-field" required /></div>
                <div><label class="block text-sm text-white/70 mb-1">ID Pengguna (8 Digit Angka: NIM/NIDN/NIP)</label><input v-model="addForm.id_pengguna" type="tel" inputmode="numeric" pattern="[0-9]{8}" maxlength="8" class="input-field" placeholder="Contoh: 47334001" required /><p v-if="addForm.errors.id_pengguna" class="text-red-400 text-xs mt-1">{{ addForm.errors.id_pengguna }}</p></div>
                <div><label class="block text-sm text-white/70 mb-1">Role</label>
                    <select v-model="addForm.role" class="input-field">
                        <option value="mahasiswa">Mahasiswa</option>
                        <option value="dosen">Dosen Pembimbing</option>
                        <option value="kops">Koordinator</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div v-if="addForm.role === 'mahasiswa' || addForm.role === 'dosen'">
                    <label class="block text-sm text-white/70 mb-1">Jurusan</label>
                    <select v-model="addForm.jurusan" class="input-field" required>
                        <option value="" disabled>Pilih Jurusan</option>
                        <option value="Teknik Informatika dan Komputer">Teknik Informatika dan Komputer</option>
                        <option value="Teknik Elektro">Teknik Elektro</option>
                        <option value="Administrasi Niaga">Administrasi Niaga</option>
                    </select>
                </div>
                <div v-if="addForm.role === 'mahasiswa' || addForm.role === 'dosen'">
                    <label class="block text-sm text-white/70 mb-1">Program Studi</label>
                    <select v-model="addForm.prodi" class="input-field" required>
                        <option value="" disabled>Pilih Prodi</option>
                        <option value="D4 Teknik Multimedia dan Jaringan">D4 Teknik Multimedia dan Jaringan</option>
                        <option value="D4 Teknik Komputer dan Jaringan">D4 Teknik Komputer dan Jaringan</option>
                        <option value="D4 Teknik Listrik">D4 Teknik Listrik</option>
                        <option value="D4 Teknologi Rekayasa Sistem Elektronika">D4 Teknologi Rekayasa Sistem Elektronika</option>
                        <option value="D3 Administrasi Bisnis">D3 Administrasi Bisnis</option>
                        <option value="D4 Manajemen Bisnis Jasa Pariwisata">D4 Manajemen Bisnis Jasa Pariwisata</option>
                    </select>
                </div>
                <div class="text-xs text-white/50 bg-white/5 p-2 rounded border border-white/10 mt-2">
                    Catatan: Password akan diset secara otomatis sama dengan ID Pengguna.
                </div>
                <div class="flex gap-3 justify-end pt-2">
                    <button type="button" @click="showAdd=false" class="btn-ghost">Batal</button>
                    <button type="submit" :disabled="addForm.processing" class="btn-primary">Simpan</button>
                </div>
            </form>
        </Modal>
    </AppLayout>
</template>
<script setup>
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Components/AppLayout.vue';
import DataTable from '@/Components/DataTable.vue';
import Modal from '@/Components/Modal.vue';
defineProps({ users: Array, pendingUsers: Array });
const showAdd = ref(false);
const addForm = useForm({ name:'', id_pengguna:'', role:'mahasiswa', jurusan:'', prodi:'' });
const submitAdd = () => addForm.post(route('admin.pengguna.store'), { onSuccess:()=>{ showAdd.value=false; addForm.reset(); } });
const cols = [{ key:'name', label:'Nama' },{ key:'id_pengguna', label:'ID Pengguna' },{ key:'role', label:'Role' },{ key:'status', label:'Status' }];
const menuItems = [
    { label:'Dashboard', routeName:'admin.dashboard', href:route('admin.dashboard'), icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>' },
    { label:'Pengguna', routeName:'admin.pengguna', href:route('admin.pengguna'), icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>' },
    { label:'Perusahaan', routeName:'admin.perusahaan', href:route('admin.perusahaan'), icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>' },
    { label:'Verifikasi', routeName:'admin.verifikasi', href:route('admin.verifikasi'), icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' },
];
</script>
