<template>
    <Head title="Verifikasi Magang" />
    <AppLayout title="Verifikasi Magang" :menu-items="menuItems">
        <div class="space-y-4 animate-fade-in-up">
            <p class="text-white/50 text-sm">Daftar pengajuan magang menunggu verifikasi Admin</p>
            <div v-if="applications.length===0" class="glass-card p-12 text-center text-white/40">Tidak ada pengajuan pending</div>
            <div v-for="app in applications" :key="app.id" class="glass-card p-5 hover:bg-white/5 transition">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h4 class="font-semibold text-white">{{ app.user?.name }}</h4>
                        <p class="text-sm text-white/50">{{ app.company?.name || app.custom_company_name || 'Pengajuan baru' }}</p>
                        <p class="text-xs text-white/30 mt-1">Periode: {{ formatDate(app.period_start) }} - {{ formatDate(app.period_end) }}</p>
                    </div>
                    <div class="flex gap-2">
                        <button @click="$inertia.patch(route('admin.verifikasi.approve',app.id))" class="btn-accent !py-1.5 !px-4 text-sm">Terima</button>
                        <button @click="rejectTarget=app" class="btn-danger !py-1.5 !px-4 text-sm">Tolak</button>
                    </div>
                </div>
            </div>
        </div>
        <Modal :show="!!rejectTarget" @close="rejectTarget=null">
            <h3 class="text-lg font-bold text-white mb-3">Tolak Pengajuan</h3>
            <p class="text-white/60 text-sm mb-3">Tuliskan alasan penolakan untuk {{ rejectTarget?.user?.name }}:</p>
            <textarea v-model="rejectReason" class="input-field min-h-[80px] mb-4" placeholder="Alasan penolakan..."></textarea>
            <div class="flex gap-3 justify-end">
                <button @click="rejectTarget=null" class="btn-ghost">Batal</button>
                <button @click="doReject" class="btn-danger" :disabled="!rejectReason">Tolak</button>
            </div>
        </Modal>
    </AppLayout>
</template>
<script setup>
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/Components/AppLayout.vue';
import Modal from '@/Components/Modal.vue';
defineProps({ applications: Array });
const rejectTarget = ref(null); const rejectReason = ref('');
const doReject = () => { router.patch(route('admin.verifikasi.reject',rejectTarget.value.id),{rejection_reason:rejectReason.value}); rejectTarget.value=null; rejectReason.value=''; };
const formatDate = d => d ? new Date(d).toLocaleDateString('id-ID') : '-';
const menuItems = [
    {label:'Dashboard',routeName:'admin.dashboard',href:route('admin.dashboard'),icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>'},
    {label:'Pengguna',routeName:'admin.pengguna',href:route('admin.pengguna'),icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>'},
    {label:'Perusahaan',routeName:'admin.perusahaan',href:route('admin.perusahaan'),icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>'},
    {label:'Verifikasi',routeName:'admin.verifikasi',href:route('admin.verifikasi'),icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'},
];
</script>
