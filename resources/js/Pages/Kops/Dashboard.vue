<template>
    <Head title="Dashboard Koordinator" />
    <AppLayout title="Dashboard" :menu-items="menuItems">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 animate-fade-in-up">
            <div class="glass-card p-5"><div class="flex items-center gap-4"><div class="w-12 h-12 rounded-xl bg-primary-500/20 flex items-center justify-center"><svg class="w-6 h-6 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg></div><div><p class="text-2xl font-bold text-white">{{ stats.totalMahasiswa }}</p><p class="text-xs text-white/50">Mahasiswa</p></div></div></div>
            <div class="glass-card p-5"><div class="flex items-center gap-4"><div class="w-12 h-12 rounded-xl bg-accent-500/20 flex items-center justify-center"><svg class="w-6 h-6 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div><div><p class="text-2xl font-bold text-white">{{ stats.totalDosen }}</p><p class="text-xs text-white/50">Dosen Pembimbing</p></div></div></div>
            <div class="glass-card p-5"><div class="flex items-center gap-4"><div class="w-12 h-12 rounded-xl bg-yellow-500/20 flex items-center justify-center"><svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></div><div><p class="text-2xl font-bold text-white">{{ stats.pendingApps }}</p><p class="text-xs text-white/50">Menunggu Verifikasi</p></div></div></div>
        </div>
    </AppLayout>
</template>
<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Components/AppLayout.vue';
defineProps({ stats: Object });
const menuItems = [
    {label:'Dashboard',routeName:'kops.dashboard',href:route('kops.dashboard'),icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>'},
    {label:'Daftar Mahasiswa',routeName:'kops.mahasiswa',href:route('kops.mahasiswa'),icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>'},
    {label:'Dosen Pembimbing',routeName:'kops.dosen',href:route('kops.dosen'),icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>'},
    {label:'Verifikasi Magang',routeName:'kops.verifikasi',href:route('kops.verifikasi'),icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'},
];
</script>
