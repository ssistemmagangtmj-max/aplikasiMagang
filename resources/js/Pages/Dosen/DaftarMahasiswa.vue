<template>
    <Head title="Mahasiswa Bimbingan" />
    <AppLayout title="Mahasiswa Bimbingan" :menu-items="menuItems">
        <div class="space-y-4 animate-fade-in-up">
            <div v-if="applications.length===0" class="glass-card p-12 text-center text-white/40">Belum ada mahasiswa bimbingan</div>
            <a v-for="app in applications" :key="app.id" :href="route('dosen.mahasiswa.laporan',app.user.id)" @click.prevent="$inertia.visit(route('dosen.mahasiswa.laporan',app.user.id))"
               class="glass-card p-5 flex items-center gap-4 hover:bg-white/5 hover:border-primary-500/30 transition block">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center text-white font-bold">{{ app.user.name.charAt(0) }}</div>
                <div class="flex-1">
                    <h4 class="font-semibold text-white">{{ app.user.name }}</h4>
                    <p class="text-sm text-white/50">{{ app.company?.name || app.custom_company_name }}</p>
                    <p class="text-xs text-white/30">{{ app.user.jurusan }} · {{ app.user.prodi }}</p>
                </div>
                <svg class="w-5 h-5 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </AppLayout>
</template>
<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Components/AppLayout.vue';
defineProps({ applications: Array });
const menuItems = [
    {label:'Dashboard',routeName:'dosen.dashboard',href:route('dosen.dashboard'),icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>'},
    {label:'Mahasiswa Bimbingan',routeName:'dosen.mahasiswa',href:route('dosen.mahasiswa'),icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>'},
];
</script>
