<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Inbox, Mail, FolderKanban, Download, Eye, CalendarDays, Users, BarChart3, Globe } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    stats: Record<string, number>;
    leadsByStatus: Record<string, number>;
    recentLeads: Array<{ id: number; name: string; email: string; status: string; created_at: string }>;
    analytics: {
        visitsToday: number;
        visitsMonth: number;
        visitsTotal: number;
        uniqueMonth: number;
        trend: Array<{ label: string; count: number }>;
        topCountries: Array<{ country: string; country_code: string | null; count: number }>;
        topPages: Array<{ path: string; count: number }>;
        devices: Record<string, number>;
    };
}>();

const breadcrumbs = [{ title: 'Dashboard', href: '/admin/dashboard' }];

const cards = [
    { key: 'leadsNew', label: 'New leads', accent: 'text-brand-600', icon: Inbox },
    { key: 'leadsTotal', label: 'Total leads', icon: Mail },
    { key: 'projectsPublished', label: 'Published projects', icon: FolderKanban },
    { key: 'cvDownloads', label: 'CV downloads', icon: Download },
];

const visitCards = computed(() => [
    { label: 'Visits today', value: props.analytics.visitsToday, icon: Eye },
    { label: 'This month', value: props.analytics.visitsMonth, icon: CalendarDays },
    { label: 'Unique (month)', value: props.analytics.uniqueMonth, icon: Users },
    { label: 'Total visits', value: props.analytics.visitsTotal, icon: BarChart3 },
]);

const maxTrend = computed(() => Math.max(1, ...props.analytics.trend.map((t) => t.count)));
const maxCountry = computed(() => Math.max(1, ...props.analytics.topCountries.map((c) => c.count)));
const maxPage = computed(() => Math.max(1, ...props.analytics.topPages.map((p) => p.count)));
const deviceEntries = computed(() => Object.entries(props.analytics.devices));

function flag(cc: string | null): string {
    if (!cc) return '🌐';
    return cc.toUpperCase().replace(/./g, (c) => String.fromCodePoint(127397 + c.charCodeAt(0)));
}
</script>

<template>
    <Head title="Dashboard" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-8 p-4">
            <!-- KPI cards -->
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div v-for="c in cards" :key="c.key" class="rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-700 dark:bg-slate-800">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-slate-500">{{ c.label }}</p>
                        <span class="grid h-9 w-9 place-items-center rounded-lg bg-brand-50 text-brand-600 dark:bg-brand-600/20 dark:text-brand-300"><component :is="c.icon" class="h-4 w-4" /></span>
                    </div>
                    <p class="mt-2 text-3xl font-extrabold" :class="c.accent || 'text-slate-900 dark:text-white'">{{ stats[c.key] ?? 0 }}</p>
                </div>
            </div>

            <!-- Site analytics -->
            <section>
                <h2 class="mb-3 font-display text-lg font-bold text-slate-900 dark:text-white">Site analytics</h2>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div v-for="v in visitCards" :key="v.label" class="rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-700 dark:bg-slate-800">
                        <div class="flex items-center justify-between">
                            <p class="text-sm text-slate-500">{{ v.label }}</p>
                            <span class="grid h-9 w-9 place-items-center rounded-lg bg-emerald-50 text-emerald-600 dark:bg-emerald-600/20 dark:text-emerald-300"><component :is="v.icon" class="h-4 w-4" /></span>
                        </div>
                        <p class="mt-2 text-3xl font-extrabold text-slate-900 dark:text-white">{{ v.value }}</p>
                    </div>
                </div>

                <!-- 14-day trend -->
                <div class="mt-4 rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-700 dark:bg-slate-800">
                    <p class="text-sm font-semibold text-slate-900 dark:text-white">Visits — last 14 days</p>
                    <div class="mt-4 flex h-40 items-end gap-1.5">
                        <div v-for="(t, i) in analytics.trend" :key="i" class="group flex flex-1 flex-col items-center justify-end">
                            <span class="mb-1 text-[10px] font-medium text-slate-400 opacity-0 group-hover:opacity-100">{{ t.count }}</span>
                            <div class="w-full rounded-t bg-brand-500/80 transition hover:bg-brand-600" :style="{ height: Math.max(2, (t.count / maxTrend) * 100) + '%' }"></div>
                            <span class="mt-1.5 text-[10px] text-slate-400">{{ t.label.split(' ')[0] }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 grid gap-4 lg:grid-cols-3">
                    <!-- Top countries -->
                    <div class="rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-700 dark:bg-slate-800">
                        <p class="flex items-center gap-2 text-sm font-semibold text-slate-900 dark:text-white"><Globe class="h-4 w-4 text-slate-400" /> Top countries</p>
                        <ul class="mt-4 space-y-3">
                            <li v-for="c in analytics.topCountries" :key="c.country">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-slate-600 dark:text-slate-300">{{ flag(c.country_code) }} {{ c.country }}</span>
                                    <span class="font-semibold text-slate-900 dark:text-white">{{ c.count }}</span>
                                </div>
                                <div class="mt-1 h-1.5 w-full overflow-hidden rounded-full bg-slate-100 dark:bg-slate-700">
                                    <div class="h-full rounded-full bg-brand-500" :style="{ width: (c.count / maxCountry) * 100 + '%' }"></div>
                                </div>
                            </li>
                            <li v-if="analytics.topCountries.length === 0" class="text-sm text-slate-400">No data yet.</li>
                        </ul>
                    </div>

                    <!-- Top pages -->
                    <div class="rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-700 dark:bg-slate-800">
                        <p class="text-sm font-semibold text-slate-900 dark:text-white">Top pages</p>
                        <ul class="mt-4 space-y-3">
                            <li v-for="p in analytics.topPages" :key="p.path">
                                <div class="flex items-center justify-between gap-2 text-sm">
                                    <span class="truncate text-slate-600 dark:text-slate-300">/{{ p.path }}</span>
                                    <span class="shrink-0 font-semibold text-slate-900 dark:text-white">{{ p.count }}</span>
                                </div>
                                <div class="mt-1 h-1.5 w-full overflow-hidden rounded-full bg-slate-100 dark:bg-slate-700">
                                    <div class="h-full rounded-full bg-emerald-500" :style="{ width: (p.count / maxPage) * 100 + '%' }"></div>
                                </div>
                            </li>
                            <li v-if="analytics.topPages.length === 0" class="text-sm text-slate-400">No data yet.</li>
                        </ul>
                    </div>

                    <!-- Devices -->
                    <div class="rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-700 dark:bg-slate-800">
                        <p class="text-sm font-semibold text-slate-900 dark:text-white">Devices</p>
                        <ul class="mt-4 space-y-2">
                            <li v-for="[device, count] in deviceEntries" :key="device" class="flex items-center justify-between text-sm">
                                <span class="capitalize text-slate-600 dark:text-slate-300">{{ device }}</span>
                                <span class="font-semibold text-slate-900 dark:text-white">{{ count }}</span>
                            </li>
                            <li v-if="deviceEntries.length === 0" class="text-sm text-slate-400">No data yet.</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Leads -->
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
