<template>
    <Head title="Profil Saya" />
    <AppLayout title="Profil Saya" :menu-items="menuItems">
        <div class="space-y-6 animate-fade-in-up max-w-3xl mx-auto">
            <div v-if="$page.props.flash?.success" class="p-4 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-sm">
                {{ $page.props.flash.success }}
            </div>
            
            <div class="glass-card p-6">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Informasi Profil
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm text-white/50 mb-1">Nama Lengkap</label>
                        <p class="text-white font-medium text-lg">{{ user.name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm text-white/50 mb-1">ID Pengguna (NIM/NIDN/NIP)</label>
                        <p class="text-white font-medium text-lg">{{ user.id_pengguna }}</p>
                    </div>
                    <div>
                        <label class="block text-sm text-white/50 mb-1">Role / Peran</label>
                        <p class="text-white font-medium text-lg capitalize"><span class="badge badge-info">{{ user.role }}</span></p>
                    </div>
                    <div v-if="user.jurusan">
                        <label class="block text-sm text-white/50 mb-1">Jurusan</label>
                        <p class="text-white font-medium text-lg">{{ user.jurusan }}</p>
                    </div>
                    <div v-if="user.prodi">
                        <label class="block text-sm text-white/50 mb-1">Program Studi</label>
                        <p class="text-white font-medium text-lg">{{ user.prodi }}</p>
                    </div>
                </div>
            </div>

            <div class="glass-card p-6">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Ganti Password
                </h3>
                
                <form @submit.prevent="submitPassword" class="space-y-4 max-w-md">
                    <div>
                        <label class="block text-sm font-medium text-white/70 mb-1.5">Password Lama</label>
                        <input v-model="passForm.current_password" type="password" class="input-field" required />
                        <p v-if="passForm.errors.current_password" class="text-red-400 text-xs mt-1">{{ passForm.errors.current_password }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-white/70 mb-1.5">Password Baru</label>
                        <input v-model="passForm.password" type="password" class="input-field" required minlength="8" />
                        <p v-if="passForm.errors.password" class="text-red-400 text-xs mt-1">{{ passForm.errors.password }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-white/70 mb-1.5">Konfirmasi Password Baru</label>
                        <input v-model="passForm.password_confirmation" type="password" class="input-field" required minlength="8" />
                    </div>
                    
                    <div class="pt-2">
                        <button type="submit" :disabled="passForm.processing" class="btn-primary flex items-center justify-center gap-2">
                            <div v-if="passForm.processing" class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                            <span>{{ passForm.processing ? 'Menyimpan...' : 'Simpan Password' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Head, useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Components/AppLayout.vue';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);

const passForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: ''
});

const submitPassword = () => {
    passForm.put(route('profile.password'), {
        preserveScroll: true,
        onSuccess: () => passForm.reset(),
    });
};

// Define menu items dynamically based on role so AppLayout can render the correct sidebar
const menuItems = computed(() => {
    const role = user.value.role;
    if (role === 'admin') {
        return [
            { label:'Dashboard', routeName:'admin.dashboard', href:route('admin.dashboard') },
            { label:'Pengguna', routeName:'admin.pengguna', href:route('admin.pengguna') },
            { label:'Perusahaan', routeName:'admin.perusahaan', href:route('admin.perusahaan') },
            { label:'Verifikasi', routeName:'admin.verifikasi', href:route('admin.verifikasi') },
            { label:'Profil Saya', routeName:'profile', href:route('profile') },
        ];
    } else if (role === 'mahasiswa') {
        return [
            { label:'Dashboard', routeName:'mahasiswa.dashboard', href:route('mahasiswa.dashboard') },
            { label:'Daftar Magang', routeName:'mahasiswa.magang', href:route('mahasiswa.magang') },
            { label:'Laporan', routeName:'mahasiswa.laporan', href:route('mahasiswa.laporan') },
            { label:'Profil Saya', routeName:'profile', href:route('profile') },
        ];
    } else if (role === 'kops') {
        return [
            { label:'Dashboard', routeName:'kops.dashboard', href:route('kops.dashboard') },
            { label:'Mahasiswa', routeName:'kops.mahasiswa', href:route('kops.mahasiswa') },
            { label:'Dosen', routeName:'kops.dosen', href:route('kops.dosen') },
            { label:'Verifikasi', routeName:'kops.verifikasi', href:route('kops.verifikasi') },
            { label:'Profil Saya', routeName:'profile', href:route('profile') },
        ];
    } else if (role === 'dosen') {
        return [
            { label:'Dashboard', routeName:'dosen.dashboard', href:route('dosen.dashboard') },
            { label:'Mahasiswa', routeName:'dosen.mahasiswa', href:route('dosen.mahasiswa') },
            { label:'Profil Saya', routeName:'profile', href:route('profile') },
        ];
    }
    return [{ label:'Profil Saya', routeName:'profile', href:route('profile') }];
});
</script>
