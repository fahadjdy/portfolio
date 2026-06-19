<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Inbox, Mail, FolderKanban, Download } from 'lucide-vue-next';

defineProps<{
    stats: Record<string, number>;
    leadsByStatus: Record<string, number>;
    recentLeads: Array<{ id: number; name: string; email: string; status: string; created_at: string }>;
}>();

const breadcrumbs = [{ title: 'Dashboard', href: '/admin/dashboard' }];

const cards = [
    { key: 'leadsNew', label: 'New leads', accent: 'text-brand-600', icon: Inbox },
    { key: 'leadsTotal', label: 'Total leads', icon: Mail },
    { key: 'projectsPublished', label: 'Published projects', icon: FolderKanban },
    { key: 'cvDownloads', label: 'CV downloads', icon: Download },
];
</script>

<template>
    <Head title="Dashboard" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-4">
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div v-for="c in cards" :key="c.key" class="rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-700 dark:bg-slate-800">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-slate-500">{{ c.label }}</p>
                        <span class="grid h-9 w-9 place-items-center rounded-lg bg-brand-50 text-brand-600 dark:bg-brand-600/20 dark:text-brand-300"><component :is="c.icon" class="h-4 w-4" /></span>
                    </div>
                    <p class="mt-2 text-3xl font-extrabold" :class="c.accent || 'text-slate-900 dark:text-white'">{{ stats[c.key] ?? 0 }}</p>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-700 dark:bg-slate-800">
                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Leads by status</h2>
                    <ul class="mt-4 space-y-2">
                        <li v-for="(count, status) in leadsByStatus" :key="status" class="flex items-center justify-between text-sm">
                            <span class="capitalize text-slate-600 dark:text-slate-300">{{ String(status).replace('_', ' ') }}</span>
                            <span class="font-semibold text-slate-900 dark:text-white">{{ count }}</span>
                        </li>
                        <li v-if="Object.keys(leadsByStatus).length === 0" class="text-sm text-slate-400">No leads yet.</li>
                    </ul>
                    <div class="mt-4 grid grid-cols-3 gap-3 border-t border-slate-100 pt-4 text-center dark:border-slate-700">
                        <div><p class="text-xs text-slate-500">Projects</p><p class="font-bold text-slate-900 dark:text-white">{{ stats.projects }}</p></div>
                        <div><p class="text-xs text-slate-500">Services</p><p class="font-bold text-slate-900 dark:text-white">{{ stats.services }}</p></div>
                        <div><p class="text-xs text-slate-500">Skills</p><p class="font-bold text-slate-900 dark:text-white">{{ stats.skills }}</p></div>
                    </div>
                </div>

                <div class="lg:col-span-2 rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-700 dark:bg-slate-800">
                    <div class="mb-3 flex items-center justify-between">
                        <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Recent leads</h2>
                        <Link :href="route('admin.leads.index')" class="text-sm font-medium text-brand-700 hover:underline">View all</Link>
                    </div>
                    <table class="w-full text-sm">
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            <tr v-for="lead in recentLeads" :key="lead.id">
                                <td class="py-2.5">
                                    <Link :href="route('admin.leads.show', lead.id)" class="font-medium text-slate-900 hover:text-brand-700 dark:text-white">{{ lead.name }}</Link>
                                    <p class="text-xs text-slate-500">{{ lead.email }}</p>
                                </td>
                                <td class="py-2.5 text-right">
                                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs capitalize text-slate-600 dark:bg-slate-700 dark:text-slate-200">{{ lead.status.replace('_', ' ') }}</span>
                                </td>
                            </tr>
                            <tr v-if="recentLeads.length === 0"><td class="py-6 text-center text-slate-400">No leads yet.</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
