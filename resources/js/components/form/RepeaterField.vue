<script setup lang="ts">
defineProps<{ modelValue: string[]; label?: string; placeholder?: string; hint?: string }>();
const emit = defineEmits<{ (e: 'update:modelValue', value: string[]): void }>();

function update(list: string[]) {
    emit('update:modelValue', list);
}
function add(current: string[]) {
    update([...(current || []), '']);
}
function remove(current: string[], i: number) {
    const list = [...current];
    list.splice(i, 1);
    update(list);
}
function set(current: string[], i: number, val: string) {
    const list = [...current];
    list[i] = val;
    update(list);
}
</script>

<template>
    <div>
        <label v-if="label" class="mb-1.5 block text-sm font-medium text-slate-700">{{ label }}</label>
        <p v-if="hint" class="mb-2 text-xs text-slate-400">{{ hint }}</p>
        <div class="space-y-2">
            <div v-for="(item, i) in (modelValue || [])" :key="i" class="flex items-center gap-2">
                <input
                    :value="item"
                    :placeholder="placeholder"
                    @input="set(modelValue, i, ($event.target as HTMLInputElement).value)"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200"
                />
                <button type="button" @click="remove(modelValue, i)" class="shrink-0 rounded-lg border border-slate-200 px-2.5 py-2 text-slate-400 hover:text-rose-600">&times;</button>
            </div>
        </div>
        <button type="button" @click="add(modelValue)" class="mt-2 text-sm font-medium text-brand-700 hover:text-brand-800">+ Add item</button>
    </div>
</template>
