<script setup lang="ts">
import TextField from '@/components/form/TextField.vue';
import TextareaField from '@/components/form/TextareaField.vue';
import ToggleField from '@/components/form/ToggleField.vue';
import ImageField from '@/components/form/ImageField.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Save } from 'lucide-vue-next';

const props = defineProps<{ values: Record<string, any> }>();

const form = useForm({
    site_name: props.values.site_name ?? '',
    tagline: props.values.tagline ?? '',
    logo: null as File | null,
    favicon: null as File | null,
    meta_title: props.values.meta_title ?? '',
    meta_description: props.values.meta_description ?? '',
    meta_keywords: props.values.meta_keywords ?? '',
    default_title_suffix: props.values.default_title_suffix ?? '',
    og_default_image: null as File | null,
    contact_email: props.values.contact_email ?? '',
    contact_phone: props.values.contact_phone ?? '',
    contact_address: props.values.contact_address ?? '',
    whatsapp: props.values.whatsapp ?? '',
    google_analytics_id: props.values.google_analytics_id ?? '',
    blog_enabled: !!props.values.blog_enabled,
});

function submit() {
    form.transform((d) => ({ ...d, _method: 'put' })).post(route('admin.settings.update'), { forceFormData: true });
}

const breadcrumbs = [{ title: 'Settings', href: route('admin.settings.index') }];
</script>

<template>
    <Head title="Settings" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <form @submit.prevent="submit" class="max-w-3xl space-y-6 p-4">
            <section class="rounded-xl border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-800">
                <h2 class="mb-4 text-sm font-semibold text-slate-900 dark:text-white">General</h2>
                <div class="space-y-4">
                    <TextField v-model="form.site_name" label="Site name" required :error="form.errors.site_name" />
                    <TextField v-model="form.tagline" label="Tagline" :error="form.errors.tagline" />
                    <ImageField v-model="form.logo" label="Logo" :current="values.logo" :error="form.errors.logo" />
                    <ImageField v-model="form.favicon" label="Favicon" :current="values.favicon" :error="form.errors.favicon" />
                </div>
            </section>

            <section class="rounded-xl border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-800">
                <h2 class="mb-4 text-sm font-semibold text-slate-900 dark:text-white">SEO</h2>
                <div class="space-y-4">
                    <TextField v-model="form.meta_title" label="Default meta title" :error="form.errors.meta_title" />
                    <TextareaField v-model="form.meta_description" label="Default meta description" :error="form.errors.meta_description" />
                    <TextField v-model="form.meta_keywords" label="Meta keywords" :error="form.errors.meta_keywords" />
                    <TextField v-model="form.default_title_suffix" label="Title suffix" :error="form.errors.default_title_suffix" />
                    <ImageField v-model="form.og_default_image" label="Default OG image" :current="values.og_default_image" :error="form.errors.og_default_image" />
                </div>
            </section>

            <section class="rounded-xl border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-800">
                <h2 class="mb-4 text-sm font-semibold text-slate-900 dark:text-white">Contact &amp; misc</h2>
                <div class="space-y-4">
                    <TextField v-model="form.contact_email" type="email" label="Contact email" :error="form.errors.contact_email" />
                    <TextField v-model="form.contact_phone" label="Contact phone" :error="form.errors.contact_phone" />
                    <TextField v-model="form.contact_address" label="Location" :error="form.errors.contact_address" />
                    <TextField v-model="form.whatsapp" label="WhatsApp" :error="form.errors.whatsapp" />
                    <TextField v-model="form.google_analytics_id" label="Google Analytics ID" :error="form.errors.google_analytics_id" />
                    <ToggleField v-model="form.blog_enabled" label="Enable blog" hint="Show the blog on the public site" />
                </div>
            </section>

            <button type="submit" :disabled="form.processing" class="inline-flex items-center gap-1.5 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-700 disabled:opacity-60"><Save class="h-4 w-4" /> Save settings</button>
        </form>
    </AppLayout>
</template>
