<template>
    <div class="min-h-screen flex">
        <!-- Mobile Sidebar Overlay -->
        <div v-if="sidebarOpen" class="fixed inset-0 z-40 lg:hidden" @click="sidebarOpen = false">
            <div class="modal-overlay absolute inset-0"></div>
        </div>

        <!-- Sidebar -->
        <Sidebar
            :menu-items="menuItems"
            :is-open="sidebarOpen"
            @close="sidebarOpen = false"
            @logout="showLogout = true"
        />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-h-screen lg:ml-64">
            <!-- Header -->
            <header class="sticky top-0 z-30 glass-card border-0 border-b border-white/10 rounded-none px-4 sm:px-6 py-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-white/70 hover:text-white p-2 rounded-lg hover:bg-white/10 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        <h1 class="text-lg font-semibold text-white">{{ title }}</h1>
                    </div>
                    <div class="flex items-center gap-3">
                        <NotificationBell />
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center text-white text-sm font-bold">
                                {{ userInitial }}
                            </div>
                            <span class="hidden sm:block text-sm text-white/80">{{ $page.props.auth.user?.name }}</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Flash Messages -->
            <div v-if="$page.props.flash?.success" class="mx-4 sm:mx-6 mt-4">
                <div class="glass-card border-emerald-500/30 bg-emerald-500/10 p-4 rounded-xl flex items-center gap-3 animate-fade-in-up">
                    <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span class="text-emerald-200 text-sm">{{ $page.props.flash.success }}</span>
                </div>
            </div>
            <div v-if="$page.props.flash?.error" class="mx-4 sm:mx-6 mt-4">
                <div class="glass-card border-red-500/30 bg-red-500/10 p-4 rounded-xl flex items-center gap-3 animate-fade-in-up">
                    <svg class="w-5 h-5 text-red-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    <span class="text-red-200 text-sm">{{ $page.props.flash.error }}</span>
                </div>
            </div>

            <!-- Page Content -->
            <main class="flex-1 p-4 sm:p-6">
                <slot />
            </main>
        </div>

        <!-- Logout Modal -->
        <Modal :show="showLogout" @close="showLogout = false">
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-red-500/10 flex items-center justify-center">
                    <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Konfirmasi Logout</h3>
                <p class="text-white/60 mb-6">Apakah Anda yakin ingin keluar dari sistem?</p>
                <div class="flex gap-3 justify-center">
                    <button @click="showLogout = false" class="btn-ghost">Batal</button>
                    <button @click="doLogout" class="btn-danger">Ya, Logout</button>
                </div>
            </div>
        </Modal>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import Sidebar from './Sidebar.vue';
import NotificationBell from './NotificationBell.vue';
import Modal from './Modal.vue';

const props = defineProps({
    title: { type: String, default: 'Dashboard' },
    menuItems: { type: Array, default: () => [] },
});

const page = usePage();
const sidebarOpen = ref(false);
const showLogout = ref(false);

const userInitial = computed(() => {
    return page.props.auth.user?.name?.charAt(0)?.toUpperCase() || '?';
});

const doLogout = () => {
    router.post(route('logout'));
};

// Expose for sidebar
defineExpose({ showLogout });
</script>
