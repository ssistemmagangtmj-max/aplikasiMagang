<template>
    <Head title="Dashboard Mahasiswa" />
    <AppLayout title="Dashboard" :menu-items="menuItems">
        <div class="space-y-6 animate-fade-in-up">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="glass-card p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-primary-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.193 23.193 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-white/50 text-sm">Status Magang</p>
                            <p class="text-white font-semibold">{{ activeApplication ? statusLabel(activeApplication.status) : 'Belum mendaftar' }}</p>
                        </div>
                    </div>
                    <div v-if="activeApplication" class="mt-4 pt-4 border-t border-white/10 space-y-2">
                        <p class="text-sm text-white/60">Perusahaan: <span class="text-white">{{ activeApplication.company?.name || activeApplication.custom_company_name || '-' }}</span></p>
                        <p v-if="activeApplication.supervisor" class="text-sm text-white/60">Dosen Pembimbing: <span class="text-white">{{ activeApplication.supervisor.name }}</span></p>
                    </div>
                </div>
                <div class="glass-card p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-accent-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-white/50 text-sm">Laporan</p>
                            <p class="text-white font-semibold">{{ latestReport ? reportStatusLabel(latestReport.status) : 'Belum diupload' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="glass-card p-6">
                <h3 class="font-semibold text-white mb-4">Menu Cepat</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <a :href="route('mahasiswa.magang')" @click.prevent="$inertia.visit(route('mahasiswa.magang'))" class="flex items-center gap-3 p-4 rounded-xl bg-white/5 hover:bg-primary-500/10 border border-white/10 hover:border-primary-500/30 transition">
                        <svg class="w-5 h-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.193 23.193 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span class="text-sm text-white/80">Daftar Magang</span>
                    </a>
                    <a :href="route('mahasiswa.laporan')" @click.prevent="$inertia.visit(route('mahasiswa.laporan'))" class="flex items-center gap-3 p-4 rounded-xl bg-white/5 hover:bg-accent-500/10 border border-white/10 hover:border-accent-500/30 transition">
                        <svg class="w-5 h-5 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span class="text-sm text-white/80">Laporan</span>
                    </a>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Components/AppLayout.vue';
const props = defineProps({ activeApplication: Object, latestReport: Object });
const menuItems = [
    { label: 'Dashboard', routeName: 'mahasiswa.dashboard', href: route('mahasiswa.dashboard'), icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>' },
    { label: 'Daftar Magang', routeName: 'mahasiswa.magang', href: route('mahasiswa.magang'), icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.193 23.193 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>' },
    { label: 'Laporan', routeName: 'mahasiswa.laporan', href: route('mahasiswa.laporan'), icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' },
];
const statusLabel = (s) => ({ pending: 'Menunggu Verifikasi', approved_admin: 'Disetujui Admin', approved_kops: 'Aktif Magang', rejected: 'Ditolak' }[s] || s);
const reportStatusLabel = (s) => ({ uploaded: 'Terkirim', reviewed: 'Sedang Direview', approved: 'Disetujui', revision: 'Perlu Revisi' }[s] || s);
</script>
