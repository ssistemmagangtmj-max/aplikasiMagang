<template>
    <div class="glass-card overflow-hidden">
        <div v-if="title || $slots.actions" class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-4 border-b border-white/10">
            <h3 v-if="title" class="font-semibold text-white">{{ title }}</h3>
            <div class="flex items-center gap-2">
                <input v-if="searchable" v-model="search" type="text" placeholder="Cari..." class="input-field !py-2 !text-sm max-w-[200px]" />
                <slot name="actions" />
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="border-b border-white/10">
                    <th v-for="col in columns" :key="col.key" class="text-left px-4 py-3 text-white/50 font-medium text-xs uppercase tracking-wider">{{ col.label }}</th>
                    <th v-if="$slots.rowActions" class="px-4 py-3 text-right text-white/50 font-medium text-xs uppercase">Aksi</th>
                </tr></thead>
                <tbody>
                    <tr v-for="(row, i) in filteredData" :key="i" class="border-b border-white/5 hover:bg-white/5 transition cursor-pointer" @click="$emit('row-click', row)">
                        <td v-for="col in columns" :key="col.key" class="px-4 py-3 text-white/80">
                            <slot :name="'cell-'+col.key" :row="row" :value="row[col.key]">{{ row[col.key] || '-' }}</slot>
                        </td>
                        <td v-if="$slots.rowActions" class="px-4 py-3 text-right"><slot name="rowActions" :row="row" /></td>
                    </tr>
                    <tr v-if="filteredData.length === 0"><td :colspan="columns.length + ($slots.rowActions ? 1 : 0)" class="px-4 py-8 text-center text-white/40">Tidak ada data</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
const props = defineProps({ columns: Array, data: { type: Array, default: () => [] }, title: String, searchable: Boolean, searchKeys: { type: Array, default: () => [] } });
defineEmits(['row-click']);
const search = ref('');
const filteredData = computed(() => {
    if (!search.value || !props.searchKeys.length) return props.data;
    const q = search.value.toLowerCase();
    return props.data.filter(row => props.searchKeys.some(k => String(row[k] || '').toLowerCase().includes(q)));
});
</script>
