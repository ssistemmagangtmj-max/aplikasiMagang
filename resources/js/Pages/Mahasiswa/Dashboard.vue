<template>
    <Head title="Dashboard Mahasiswa" />
    <AppLayout title="Dashboard" :menu-items="menuItems">
        <div class="space-y-6 animate-fade-in-up">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="glass-card p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-primary-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.193 23.193 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-white/50 text-sm">Status Magang</p>
                            <p class="text-white font-semibold">{{ activeApplication ? statusLabel(activeApplication.status) : 'Belum mendaftar' }}</p>
                        </div>
                    </div>
                    <div v-if="activeApplication" class="mt-4 pt-4 border-t border-white/10 space-y-2">
                        <p class="text-sm text-white/60">Perusahaan: <span class="text-white">{{ activeApplication.company?.name || activeApplication.custom_company_name || '-' }}</span></p>
                    </div>
                </div>
                <div class="glass-card p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-accent-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-white/50 text-sm">Laporan</p>
                            <p class="text-white font-semibold">{{ latestReport ? reportStatusLabel(latestReport.status) : 'Belum diupload' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Box Info Status Magang -->
            <div class="glass-card p-6 border-l-4" :class="infoBoxClass">
                <div class="flex items-start gap-4">
                    <div class="mt-1">
                        <svg v-if="!activeApplication" class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <svg v-else-if="activeApplication.status === 'pending'" class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <svg v-else-if="activeApplication.status === 'approved'" class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <svg v-else-if="activeApplication.status === 'rejected'" class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-white mb-1">Informasi Magang</h3>
                        <p class="text-white/80 font-medium text-lg">{{ infoMessage }}</p>
                        
                        <div v-if="!activeApplication" class="mt-4">
                            <Link :href="route('mahasiswa.magang')" class="btn-primary">Daftar Magang Sekarang</Link>
                        </div>
                        
                        <div v-if="activeApplication && activeApplication.status === 'rejected'" class="mt-3 p-3 bg-red-500/10 border border-red-500/20 text-red-400 rounded-lg text-sm">
                            <strong>Alasan Penolakan:</strong> {{ activeApplication.rejection_reason }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppLayout from '@/Components/AppLayout.vue';

const props = defineProps({ activeApplication: Object, latestReport: Object });

const infoMessage = computed(() => {
    if (!props.activeApplication) {
        return "Silahkan daftar magang";
    }
    if (props.activeApplication.status === 'pending') {
        return "Tunggu respon dari admin";
    }
    if (props.activeApplication.status === 'approved') {
        return "Surat Terbit, Proses surat sudah jadi silahkan ke akademik mengisi pengajuan akademik";
    }
    if (props.activeApplication.status === 'rejected') {
        return "Pengajuan magang ditolak";
    }
    return "Status pengajuan: " + props.activeApplication.status;
});

const infoBoxClass = computed(() => {
    if (!props.activeApplication) return 'border-blue-500 bg-blue-500/5';
    if (props.activeApplication.status === 'pending') return 'border-yellow-500 bg-yellow-500/5';
    if (props.activeApplication.status === 'approved') return 'border-emerald-500 bg-emerald-500/5';
    if (props.activeApplication.status === 'rejected') return 'border-red-500 bg-red-500/5';
    return 'border-white/20';
});

const menuItems = [
    { label: 'Dashboard', routeName: 'mahasiswa.dashboard', href: route('mahasiswa.dashboard'), icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>' },
    { label: 'Daftar Perusahaan', routeName: 'mahasiswa.magang', href: route('mahasiswa.magang'), icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>' },
    { label: 'Laporan Magang', routeName: 'mahasiswa.laporan', href: route('mahasiswa.laporan'), icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' },
];

const statusLabel = (s) => ({ pending: 'Menunggu ACC', approved: 'Disetujui', rejected: 'Ditolak' }[s] || s);
const reportStatusLabel = (s) => ({ uploaded: 'Terkirim', reviewed: 'Sedang Direview', approved: 'Disetujui', revision: 'Perlu Revisi' }[s] || s);
</script>
