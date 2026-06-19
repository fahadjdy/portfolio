<script setup lang="ts">
import TextField from '@/components/form/TextField.vue';
import TextareaField from '@/components/form/TextareaField.vue';
import SelectField from '@/components/form/SelectField.vue';
import ToggleField from '@/components/form/ToggleField.vue';
import RepeaterField from '@/components/form/RepeaterField.vue';
import ImageField from '@/components/form/ImageField.vue';
import TagSelect from '@/components/form/TagSelect.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Save } from 'lucide-vue-next';

interface Field {
    name: string;
    label?: string;
    type?: string;
    required?: boolean;
    placeholder?: string;
    hint?: string;
    options?: Array<{ value: any; label: string }>;
    default?: any;
    current?: string | null;
    step?: string;
    rows?: number;
}

const props = defineProps<{
    resource: { singular: string; plural: string; routeBase: string };
    item: Record<string, any> | null;
    fields: Field[];
}>();

const isEdit = !!props.item;

function initial(field: Field) {
    const val = props.item ? props.item[field.name] : undefined;
    switch (field.type) {
        case 'toggle':
            return val ?? field.default ?? false;
        case 'repeater':
        case 'tags':
            return Array.isArray(val) ? val : [];
        case 'image':
            return null;
        default:
            return val ?? '';
    }
}

const form = useForm(Object.fromEntries(props.fields.map((f) => [f.name, initial(f)])));

const breadcrumbs = [
    { title: props.resource.plural, href: route(props.resource.routeBase + '.index') },
    { title: isEdit ? 'Edit' : 'New', href: '#' },
];

function submit() {
    if (isEdit) {
        form.transform((data) => ({ ...data, _method: 'put' })).post(route(props.resource.routeBase + '.update', props.item!.id), { forceFormData: true });
    } else {
        form.post(route(props.resource.routeBase + '.store'), { forceFormData: true });
    }
}
</script>

<template>
    <Head :title="(isEdit ? 'Edit ' : 'New ') + resource.singular" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <h1 class="mb-5 text-xl font-bold text-slate-900 dark:text-white">{{ isEdit ? 'Edit' : 'New' }} {{ resource.singular }}</h1>

            <form @submit.prevent="submit" class="max-w-2xl space-y-5 rounded-xl border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-800">
                <template v-for="field in fields" :key="field.name">
                    <ToggleField v-if="field.type === 'toggle'" v-model="form[field.name]" :label="field.label" :hint="field.hint" />
                    <TextareaField v-else-if="field.type === 'textarea'" v-model="form[field.name]" :label="field.label" :rows="field.rows" :required="field.required" :placeholder="field.placeholder" :hint="field.hint" :error="form.errors[field.name]" />
                    <SelectField v-else-if="field.type === 'select'" v-model="form[field.name]" :options="field.options || []" :label="field.label" :required="field.required" :placeholder="field.placeholder" :error="form.errors[field.name]" />
                    <RepeaterField v-else-if="field.type === 'repeater'" v-model="form[field.name]" :label="field.label" :placeholder="field.placeholder" :hint="field.hint" />
                    <TagSelect v-else-if="field.type === 'tags'" v-model="form[field.name]" :options="field.options || []" :label="field.label" />
                    <ImageField v-else-if="field.type === 'image'" v-model="form[field.name]" :label="field.label" :current="field.current" :hint="field.hint" :error="form.errors[field.name]" />
                    <TextField v-else v-model="form[field.name]" :type="field.type === 'number' ? 'number' : field.type === 'date' ? 'date' : 'text'" :label="field.label" :required="field.required" :placeholder="field.placeholder" :hint="field.hint" :error="form.errors[field.name]" />
                </template>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" :disabled="form.processing" class="inline-flex items-center gap-1.5 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-700 disabled:opacity-60">
                        <Save class="h-4 w-4" /> {{ isEdit ? 'Save changes' : 'Create' }}
                    </button>
                    <Link :href="route(resource.routeBase + '.index')" class="text-sm font-medium text-slate-500 hover:text-slate-700">Cancel</Link>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
