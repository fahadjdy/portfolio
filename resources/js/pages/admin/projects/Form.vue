<script setup lang="ts">
import TextField from '@/components/form/TextField.vue';
import TextareaField from '@/components/form/TextareaField.vue';
import SelectField from '@/components/form/SelectField.vue';
import ToggleField from '@/components/form/ToggleField.vue';
import RepeaterField from '@/components/form/RepeaterField.vue';
import ImageField from '@/components/form/ImageField.vue';
import TagSelect from '@/components/form/TagSelect.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { Plus, Save, Star, Trash2 } from 'lucide-vue-next';

const props = defineProps<{
    project: any | null;
    techTags: Array<{ value: number; label: string }>;
    statuses: Array<{ value: string; label: string }>;
}>();

const isEdit = !!props.project;
const p = props.project;

const form = useForm({
    title: p?.title ?? '',
    slug: p?.slug ?? '',
    client_name: p?.client_name ?? '',
    category: p?.category ?? '',
    summary: p?.summary ?? '',
    problem: p?.problem ?? '',
    solution: p?.solution ?? '',
    outcome: p?.outcome ?? '',
    highlights: (p?.highlights ?? []) as string[],
    live_url: p?.live_url ?? '',
    repo_url: p?.repo_url ?? '',
    year: p?.year ?? '',
    role: p?.role ?? '',
    duration: p?.duration ?? '',
    status: p?.status ?? 'draft',
    is_featured: p?.is_featured ?? false,
    seo_title: p?.seo_title ?? '',
    seo_description: p?.seo_description ?? '',
    cover_image: null as File | null,
    thumbnail: null as File | null,
    og_image: null as File | null,
    tech_tag_ids: (p?.tech_tag_ids ?? []) as number[],
    panels: (p?.panels ?? []) as Array<{ name: string; icon: string; description: string; features: Array<{ title: string; description: string }> }>,
});

function addPanel() { form.panels.push({ name: '', icon: '', description: '', features: [] }); }
function removePanel(i: number) { form.panels.splice(i, 1); }
function addFeature(pi: number) { form.panels[pi].features.push({ title: '', description: '' }); }
function removeFeature(pi: number, fi: number) { form.panels[pi].features.splice(fi, 1); }

function submit() {
    if (isEdit) {
        form.transform((d) => ({ ...d, _method: 'put' })).post(route('admin.projects.update', p.id), { forceFormData: true });
    } else {
        form.post(route('admin.projects.store'), { forceFormData: true });
    }
}

// Gallery (edit only)
const gallery = useForm({ images: [] as File[], alt: '' });
function uploadImages() {
    gallery.post(route('admin.projects.images.store', p.id), { forceFormData: true, onSuccess: () => gallery.reset() });
}
function deleteImage(id: number) {
    if (confirm('Delete this image?')) router.delete(route('admin.project_images.destroy', id), { preserveScroll: true });
}
function makeCover(id: number) {
    router.post(route('admin.project_images.cover', id), {}, { preserveScroll: true });
}

const breadcrumbs = [
    { title: 'Projects', href: route('admin.projects.index') },
    { title: isEdit ? 'Edit' : 'New', href: '#' },
];
</script>

<template>
    <Head :title="(isEdit ? 'Edit ' : 'New ') + 'project'" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <form @submit.prevent="submit" class="max-w-4xl space-y-6 p-4">
            <h1 class="text-xl font-bold text-slate-900 dark:text-white">{{ isEdit ? 'Edit' : 'New' }} project</h1>

            <section class="rounded-xl border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-800">
                <h2 class="mb-4 text-sm font-semibold text-slate-900 dark:text-white">Overview</h2>
                <div class="grid gap-4 sm:grid-cols-2">
                    <TextField v-model="form.title" label="Title" required :error="form.errors.title" />
                    <TextField v-model="form.slug" label="Slug (optional)" :error="form.errors.slug" hint="Auto-generated from title if blank" />
                    <TextField v-model="form.category" label="Category" :error="form.errors.category" />
                    <TextField v-model="form.client_name" label="Client name" :error="form.errors.client_name" />
                    <TextField v-model="form.role" label="Your role" :error="form.errors.role" />
                    <TextField v-model="form.year" type="number" label="Year" :error="form.errors.year" />
                    <TextField v-model="form.live_url" label="Live URL" :error="form.errors.live_url" />
                    <TextField v-model="form.repo_url" label="Repo URL" :error="form.errors.repo_url" />
                </div>
                <div class="mt-4"><TextareaField v-model="form.summary" label="Summary" required :error="form.errors.summary" hint="Shown on cards and as the meta description fallback (max 500)" /></div>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <SelectField v-model="form.status" :options="statuses" label="Status" required :error="form.errors.status" />
                    <div class="flex items-end"><ToggleField v-model="form.is_featured" label="Featured" hint="Show on the home page" /></div>
                </div>
            </section>

            <section class="rounded-xl border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-800">
                <h2 class="mb-4 text-sm font-semibold text-slate-900 dark:text-white">Case study</h2>
                <div class="space-y-4">
                    <TextareaField v-model="form.problem" label="The challenge" :error="form.errors.problem" />
                    <TextareaField v-model="form.solution" label="The solution" :error="form.errors.solution" />
                    <TextareaField v-model="form.outcome" label="The outcome" :error="form.errors.outcome" />
                    <RepeaterField v-model="form.highlights" label="Key highlights" placeholder="A standout technical highlight" />
                </div>
            </section>

            <section class="rounded-xl border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-800">
                <h2 class="mb-4 text-sm font-semibold text-slate-900 dark:text-white">Tech &amp; media</h2>
                <TagSelect v-model="form.tech_tag_ids" :options="techTags" label="Tech stack" />
                <div class="mt-4 grid gap-4 sm:grid-cols-3">
                    <ImageField v-model="form.cover_image" label="Cover image" :current="p?.cover_image" :error="form.errors.cover_image" />
                    <ImageField v-model="form.thumbnail" label="Thumbnail" :current="p?.thumbnail" :error="form.errors.thumbnail" />
                    <ImageField v-model="form.og_image" label="OG image" :current="p?.og_image" :error="form.errors.og_image" />
                </div>
            </section>

            <section class="rounded-xl border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-800">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Panels &amp; features</h2>
                    <button type="button" @click="addPanel" class="inline-flex items-center gap-1 rounded-lg border border-slate-200 px-3 py-1.5 text-sm font-medium text-brand-700 hover:bg-brand-50 dark:border-slate-600 dark:hover:bg-slate-700"><Plus class="h-4 w-4" /> Add panel</button>
                </div>
                <div class="space-y-4">
                    <div v-for="(panel, pi) in form.panels" :key="pi" class="rounded-lg border border-slate-200 p-4 dark:border-slate-700">
                        <div class="flex items-start justify-between gap-3">
                            <div class="grid flex-1 gap-3 sm:grid-cols-3">
                                <input v-model="panel.name" placeholder="Panel name (e.g. Admin)" class="rounded-lg border border-slate-300 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-900" />
                                <input v-model="panel.icon" placeholder="Icon (lucide)" class="rounded-lg border border-slate-300 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-900" />
                                <input v-model="panel.description" placeholder="Short description" class="rounded-lg border border-slate-300 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-900" />
                            </div>
                            <button type="button" @click="removePanel(pi)" class="shrink-0 rounded-lg border border-slate-200 px-2.5 py-2 text-slate-400 hover:text-rose-600 dark:border-slate-600">&times;</button>
                        </div>
                        <div class="mt-3 space-y-2 border-l-2 border-slate-100 pl-4 dark:border-slate-700">
                            <div v-for="(feature, fi) in panel.features" :key="fi" class="flex gap-2">
                                <input v-model="feature.title" placeholder="Feature title" class="w-full rounded-lg border border-slate-300 px-3 py-1.5 text-sm dark:border-slate-600 dark:bg-slate-900" />
                                <button type="button" @click="removeFeature(pi, fi)" class="shrink-0 text-slate-400 hover:text-rose-600">&times;</button>
                            </div>
                            <button type="button" @click="addFeature(pi)" class="text-sm font-medium text-brand-700">+ Add feature</button>
                        </div>
                    </div>
                    <p v-if="form.panels.length === 0" class="text-sm text-slate-400">No panels yet. Add one to describe a role/area of the product.</p>
                </div>
            </section>

            <section class="rounded-xl border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-800">
                <h2 class="mb-4 text-sm font-semibold text-slate-900 dark:text-white">SEO</h2>
                <div class="space-y-4">
                    <TextField v-model="form.seo_title" label="SEO title" :error="form.errors.seo_title" />
                    <TextareaField v-model="form.seo_description" label="SEO description" :error="form.errors.seo_description" />
                </div>
            </section>

            <div class="flex items-center gap-3">
                <button type="submit" :disabled="form.processing" class="inline-flex items-center gap-1.5 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-700 disabled:opacity-60"><Save class="h-4 w-4" /> {{ isEdit ? 'Save project' : 'Create project' }}</button>
                <Link :href="route('admin.projects.index')" class="text-sm font-medium text-slate-500 hover:text-slate-700">Cancel</Link>
            </div>
        </form>

        <!-- Gallery (edit only) -->
        <div v-if="isEdit" class="max-w-4xl p-4">
            <section class="rounded-xl border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-800">
                <h2 class="mb-4 text-sm font-semibold text-slate-900 dark:text-white">Gallery / screenshots</h2>
                <div class="flex flex-wrap items-end gap-3">
                    <input type="file" multiple accept="image/*" @change="gallery.images = Array.from(($event.target as HTMLInputElement).files || [])" class="block text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-brand-50 file:px-3 file:py-2 file:font-medium file:text-brand-700" />
                    <input v-model="gallery.alt" placeholder="Alt text (optional)" class="rounded-lg border border-slate-300 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-900" />
                    <button type="button" @click="uploadImages" :disabled="gallery.processing || gallery.images.length === 0" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white disabled:opacity-50 dark:bg-slate-200 dark:text-slate-900">Upload</button>
                </div>
                <p v-if="p?.images?.length" class="mt-3 text-xs text-slate-500">Hover an image to set it as the listing <strong>banner</strong> or delete it.</p>
                <div v-if="p?.images?.length" class="mt-3 grid grid-cols-2 gap-3 sm:grid-cols-4">
                    <div v-for="img in p.images" :key="img.id" class="group relative overflow-hidden rounded-lg border border-slate-200 dark:border-slate-700">
                        <img :src="img.url" :alt="img.alt" class="aspect-video w-full object-cover" />
                        <span v-if="img.is_cover" class="absolute left-1.5 top-1.5 inline-flex items-center gap-1 rounded bg-amber-400 px-1.5 py-0.5 text-[10px] font-bold text-slate-900"><Star class="h-3 w-3" /> Banner</span>
                        <div class="absolute inset-x-0 bottom-0 flex items-center justify-between gap-1 bg-gradient-to-t from-black/70 to-transparent p-1.5 opacity-0 transition group-hover:opacity-100">
                            <button type="button" @click="makeCover(img.id)" :disabled="img.is_cover" class="inline-flex items-center gap-1 rounded bg-white/90 px-1.5 py-0.5 text-[10px] font-semibold text-slate-800 hover:bg-white disabled:opacity-50">
                                <Star class="h-3 w-3" /> {{ img.is_cover ? 'Banner' : 'Set banner' }}
                            </button>
                            <button type="button" @click="deleteImage(img.id)" class="grid h-6 w-6 place-items-center rounded bg-rose-600 text-white hover:bg-rose-700"><Trash2 class="h-3 w-3" /></button>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>
