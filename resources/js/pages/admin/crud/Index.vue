<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ChevronUp, ChevronDown, Pencil, Plus, Trash2 } from 'lucide-vue-next';
import { ref, watch } from 'vue';

const props = defineProps<{
    resource: { singular: string; plural: string; routeBase: string; sortable: boolean };
    columns: Array<{ key: string; label: string; type?: string; suffix?: string }>;
    items: Array<Record<string, any>>;
}>();

const rows = ref([...props.items]);
watch(() => props.items, (v) => (rows.value = [...v]));

const breadcrumbs = [{ title: props.resource.plural, href: '#' }];

function destroy(id: number) {
    if (confirm('Delete this item? This cannot be undone.')) {
        router.delete(route(props.resource.routeBase + '.destroy', id), { preserveScroll: true });
    }
}

function move(index: number, dir: number) {
    const target = index + dir;
    if (target < 0 || target >= rows.value.length) return;
    const list = [...rows.value];
    [list[index], list[target]] = [list[target], list[index]];
    rows.value = list;
    router.post(route(props.resource.routeBase + '.reorder'), { ids: list.map((r) => r.id) }, { preserveScroll: true, preserveState: true });
}
</script>

<template>
    <Head :title="resource.plural" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <div class="mb-5 flex items-center justify-between">
                <h1 class="text-xl font-bold text-slate-900 dark:text-white">{{ resource.plural }}</h1>
                <Link :href="route(resource.routeBase + '.create')" class="inline-flex items-center gap-1.5 rounded-lg bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-700">
                    <Plus class="h-4 w-4" /> New {{ resource.singular }}
                </Link>
            </div>

            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-800">
                <table class="w-full text-sm">
                    <thead class="border-b border-slate-200 bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500 dark:border-slate-700 dark:bg-slate-900/40">
                        <tr>
                            <th v-if="resource.sortable" class="w-16 px-4 py-3">Order</th>
                            <th v-for="col in columns" :key="col.key" class="px-4 py-3">{{ col.label }}</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        <tr v-for="(row, i) in rows" :key="row.id" class="hover:bg-slate-50 dark:hover:bg-slate-900/30">
                            <td v-if="resource.sortable" class="px-4 py-3">
                                <div class="flex flex-col gap-0.5">
                                    <button @click="move(i, -1)" :disabled="i === 0" class="text-slate-400 hover:text-brand-600 disabled:opacity-30"><ChevronUp class="h-4 w-4" /></button>
                                    <button @click="move(i, 1)" :disabled="i === rows.length - 1" class="text-slate-400 hover:text-brand-600 disabled:opacity-30"><ChevronDown class="h-4 w-4" /></button>
                                </div>
                            </td>
                            <td v-for="col in columns" :key="col.key" class="px-4 py-3 text-slate-700 dark:text-slate-200">
                                <span v-if="col.type === 'bool'" :class="row[col.key] ? 'text-emerald-600' : 'text-slate-300'">
                                    {{ row[col.key] ? '✓' : '—' }}
                                </span>
                                <span v-else>{{ row[col.key] ?? '—' }}{{ row[col.key] != null && col.suffix ? col.suffix : '' }}</span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <Link :href="route(resource.routeBase + '.edit', row.id)" class="inline-flex items-center gap-1 text-sm font-medium text-brand-700 hover:underline"><Pencil class="h-3.5 w-3.5" /> Edit</Link>
                                <button @click="destroy(row.id)" class="ml-3 inline-flex items-center gap-1 text-sm font-medium text-rose-600 hover:underline"><Trash2 class="h-3.5 w-3.5" /> Delete</button>
                            </td>
                        </tr>
                        <tr v-if="rows.length === 0">
                            <td :colspan="columns.length + 2" class="px-4 py-10 text-center text-slate-400">No {{ resource.plural.toLowerCase() }} yet.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
