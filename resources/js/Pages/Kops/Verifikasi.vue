<template>
    <Head title="Verifikasi Magang" />
    <AppLayout title="Verifikasi Magang" :menu-items="menuItems">
        <div class="space-y-4 animate-fade-in-up">
            <p class="text-white/50 text-sm">Pengajuan yang sudah disetujui Admin</p>
            <div v-if="applications.length===0" class="glass-card p-12 text-center text-white/40">Tidak ada pengajuan menunggu verifikasi</div>
            <div v-for="app in applications" :key="app.id" class="glass-card p-5 hover:bg-white/5 transition">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h4 class="font-semibold text-white">{{ app.user?.name }}</h4>
                        <p class="text-sm text-white/50">{{ app.company?.name || app.custom_company_name }}</p>
                        <p class="text-xs text-white/30 mt-1">{{ formatDate(app.period_start) }} - {{ formatDate(app.period_end) }}</p>
                    </div>
                    <div class="flex gap-2">
                        <button @click="approveTarget=app" class="btn-accent !py-1.5 !px-4 text-sm">Terima</button>
                        <button @click="rejectTarget=app" class="btn-danger !py-1.5 !px-4 text-sm">Tolak</button>
                    </div>
                </div>
            </div>
        </div>
        <Modal :show="!!approveTarget" @close="approveTarget=null">
            <h3 class="text-lg font-bold text-white mb-3">Pilih Dosen Pembimbing</h3>
            <p class="text-white/60 text-sm mb-4">Untuk mahasiswa: {{ approveTarget?.user?.name }}</p>
            <select v-model="selectedDosen" class="input-field mb-4">
                <option value="">-- Pilih Dosen --</option>
                <option v-for="d in dosenList" :key="d.id" :value="d.id">{{ d.name }} ({{ d.nomor_induk }})</option>
            </select>
            <div class="flex gap-3 justify-end">
                <button @click="approveTarget=null" class="btn-ghost">Batal</button>
                <button @click="doApprove" class="btn-accent" :disabled="!selectedDosen">Setujui</button>
            </div>
        </Modal>
        <Modal :show="!!rejectTarget" @close="rejectTarget=null">
            <h3 class="text-lg font-bold text-white mb-3">Tolak Pengajuan</h3>
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
defineProps({ applications: Array, dosenList: Array });
const approveTarget=ref(null); const rejectTarget=ref(null);
const selectedDosen=ref(''); const rejectReason=ref('');
const doApprove=()=>{router.patch(route('kops.verifikasi.approve',approveTarget.value.id),{supervisor_id:selectedDosen.value});approveTarget.value=null;selectedDosen.value='';};
const doReject=()=>{router.patch(route('kops.verifikasi.reject',rejectTarget.value.id),{rejection_reason:rejectReason.value});rejectTarget.value=null;rejectReason.value='';};
const formatDate=d=>d?new Date(d).toLocaleDateString('id-ID'):'-';
const menuItems = [
    {label:'Dashboard',routeName:'kops.dashboard',href:route('kops.dashboard'),icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>'},
    {label:'Daftar Mahasiswa',routeName:'kops.mahasiswa',href:route('kops.mahasiswa'),icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>'},
    {label:'Dosen Pembimbing',routeName:'kops.dosen',href:route('kops.dosen'),icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>'},
    {label:'Verifikasi Magang',routeName:'kops.verifikasi',href:route('kops.verifikasi'),icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'},
];
</script>
