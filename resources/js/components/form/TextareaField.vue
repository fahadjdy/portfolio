<script setup lang="ts">
defineProps<{
    modelValue: string | null;
    label?: string;
    rows?: number;
    error?: string;
    required?: boolean;
    placeholder?: string;
    hint?: string;
}>();
defineEmits<{ (e: 'update:modelValue', value: string): void }>();
</script>

<template>
    <div>
        <label v-if="label" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">
            {{ label }} <span v-if="required" class="text-rose-500">*</span>
        </label>
        <textarea
            :rows="rows || 4"
            :value="modelValue ?? ''"
            :placeholder="placeholder"
            @input="$emit('update:modelValue', ($event.target as HTMLTextAreaElement).value)"
            class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:ring-brand-900"
        ></textarea>
        <p v-if="hint" class="mt-1 text-xs text-slate-400">{{ hint }}</p>
        <p v-if="error" class="mt-1 text-xs text-rose-600">{{ error }}</p>
    </div>
</template>
