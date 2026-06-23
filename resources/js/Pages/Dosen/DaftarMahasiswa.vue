<template>
    <Head title="Laporan Magang Mahasiswa" />
    <AppLayout title="Laporan Magang" :menu-items="menuItems">
        <div class="space-y-4 animate-fade-in-up">
            <div class="mb-6 relative">
                <input v-model="searchQuery" type="text" placeholder="Cari berdasarkan nama atau NIM..." class="form-input pl-10 w-full rounded-full placeholder-gray-500 focus:ring-primary-500 focus:border-primary-500" style="background-color: white !important; color: black !important;" />
                <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <div v-if="filteredApplications.length===0" class="glass-card p-12 text-center text-white/40">Data mahasiswa tidak ditemukan</div>
            <a v-for="app in filteredApplications" :key="app.id" :href="route('dosen.laporan.detail',app.user.id)" @click.prevent="$inertia.visit(route('dosen.laporan.detail',app.user.id))"
               class="glass-card p-5 flex items-center gap-4 hover:bg-white/5 hover:border-primary-500/30 transition block">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center text-white font-bold">{{ app.user.name.charAt(0) }}</div>
                <div class="flex-1">
                    <h4 class="font-semibold text-white">{{ app.user.name }}</h4>
                    <p class="text-sm text-white/50">{{ app.company?.name || app.custom_company_name }}</p>
                    <p class="text-xs text-white/30">{{ formatDate(app.period_start) }} - {{ formatDate(app.period_end) }}</p>
                </div>
                <svg class="w-5 h-5 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </AppLayout>
</template>
<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Components/AppLayout.vue';
const props = defineProps({ applications: Array });

const searchQuery = ref('');
const filteredApplications = computed(() => {
    if (!searchQuery.value) return props.applications;
    const q = searchQuery.value.toLowerCase();
    return props.applications.filter(app => 
        app.user.name.toLowerCase().includes(q) || 
        app.user.id_pengguna.toLowerCase().includes(q)
    );
});
const formatDate = (d) => d ? new Date(d).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : '-';
const menuItems = [
    {label:'Dashboard',routeName:'dosen.dashboard',href:route('dosen.dashboard'),icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>'},
    {label:'Laporan Magang',routeName:'dosen.laporan',href:route('dosen.laporan'),icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>'},
];
</script>
