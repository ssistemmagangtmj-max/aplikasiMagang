<template>
    <div>
        <label
            @dragover.prevent="isDragging = true"
            @dragleave.prevent="isDragging = false"
            @drop.prevent="handleDrop"
            :class="[
                'flex flex-col items-center justify-center w-full border-2 border-dashed rounded-xl p-6 cursor-pointer transition-all',
                isDragging ? 'border-primary-400 bg-primary-500/10' : 'border-white/15 hover:border-primary-400/50 hover:bg-white/5',
                hasError ? 'border-red-400/50' : ''
            ]"
        >
            <input type="file" :accept="accept" class="hidden" ref="fileInput" @change="handleFileSelect" />
            <div v-if="!selectedFile" class="text-center">
                <svg class="w-10 h-10 mx-auto mb-3 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                <p class="text-sm text-white/60 mb-1"><span class="text-primary-400 font-medium">Klik untuk upload</span> atau drag & drop</p>
                <p class="text-xs text-white/40">{{ hint }}</p>
            </div>
            <div v-else class="flex items-center gap-3 w-full">
                <div class="w-10 h-10 rounded-lg bg-primary-500/20 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-white truncate">{{ selectedFile.name }}</p>
                    <p class="text-xs text-white/40">{{ formatSize(selectedFile.size) }}</p>
                </div>
                <button type="button" @click.prevent="removeFile" class="text-red-400 hover:text-red-300 p-1">✕</button>
            </div>
        </label>
        <p v-if="hasError" class="text-red-400 text-xs mt-1">{{ errorMessage }}</p>
    </div>
</template>

<script setup>
import { ref } from 'vue';
const props = defineProps({ accept: { type: String, default: '*' }, hint: { type: String, default: 'Maks. 5MB' }, hasError: Boolean, errorMessage: String });
const emit = defineEmits(['file-selected']);
const fileInput = ref(null), selectedFile = ref(null), isDragging = ref(false);
const handleFileSelect = (e) => { const f = e.target.files[0]; if (f) { selectedFile.value = f; emit('file-selected', f); } };
const handleDrop = (e) => { isDragging.value = false; const f = e.dataTransfer.files[0]; if (f) { selectedFile.value = f; emit('file-selected', f); } };
const removeFile = () => { selectedFile.value = null; if (fileInput.value) fileInput.value.value = ''; emit('file-selected', null); };
const formatSize = (b) => b < 1024 ? b+' B' : b < 1048576 ? (b/1024).toFixed(1)+' KB' : (b/1048576).toFixed(1)+' MB';
</script>
