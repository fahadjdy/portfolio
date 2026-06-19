<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const page = usePage<any>();
const toast = ref<{ type: 'success' | 'error'; text: string } | null>(null);
let timer: ReturnType<typeof setTimeout> | undefined;

function show(type: 'success' | 'error', text: string) {
    toast.value = { type, text };
    clearTimeout(timer);
    timer = setTimeout(() => (toast.value = null), 5000);
}

watch(
    () => page.props.flash,
    (flash: any) => {
        if (flash?.success) show('success', flash.success);
        else if (flash?.error) show('error', flash.error);
    },
    { deep: true, immediate: true },
);
</script>

<template>
    <Transition
        enter-active-class="transition duration-200" enter-from-class="translate-y-2 opacity-0"
        leave-active-class="transition duration-200" leave-to-class="translate-y-2 opacity-0"
    >
        <div
            v-if="toast"
            :class="toast.type === 'error' ? 'bg-rose-600' : 'bg-emerald-600'"
            class="fixed right-4 top-4 z-50 max-w-sm rounded-xl px-4 py-3 text-sm font-medium text-white shadow-lg"
        >
            {{ toast.text }}
        </div>
    </Transition>
</template>
