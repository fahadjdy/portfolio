<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    lead: any;
    statuses: Array<{ value: string; label: string }>;
}>();

const statusForm = useForm({ status: props.lead.status });
const noteForm = useForm({ body: '' });

function updateStatus() {
    statusForm.patch(route('admin.leads.status', props.lead.id), { preserveScroll: true });
}
function addNote() {
    noteForm.post(route('admin.leads.notes.store', props.lead.id), { preserveScroll: true, onSuccess: () => noteForm.reset('body') });
}

const breadcrumbs = [
    { title: 'Leads', href: route('admin.leads.index') },
    { title: props.lead.name, href: '#' },
];
</script>

<template>
    <Head :title="`Lead: ${lead.name}`" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="grid gap-6 p-4 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-xl border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-800">
                    <h1 class="text-xl font-bold text-slate-900 dark:text-white">{{ lead.name }}</h1>
                    <div class="mt-3 grid gap-3 text-sm sm:grid-cols-2">
                        <div><span class="text-slate-400">Email:</span> <a :href="`mailto:${lead.email}`" class="text-brand-700 hover:underline">{{ lead.email }}</a></div>
                        <div v-if="lead.phone"><span class="text-slate-400">Phone:</span> {{ lead.phone }}</div>
                        <div v-if="lead.company"><span class="text-slate-400">Company:</span> {{ lead.company }}</div>
                        <div v-if="lead.service"><span class="text-slate-400">Service:</span> {{ lead.service.title }}</div>
                        <div v-if="lead.budget"><span class="text-slate-400">Budget:</span> {{ lead.budget }}</div>
                        <div><span class="text-slate-400">Received:</span> {{ new Date(lead.created_at).toLocaleString() }}</div>
                    </div>
                    <div class="mt-4 rounded-lg bg-slate-50 p-4 text-sm text-slate-700 dark:bg-slate-900/40 dark:text-slate-200">
                        <p class="whitespace-pre-line">{{ lead.message }}</p>
                    </div>
                    <a :href="`mailto:${lead.email}?subject=Re: your inquiry`" class="mt-4 inline-block rounded-lg bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-700">Reply by email</a>
                </div>

                <div class="rounded-xl border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-800">
                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Notes</h2>
                    <form @submit.prevent="addNote" class="mt-3 flex gap-2">
                        <input v-model="noteForm.body" placeholder="Add a follow-up note…" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-900" />
                        <button :disabled="noteForm.processing" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white disabled:opacity-60 dark:bg-slate-200 dark:text-slate-900">Add</button>
                    </form>
                    <ul class="mt-4 space-y-3">
                        <li v-for="note in lead.notes" :key="note.id" class="rounded-lg border border-slate-100 p-3 text-sm dark:border-slate-700">
                            <p class="text-slate-700 dark:text-slate-200">{{ note.body }}</p>
                            <p class="mt-1 text-xs text-slate-400">{{ note.user?.name ?? 'Admin' }} · {{ new Date(note.created_at).toLocaleString() }}</p>
                        </li>
                        <li v-if="!lead.notes || lead.notes.length === 0" class="text-sm text-slate-400">No notes yet.</li>
                    </ul>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-xl border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-800">
                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Status</h2>
                    <select v-model="statusForm.status" @change="updateStatus" class="mt-3 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-900">
                        <option v-for="s in statuses" :key="s.value" :value="s.value">{{ s.label }}</option>
                    </select>
                    <Link :href="route('admin.leads.index')" class="mt-4 inline-block text-sm text-slate-500 hover:text-slate-700">← Back to leads</Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
