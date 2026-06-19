<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps<{ actions: Array<{ key: string; label: string; url: string }> }>();

const breadcrumbs = [{ title: 'Maintenance', href: route('admin.tools.index') }];

function confirmRun(e: MouseEvent, key: string) {
    if (key === 'migrate' || key === 'seed') {
        if (!confirm(`Run "${key}" on the live database? This changes data.`)) e.preventDefault();
    }
}
</script>

<template>
    <Head title="Maintenance" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="max-w-2xl p-4">
            <h1 class="text-xl font-bold text-slate-900 dark:text-white">Maintenance tools</h1>
            <p class="mt-1 text-sm text-slate-500">Run safe deploy/maintenance commands. These open in a new tab and show the command output. Available only while you're logged in.</p>

            <div class="mt-6 divide-y divide-slate-100 overflow-hidden rounded-xl border border-slate-200 bg-white dark:divide-slate-700 dark:border-slate-700 dark:bg-slate-800">
                <div v-for="action in actions" :key="action.key" class="flex items-center justify-between gap-4 p-4">
                    <div>
                        <p class="font-medium text-slate-900 dark:text-white">/{{ action.key }}</p>
                        <p class="text-sm text-slate-500">{{ action.label }}</p>
                    </div>
                    <a :href="action.url" target="_blank" rel="noopener" @click="confirmRun($event, action.key)"
                       class="shrink-0 rounded-lg bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-700">
                        Run
                    </a>
                </div>
            </div>

            <p class="mt-4 text-xs text-slate-400">Tip: after a deploy run <strong>/storage</strong> (symlink) once, then <strong>/migrate</strong>, then <strong>/cache</strong>. Use <strong>/clear</strong> if pages look stale.</p>
        </div>
    </AppLayout>
</template>
