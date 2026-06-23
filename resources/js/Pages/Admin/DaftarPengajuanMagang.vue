<template>
    <Head title="Daftar Pengajuan Magang" />
    <AppLayout title="Daftar Pengajuan Magang" :menu-items="menuItems">
        <div class="space-y-4 animate-fade-in-up">
            <p class="text-white/50 text-sm">Daftar semua mahasiswa beserta status pengajuan magang mereka</p>
            
            <div class="glass-card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-white/80">
                        <thead class="text-xs text-white/60 bg-white/5 border-b border-white/10">
                            <tr>
                                <th class="px-4 py-3">NIM</th>
                                <th class="px-4 py-3">Nama Mahasiswa</th>
                                <th class="px-4 py-3">Nama Instansi / Perusahaan</th>
                                <th class="px-4 py-3">Lokasi Instansi</th>
                                <th class="px-4 py-3">Tanggal Mulai</th>
                                <th class="px-4 py-3">Tanggal Selesai</th>
                                <th class="px-4 py-3">Tanggal Mengajukan</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="mahasiswas.length === 0">
                                <td colspan="8" class="px-4 py-8 text-center text-white/40">Belum ada mahasiswa yang terdaftar.</td>
                            </tr>
                            <tr v-for="mhs in mahasiswas" :key="mhs.id" class="border-b border-white/5 hover:bg-white/5 transition">
                                <td class="px-4 py-3">{{ mhs.id_pengguna }}</td>
                                <td class="px-4 py-3 font-medium text-white">{{ mhs.name }}</td>
                                
                                <template v-if="mhs.internship_applications && mhs.internship_applications.length > 0">
                                    <td class="px-4 py-3">{{ getCompany(mhs.internship_applications[0]) }}</td>
                                    <td class="px-4 py-3">{{ getLocation(mhs.internship_applications[0]) }}</td>
                                    <td class="px-4 py-3">{{ formatDate(mhs.internship_applications[0].period_start) }}</td>
                                    <td class="px-4 py-3">{{ formatDate(mhs.internship_applications[0].period_end) }}</td>
                                    <td class="px-4 py-3">{{ formatDate(mhs.internship_applications[0].created_at) }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <button v-if="mhs.internship_applications[0].status === 'pending'" 
                                                @click="accPengajuan(mhs.internship_applications[0].id)" 
                                                class="bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-1 rounded text-xs font-semibold shadow-lg transition">
                                            ACC
                                        </button>
                                        <span v-else-if="mhs.internship_applications[0].status === 'approved'" class="text-emerald-400 text-xs font-semibold border border-emerald-500/30 px-2 py-1 rounded bg-emerald-500/10">
                                            Telah di-ACC
                                        </span>
                                        <span v-else class="text-white/40 text-xs">-</span>
                                    </td>
                                </template>
                                
                                <template v-else>
                                    <td class="px-4 py-3 text-white/30 italic">-</td>
                                    <td class="px-4 py-3 text-white/30 italic">-</td>
                                    <td class="px-4 py-3 text-white/30 italic">-</td>
                                    <td class="px-4 py-3 text-white/30 italic">-</td>
                                    <td class="px-4 py-3 text-white/30 italic">-</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="text-yellow-500/80 text-xs bg-yellow-500/10 px-2 py-1 rounded border border-yellow-500/20">Belum Mengajukan</span>
                                    </td>
                                </template>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/Components/AppLayout.vue';

defineProps({ mahasiswas: Array });

const formatDate = (d) => d ? new Date(d).toLocaleDateString('id-ID') : '-';

const getCompany = (app) => {
    return app.company?.name || app.custom_company_name || 'Pengajuan Mandiri';
};

const getLocation = (app) => {
    return app.company?.address || app.custom_company_address || '-';
};

const accPengajuan = (id) => {
    if (confirm('Apakah Anda yakin ingin menyetujui pengajuan ini?')) {
        router.patch(route('admin.pengajuan_magang.acc', id));
    }
};

const menuItems = [
    { label:'Dashboard', routeName:'admin.dashboard', href:route('admin.dashboard'), icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>' },
    { label:'Pengguna', routeName:'admin.pengguna', href:route('admin.pengguna'), icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>' },
    { label:'Daftar Perusahaan', routeName:'admin.perusahaan', href:route('admin.perusahaan'), icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>' },
    { label:'Pengajuan Surat Permohonan Magang', routeName:'admin.pengajuan_magang', href:route('admin.pengajuan_magang'), icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' },
    { label:'Daftar Mahasiswa Magang', routeName:'admin.mahasiswa_magang', href:route('admin.mahasiswa_magang'), icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>' },
];
</script>
