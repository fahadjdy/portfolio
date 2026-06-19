<script setup lang="ts">
import TextField from '@/components/form/TextField.vue';
import TextareaField from '@/components/form/TextareaField.vue';
import RepeaterField from '@/components/form/RepeaterField.vue';
import ImageField from '@/components/form/ImageField.vue';
import { useForm } from '@inertiajs/vue3';
import { reactive } from 'vue';

const props = defineProps<{ section: any }>();

const isHero = props.section.section_key === 'hero';

// Local copy of the JSON data block so we preserve keys we don't edit (e.g. CTAs).
const data = reactive({ ...(props.section.data || {}) });
if (isHero && !Array.isArray(data.rotating_titles)) data.rotating_titles = [];
if (isHero && !Array.isArray(data.stats)) data.stats = [];

const form = useForm({
    heading: props.section.heading ?? '',
    subheading: props.section.subheading ?? '',
    body: props.section.body ?? '',
    image: null as File | null,
    data: data,
});

function addStat() { data.stats.push({ value: '', label: '' }); }
function removeStat(i: number) { data.stats.splice(i, 1); }

function submit() {
    form.transform((d) => ({ ...d, data: data, _method: 'put' })).post(route('admin.pages.update', props.section.id), { forceFormData: true, preserveScroll: true });
}

const title = `${props.section.page} · ${props.section.section_key}`.replace(/_/g, ' ');
</script>

<template>
    <form @submit.prevent="submit" class="rounded-xl border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-800">
        <h2 class="mb-4 text-sm font-semibold capitalize text-slate-900 dark:text-white">{{ title }}</h2>
        <div class="space-y-4">
            <TextField v-model="form.heading" label="Heading" :error="form.errors.heading" />
            <TextField v-model="form.subheading" label="Subheading" :error="form.errors.subheading" />
            <TextareaField v-model="form.body" label="Body" :rows="5" :error="form.errors.body" />
            <ImageField v-model="form.image" label="Image" :current="section.image" :error="form.errors.image" />

            <template v-if="isHero">
                <RepeaterField v-model="data.rotating_titles" label="Rotating titles" placeholder="e.g. SaaS platforms" />
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-200">Stats</label>
                    <div class="space-y-2">
                        <div v-for="(stat, i) in data.stats" :key="i" class="flex gap-2">
                            <input v-model="stat.value" placeholder="9+" class="w-24 rounded-lg border border-slate-300 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-900" />
                            <input v-model="stat.label" placeholder="Projects" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-900" />
                            <button type="button" @click="removeStat(i)" class="rounded-lg border border-slate-200 px-2.5 text-slate-400 hover:text-rose-600 dark:border-slate-600">&times;</button>
                        </div>
                    </div>
                    <button type="button" @click="addStat" class="mt-2 text-sm font-medium text-brand-700">+ Add stat</button>
                </div>
            </template>
        </div>
        <button type="submit" :disabled="form.processing" class="mt-5 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-700 disabled:opacity-60">Save section</button>
    </form>
</template>
