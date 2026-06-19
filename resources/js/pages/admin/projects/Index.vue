<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps<{ projects: any[] }>();
const rows = ref([...props.projects]);
watch(() => props.projects, (v) => (rows.value = [...v]));

function destroy(id: number) {
    if (confirm('Delete this project? This cannot be undone.')) router.delete(route('admin.projects.destroy', id), { preserveScroll: true });
}
function move(index: number, dir: number) {
    const target = index + dir;
    if (target < 0 || target >= rows.value.length) return;
    const list = [...rows.value];
    [list[index], list[target]] = [list[target], list[index]];
    rows.value = list;
    router.post(route('admin.projects.reorder'), { ids: list.map((r) => r.id) }, { preserveScroll: true, preserveState: true });
}

const breadcrumbs = [{ title: 'Projects', href: route('admin.projects.index') }];
</script>

<template>
    <Head title="Projects" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <div class="mb-5 flex items-center justify-between">
                <h1 class="text-xl font-bold text-slate-900 dark:text-white">Projects</h1>
                <Link :href="route('admin.projects.create')" class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-700">+ New Project</Link>
            </div>

            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-800">
                <table class="w-full text-sm">
                    <thead class="border-b border-slate-200 bg-slate-50 text-left text-xs uppercase text-slate-500 dark:border-slate-700 dark:bg-slate-900/40">
                        <tr><th class="w-16 px-4 py-3">Order</th><th class="px-4 py-3">Title</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Panels</th><th class="px-4 py-3">Featured</th><th class="px-4 py-3"></th></tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        <tr v-for="(p, i) in rows" :key="p.id" class="hover:bg-slate-50 dark:hover:bg-slate-900/30">
                            <td class="px-4 py-3">
                                <div class="flex flex-col gap-0.5">
                                    <button @click="move(i, -1)" :disabled="i === 0" class="text-slate-400 hover:text-brand-600 disabled:opacity-30">▲</button>
                                    <button @click="move(i, 1)" :disabled="i === rows.length - 1" class="text-slate-400 hover:text-brand-600 disabled:opacity-30">▼</button>
                                </div>
                            </td>
                            <td class="px-4 py-3"><span class="font-medium text-slate-900 dark:text-white">{{ p.title }}</span><p class="text-xs text-slate-400">{{ p.category }}</p></td>
                            <td class="px-4 py-3"><span :class="p.status === 'published' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500'" class="rounded-full px-2 py-0.5 text-xs capitalize">{{ p.status }}</span></td>
                            <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ p.panels_count }}</td>
                            <td class="px-4 py-3">{{ p.is_featured ? '★' : '—' }}</td>
                            <td class="px-4 py-3 text-right">
                                <Link :href="route('admin.projects.edit', p.id)" class="text-sm font-medium text-brand-700 hover:underline">Edit</Link>
                                <button @click="destroy(p.id)" class="ml-3 text-sm font-medium text-rose-600 hover:underline">Delete</button>
                            </td>
                        </tr>
                        <tr v-if="rows.length === 0"><td colspan="6" class="px-4 py-10 text-center text-slate-400">No projects yet.</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
