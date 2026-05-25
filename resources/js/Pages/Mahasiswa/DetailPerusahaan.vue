<template>
    <Head :title="company.name" />
    <AppLayout :title="company.name" :menu-items="menuItems">
        <div class="max-w-2xl mx-auto animate-fade-in-up">
            <a :href="route('mahasiswa.magang')" @click.prevent="$inertia.visit(route('mahasiswa.magang'))" class="text-primary-400 hover:text-primary-300 text-sm mb-4 inline-flex items-center gap-1">← Kembali</a>
            <div class="glass-card p-6 space-y-4">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-primary-500/20 to-accent-500/20 flex items-center justify-center text-2xl font-bold text-primary-300">{{ company.name.charAt(0) }}</div>
                    <div><h2 class="text-xl font-bold text-white">{{ company.name }}</h2><span class="badge badge-info mt-1">Tersedia</span></div>
                </div>
                <div class="space-y-3 pt-4 border-t border-white/10">
                    <div v-if="company.description"><p class="text-sm text-white/50">Deskripsi</p><p class="text-sm text-white/80">{{ company.description }}</p></div>
                    <div v-if="company.address"><p class="text-sm text-white/50">Alamat</p><p class="text-sm text-white/80">{{ company.address }}</p></div>
                    <div class="grid grid-cols-2 gap-3">
                        <div v-if="company.operational_hours"><p class="text-sm text-white/50">Jam Operasional</p><p class="text-sm text-white/80">{{ company.operational_hours }}</p></div>
                        <div v-if="company.phone"><p class="text-sm text-white/50">Telepon</p><p class="text-sm text-white/80">{{ company.phone }}</p></div>
                        <div v-if="company.email"><p class="text-sm text-white/50">Email</p><p class="text-sm text-white/80">{{ company.email }}</p></div>
                    </div>
                </div>
                <button @click="showConfirm = true" class="btn-primary w-full mt-4">Daftar Magang</button>
                <p v-if="$page.props.errors?.apply" class="text-red-400 text-sm text-center">{{ $page.props.errors.apply }}</p>
            </div>
        </div>
        <Modal :show="showConfirm" @close="showConfirm = false">
            <h3 class="text-lg font-bold text-white mb-2">Konfirmasi Pendaftaran</h3>
            <p class="text-white/60 text-sm mb-4">Yakin ingin mendaftar di <strong class="text-white">{{ company.name }}</strong>? Masukkan password untuk konfirmasi.</p>
            <form @submit.prevent="submitApply">
                <input v-model="form.password" type="password" class="input-field mb-3" placeholder="Password Anda" required />
                <p v-if="form.errors.password" class="text-red-400 text-xs mb-3">{{ form.errors.password }}</p>
                <div class="flex gap-3 justify-end">
                    <button type="button" @click="showConfirm = false" class="btn-ghost">Batal</button>
                    <button type="submit" :disabled="form.processing" class="btn-primary">{{ form.processing ? 'Mengirim...' : 'Konfirmasi' }}</button>
                </div>
            </form>
        </Modal>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Components/AppLayout.vue';
import Modal from '@/Components/Modal.vue';
const props = defineProps({ company: Object });
const showConfirm = ref(false);
const form = useForm({ company_id: props.company.id, password: '' });
const submitApply = () => form.post(route('mahasiswa.magang.apply'), { onSuccess: () => showConfirm.value = false });
const menuItems = [
    { label: 'Dashboard', routeName: 'mahasiswa.dashboard', href: route('mahasiswa.dashboard'), icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>' },
    { label: 'Daftar Magang', routeName: 'mahasiswa.magang', href: route('mahasiswa.magang'), icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.193 23.193 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>' },
    { label: 'Laporan', routeName: 'mahasiswa.laporan', href: route('mahasiswa.laporan'), icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' },
];
</script>
