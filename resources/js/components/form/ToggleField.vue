<script setup lang="ts">
defineProps<{ modelValue: boolean; label?: string; hint?: string }>();
defineEmits<{ (e: 'update:modelValue', value: boolean): void }>();
</script>

<template>
    <label class="flex cursor-pointer items-center justify-between gap-4">
        <span>
            <span v-if="label" class="block text-sm font-medium text-slate-700">{{ label }}</span>
            <span v-if="hint" class="block text-xs text-slate-400">{{ hint }}</span>
        </span>
        <button
            type="button"
            role="switch"
            :aria-checked="modelValue"
            @click="$emit('update:modelValue', !modelValue)"
            :class="modelValue ? 'bg-brand-600' : 'bg-slate-300'"
            class="relative inline-flex h-6 w-11 shrink-0 items-center rounded-full transition"
        >
            <span :class="modelValue ? 'translate-x-6' : 'translate-x-1'" class="inline-block h-4 w-4 rounded-full bg-white transition"></span>
        </button>
    </label>
</template>
