<template>
    <Head title="Daftar Pengajuan Magang" />
    <AppLayout title="Daftar Pengajuan Magang" :menu-items="menuItems">
        <div class="space-y-4 animate-fade-in-up">
            <p class="text-white/50 text-sm">Daftar semua pengajuan magang dari mahasiswa</p>
            <div v-if="applications.length===0" class="glass-card p-12 text-center text-white/40">Tidak ada pengajuan magang saat ini.</div>
            <div v-for="app in applications" :key="app.id" class="glass-card p-5 hover:bg-white/5 transition">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h4 class="font-semibold text-white">{{ app.user?.name }}</h4>
                        <p class="text-sm text-white/50">{{ app.company?.name || app.custom_company_name || 'Pengajuan Mandiri' }}</p>
                        <p class="text-xs text-white/30 mt-1">Periode: {{ formatDate(app.period_start) }} - {{ formatDate(app.period_end) }}</p>
                    </div>
                    <div class="flex flex-wrap gap-2 items-center">
                        <span :class="['text-xs px-2 py-1 rounded border', statusClass(app.status)]">{{ statusLabel(app.status) }}</span>
                        <Link :href="route('admin.pengajuan_magang.detail', app.id)" class="btn-primary btn-sm">Lihat Detail</Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Components/AppLayout.vue';

defineProps({ applications: Array });

const formatDate = d => d ? new Date(d).toLocaleDateString('id-ID') : '-';

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

const menuItems = [
    { label:'Dashboard', routeName:'admin.dashboard', href:route('admin.dashboard'), icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>' },
    { label:'Pengguna', routeName:'admin.pengguna', href:route('admin.pengguna'), icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>' },
    { label:'Daftar Perusahaan', routeName:'admin.perusahaan', href:route('admin.perusahaan'), icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>' },
    { label:'Daftar Mahasiswa Magang', routeName:'admin.mahasiswa_magang', href:route('admin.mahasiswa_magang'), icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>' },
    { label:'Daftar Pengajuan Magang', routeName:'admin.pengajuan_magang', href:route('admin.pengajuan_magang'), icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' },
];
</script>
