<script setup lang="ts">
defineProps<{
    modelValue: number[];
    options: Array<{ value: number; label: string }>;
    label?: string;
}>();
const emit = defineEmits<{ (e: 'update:modelValue', value: number[]): void }>();

function toggle(current: number[], value: number) {
    const set = new Set(current || []);
    set.has(value) ? set.delete(value) : set.add(value);
    emit('update:modelValue', [...set]);
}
</script>

<template>
    <div>
        <label v-if="label" class="mb-1.5 block text-sm font-medium text-slate-700">{{ label }}</label>
        <div class="flex flex-wrap gap-2">
            <button
                v-for="opt in options"
                :key="opt.value"
                type="button"
                @click="toggle(modelValue, opt.value)"
                :class="(modelValue || []).includes(opt.value)
                    ? 'border-brand-500 bg-brand-50 text-brand-700'
                    : 'border-slate-200 bg-white text-slate-600 hover:border-slate-300'"
                class="rounded-full border px-3 py-1 text-xs font-medium transition"
            >
                {{ opt.label }}
            </button>
        </div>
    </div>
</template>
