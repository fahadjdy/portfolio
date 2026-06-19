<script setup lang="ts">
import { ref } from 'vue';

const props = defineProps<{ modelValue: File | null; label?: string; current?: string | null; error?: string; hint?: string }>();
const emit = defineEmits<{ (e: 'update:modelValue', value: File | null): void }>();

const preview = ref<string | null>(null);

function onChange(e: Event) {
    const file = (e.target as HTMLInputElement).files?.[0] ?? null;
    emit('update:modelValue', file);
    preview.value = file ? URL.createObjectURL(file) : null;
}
function clear() {
    emit('update:modelValue', null);
    preview.value = null;
}
</script>

<template>
    <div>
        <label v-if="label" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ label }}</label>
        <div class="flex items-center gap-4">
            <div class="grid h-16 w-16 shrink-0 place-items-center overflow-hidden rounded-lg border border-slate-200 bg-slate-50 dark:border-slate-700 dark:bg-slate-900">
                <img v-if="preview" :src="preview" class="h-full w-full object-cover" alt="" />
                <img v-else-if="current" :src="current" class="h-full w-full object-cover" alt="" />
                <span v-else class="text-xs text-slate-400">None</span>
            </div>
            <div>
                <input type="file" accept="image/*" @change="onChange" class="block text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-brand-50 file:px-3 file:py-2 file:text-sm file:font-medium file:text-brand-700 hover:file:bg-brand-100 dark:text-slate-300 dark:file:bg-brand-600/20 dark:file:text-brand-300" />
                <button v-if="preview || (modelValue !== null)" type="button" @click="clear" class="mt-1 text-xs text-rose-600">Remove selection</button>
                <p v-if="hint" class="mt-1 text-xs text-slate-400">{{ hint }}</p>
            </div>
        </div>
        <p v-if="error" class="mt-1 text-xs text-rose-600">{{ error }}</p>
    </div>
</template>
