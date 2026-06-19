<script setup lang="ts">
defineProps<{
    modelValue: string | number | null;
    options: Array<{ value: string | number; label: string }>;
    label?: string;
    error?: string;
    required?: boolean;
    placeholder?: string;
}>();
defineEmits<{ (e: 'update:modelValue', value: string): void }>();
</script>

<template>
    <div>
        <label v-if="label" class="mb-1.5 block text-sm font-medium text-slate-700">
            {{ label }} <span v-if="required" class="text-rose-500">*</span>
        </label>
        <select
            :value="modelValue ?? ''"
            @change="$emit('update:modelValue', ($event.target as HTMLSelectElement).value)"
            class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200"
        >
            <option v-if="placeholder" value="">{{ placeholder }}</option>
            <option v-for="opt in options" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
        </select>
        <p v-if="error" class="mt-1 text-xs text-rose-600">{{ error }}</p>
    </div>
</template>
