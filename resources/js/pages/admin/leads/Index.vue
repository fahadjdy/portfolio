<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    leads: { data: any[]; links: any[] };
    filters: { status?: string; search?: string };
    statuses: Array<{ value: string; label: string; color: string }>;
    counts: { all: number; new: number };
}>();

const search = ref(props.filters.search ?? '');
const status = ref(props.filters.status ?? '');

function apply() {
    router.get(route('admin.leads.index'), { search: search.value || undefined, status: status.value || undefined }, { preserveState: true, replace: true });
}

function destroy(id: number) {
    if (confirm('Delete this lead?')) router.delete(route('admin.leads.destroy', id), { preserveScroll: true });
}

const breadcrumbs = [{ title: 'Leads', href: route('admin.leads.index') }];
</script>

<template>
    <Head title="Leads" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                <h1 class="text-xl font-bold text-slate-900 dark:text-white">Leads <span class="text-sm font-normal text-slate-400">({{ counts.all }})</span></h1>
                <a :href="route('admin.leads.export')" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200">Export CSV</a>
            </div>

            <div class="mb-4 flex flex-wrap gap-3">
                <input v-model="search" @keyup.enter="apply" placeholder="Search name, email, company…" class="w-64 rounded-lg border border-slate-300 px-3.5 py-2 text-sm dark:border-slate-600 dark:bg-slate-800" />
                <select v-model="status" @change="apply" class="rounded-lg border border-slate-300 px-3.5 py-2 text-sm dark:border-slate-600 dark:bg-slate-800">
                    <option value="">All statuses</option>
                    <option v-for="s in statuses" :key="s.value" :value="s.value">{{ s.label }}</option>
                </select>
                <button @click="apply" class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-700">Filter</button>
            </div>

            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-800">
                <table class="w-full text-sm">
                    <thead class="border-b border-slate-200 bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500 dark:border-slate-700 dark:bg-slate-900/40">
                        <tr><th class="px-4 py-3">Lead</th><th class="px-4 py-3">Service</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Received</th><th class="px-4 py-3"></th></tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        <tr v-for="lead in leads.data" :key="lead.id" class="hover:bg-slate-50 dark:hover:bg-slate-900/30">
                            <td class="px-4 py-3">
                                <Link :href="route('admin.leads.show', lead.id)" class="font-medium text-slate-900 hover:text-brand-700 dark:text-white">{{ lead.name }}</Link>
                                <p class="text-xs text-slate-500">{{ lead.email }}<span v-if="lead.company"> · {{ lead.company }}</span></p>
                            </td>
                            <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ lead.service?.title ?? '—' }}</td>
                            <td class="px-4 py-3"><span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs capitalize text-slate-600 dark:bg-slate-700 dark:text-slate-200">{{ lead.status.replace('_', ' ') }}</span></td>
                            <td class="px-4 py-3 text-slate-500">{{ new Date(lead.created_at).toLocaleDateString() }}</td>
                            <td class="px-4 py-3 text-right">
                                <Link :href="route('admin.leads.show', lead.id)" class="text-sm font-medium text-brand-700 hover:underline">View</Link>
                                <button @click="destroy(lead.id)" class="ml-3 text-sm font-medium text-rose-600 hover:underline">Delete</button>
                            </td>
                        </tr>
                        <tr v-if="leads.data.length === 0"><td colspan="5" class="px-4 py-10 text-center text-slate-400">No leads found.</td></tr>
                    </tbody>
                </table>
            </div>

            <div v-if="leads.links.length > 3" class="mt-4 flex flex-wrap gap-1">
                <template v-for="(link, i) in leads.links" :key="i">
                    <Link v-if="link.url" :href="link.url" v-html="link.label" :class="link.active ? 'bg-brand-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-300'" class="rounded-lg border border-slate-200 px-3 py-1.5 text-sm dark:border-slate-700" />
                    <span v-else v-html="link.label" class="rounded-lg px-3 py-1.5 text-sm text-slate-300" />
                </template>
            </div>
        </div>
    </AppLayout>
</template>
