<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';

defineProps<{ resumes: any[] }>();

const form = useForm({ label: '', file: null as File | null });

function submit() {
    form.post(route('admin.resumes.store'), { forceFormData: true, onSuccess: () => form.reset() });
}
function destroy(id: number) {
    if (confirm('Delete this resume?')) router.delete(route('admin.resumes.destroy', id), { preserveScroll: true });
}

const breadcrumbs = [{ title: 'Resume / CV', href: route('admin.resumes.index') }];
</script>

<template>
    <Head title="Resume / CV" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="max-w-2xl space-y-6 p-4">
            <h1 class="text-xl font-bold text-slate-900 dark:text-white">Resume / CV</h1>

            <form @submit.prevent="submit" class="rounded-xl border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-800">
                <h2 class="mb-4 text-sm font-semibold text-slate-900 dark:text-white">Upload new (PDF)</h2>
                <div class="space-y-4">
                    <input v-model="form.label" placeholder="Label (e.g. Resume 2026)" class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm dark:border-slate-600 dark:bg-slate-900" />
                    <input type="file" accept="application/pdf" @change="form.file = ($event.target as HTMLInputElement).files?.[0] ?? null" class="block text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-brand-50 file:px-3 file:py-2 file:font-medium file:text-brand-700" />
                    <p v-if="form.errors.file" class="text-xs text-rose-600">{{ form.errors.file }}</p>
                    <button type="submit" :disabled="form.processing" class="rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-700 disabled:opacity-60">Upload &amp; set current</button>
                </div>
            </form>

            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-800">
                <table class="w-full text-sm">
                    <thead class="border-b border-slate-200 bg-slate-50 text-left text-xs uppercase text-slate-500 dark:border-slate-700 dark:bg-slate-900/40">
                        <tr><th class="px-4 py-3">Label</th><th class="px-4 py-3">Downloads</th><th class="px-4 py-3">Current</th><th class="px-4 py-3"></th></tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        <tr v-for="r in resumes" :key="r.id">
                            <td class="px-4 py-3"><a :href="r.url" target="_blank" class="font-medium text-brand-700 hover:underline">{{ r.label }}</a><p class="text-xs text-slate-400">{{ r.original_name }}</p></td>
                            <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ r.downloads }}</td>
                            <td class="px-4 py-3"><span v-if="r.is_current" class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-700">Current</span></td>
                            <td class="px-4 py-3 text-right"><button @click="destroy(r.id)" class="text-sm font-medium text-rose-600 hover:underline">Delete</button></td>
                        </tr>
                        <tr v-if="resumes.length === 0"><td colspan="4" class="px-4 py-10 text-center text-slate-400">No resume uploaded yet.</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
