<script setup lang="ts">
defineProps<{
    modelValue: string | number | null;
    label?: string;
    type?: string;
    error?: string;
    required?: boolean;
    placeholder?: string;
    hint?: string;
}>();
defineEmits<{ (e: 'update:modelValue', value: string): void }>();
</script>

<template>
    <div>
        <label v-if="label" class="mb-1.5 block text-sm font-medium text-slate-700">
            {{ label }} <span v-if="required" class="text-rose-500">*</span>
        </label>
        <input
            :type="type || 'text'"
            :value="modelValue ?? ''"
            :placeholder="placeholder"
            @input="$emit('update:modelValue', ($event.target as HTMLInputElement).value)"
            class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200"
        />
        <p v-if="hint" class="mt-1 text-xs text-slate-400">{{ hint }}</p>
        <p v-if="error" class="mt-1 text-xs text-rose-600">{{ error }}</p>
    </div>
</template>
