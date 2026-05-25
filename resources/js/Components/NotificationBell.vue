<template>
    <div class="relative" ref="bellRef">
        <button @click="toggleDropdown" class="relative p-2 rounded-lg hover:bg-white/10 transition text-white/70 hover:text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <span v-if="$page.props.notificationCount > 0" class="absolute -top-0.5 -right-0.5 w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center pulse-dot">
                {{ $page.props.notificationCount > 9 ? '9+' : $page.props.notificationCount }}
            </span>
        </button>

        <!-- Dropdown -->
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 scale-95 -translate-y-2"
            enter-to-class="opacity-100 scale-100 translate-y-0"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100 scale-100 translate-y-0"
            leave-to-class="opacity-0 scale-95 -translate-y-2"
        >
            <div v-if="open" class="absolute right-0 mt-2 w-80 sm:w-96 glass-card border border-white/10 rounded-xl shadow-2xl overflow-hidden z-50">
                <div class="flex items-center justify-between p-4 border-b border-white/10">
                    <h3 class="font-semibold text-white">Notifikasi</h3>
                    <button v-if="notifications.length > 0" @click="markAllRead" class="text-xs text-primary-400 hover:text-primary-300 transition">
                        Tandai semua dibaca
                    </button>
                </div>
                <div class="max-h-80 overflow-y-auto">
                    <div v-if="loading" class="p-8 text-center text-white/40">
                        <div class="w-6 h-6 border-2 border-primary-400/30 border-t-primary-400 rounded-full animate-spin mx-auto mb-2"></div>
                        <span class="text-sm">Memuat...</span>
                    </div>
                    <div v-else-if="notifications.length === 0" class="p-8 text-center text-white/40">
                        <svg class="w-10 h-10 mx-auto mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p class="text-sm">Tidak ada notifikasi</p>
                    </div>
                    <div v-else>
                        <button
                            v-for="notif in notifications"
                            :key="notif.id"
                            @click="markRead(notif)"
                            :class="[
                                'w-full text-left p-4 border-b border-white/5 hover:bg-white/5 transition',
                                !notif.read_at ? 'bg-primary-500/5' : ''
                            ]"
                        >
                            <div class="flex gap-3">
                                <div :class="['w-2 h-2 rounded-full mt-2 shrink-0', !notif.read_at ? 'bg-primary-400' : 'bg-white/20']"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-white/80 leading-relaxed">{{ notif.data?.message || 'Notifikasi baru' }}</p>
                                    <p class="text-xs text-white/40 mt-1">{{ formatTime(notif.created_at) }}</p>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import axios from 'axios';

const open = ref(false);
const loading = ref(false);
const notifications = ref([]);
const bellRef = ref(null);

const toggleDropdown = async () => {
    open.value = !open.value;
    if (open.value) {
        await fetchNotifications();
    }
};

const fetchNotifications = async () => {
    loading.value = true;
    try {
        const res = await axios.get(route('notifications.index'));
        notifications.value = res.data;
    } catch (e) {
        console.error('Failed to fetch notifications', e);
    } finally {
        loading.value = false;
    }
};

const markRead = async (notif) => {
    if (!notif.read_at) {
        try {
            await axios.patch(route('notifications.read', notif.id));
            notif.read_at = new Date().toISOString();
            router.reload({ only: ['notificationCount'] });
        } catch (e) {
            console.error('Failed to mark notification as read', e);
        }
    }
};

const markAllRead = async () => {
    try {
        await axios.patch(route('notifications.readAll'));
        notifications.value.forEach(n => n.read_at = new Date().toISOString());
        router.reload({ only: ['notificationCount'] });
    } catch (e) {
        console.error('Failed to mark all as read', e);
    }
};

const formatTime = (dateStr) => {
    const date = new Date(dateStr);
    const now = new Date();
    const diff = Math.floor((now - date) / 1000);
    if (diff < 60) return 'Baru saja';
    if (diff < 3600) return `${Math.floor(diff / 60)} menit lalu`;
    if (diff < 86400) return `${Math.floor(diff / 3600)} jam lalu`;
    if (diff < 604800) return `${Math.floor(diff / 86400)} hari lalu`;
    return date.toLocaleDateString('id-ID');
};

const handleClickOutside = (e) => {
    if (bellRef.value && !bellRef.value.contains(e.target)) {
        open.value = false;
    }
};

onMounted(() => document.addEventListener('click', handleClickOutside));
onUnmounted(() => document.removeEventListener('click', handleClickOutside));
</script>
