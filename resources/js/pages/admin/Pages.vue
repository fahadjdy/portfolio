<script setup lang="ts">
import SectionEditor from '@/components/SectionEditor.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps<{ sections: any[] }>();

const breadcrumbs = [{ title: 'Pages', href: route('admin.pages.index') }];
</script>

<template>
    <Head title="Pages" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-4">
            <h1 class="text-xl font-bold text-slate-900 dark:text-white">Page content</h1>
            <SectionEditor v-for="section in sections" :key="section.id" :section="section" />
        </div>
    </AppLayout>
</template>
