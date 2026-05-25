<template>
    <Head title="Daftar Mahasiswa" />
    <AppLayout title="Daftar Mahasiswa" :menu-items="menuItems">
        <DataTable :columns="cols" :data="mahasiswa" searchable :search-keys="['name','email','jurusan']" class="animate-fade-in-up">
            <template #cell-jurusan="{row}"><span class="text-white/60 text-xs">{{ row.jurusan || '-' }} / {{ row.prodi || '-' }}</span></template>
        </DataTable>
    </AppLayout>
</template>
<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Components/AppLayout.vue';
import DataTable from '@/Components/DataTable.vue';
defineProps({ mahasiswa: Array });
const cols = [{key:'name',label:'Nama'},{key:'email',label:'Email'},{key:'jurusan',label:'Jurusan/Prodi'}];
const menuItems = [
    {label:'Dashboard',routeName:'kops.dashboard',href:route('kops.dashboard'),icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>'},
    {label:'Daftar Mahasiswa',routeName:'kops.mahasiswa',href:route('kops.mahasiswa'),icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>'},
    {label:'Dosen Pembimbing',routeName:'kops.dosen',href:route('kops.dosen'),icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>'},
    {label:'Verifikasi Magang',routeName:'kops.verifikasi',href:route('kops.verifikasi'),icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'},
];
</script>
