<script setup lang="ts">
import FlashToast from '@/components/FlashToast.vue';
import { useAppearance } from '@/composables/useAppearance';
import { Link, router, usePage } from '@inertiajs/vue3';
import {
    LayoutGrid, Inbox, FolderKanban, Wrench, Briefcase, GraduationCap, Tags,
    HandHelping, Quote, Share2, FileText, FileDown, Settings, Hammer, Newspaper,
    FolderTree, Menu, X, Sun, Moon, LogOut, User, KeyRound, ExternalLink, ChevronDown,
} from 'lucide-vue-next';
import { computed, onMounted, ref } from 'vue';

const props = defineProps<{ breadcrumbs?: Array<{ title: string; href: string }> }>();

const page = usePage<any>();
const sidebarOpen = ref(false);
const userMenu = ref(false);
const dark = ref(false);
const { updateAppearance } = useAppearance();

onMounted(() => (dark.value = document.documentElement.classList.contains('dark')));
function toggleTheme() {
    dark.value = !dark.value;
    updateAppearance(dark.value ? 'dark' : 'light');
}
function logout() {
    router.post(route('logout'));
}

const user = computed(() => page.props.auth?.user);
const branding = computed(() => page.props.branding ?? { name: 'Fahad Jadiya', logo: null });
const pageTitle = computed(() => props.breadcrumbs?.length ? props.breadcrumbs[props.breadcrumbs.length - 1].title : 'Dashboard');

const groups = [
    { label: 'Overview', items: [
        { title: 'Dashboard', href: route('dashboard'), icon: LayoutGrid },
        { title: 'Leads', href: route('admin.leads.index'), icon: Inbox, badge: 'leads' },
    ] },
    { label: 'Portfolio', items: [
        { title: 'Projects', href: route('admin.projects.index'), icon: FolderKanban },
        { title: 'Skill Categories', href: route('admin.skill-categories.index'), icon: FolderTree },
        { title: 'Skills', href: route('admin.skills.index'), icon: Wrench },
        { title: 'Experience', href: route('admin.experiences.index'), icon: Briefcase },
        { title: 'Education', href: route('admin.education.index'), icon: GraduationCap },
        { title: 'Services', href: route('admin.services.index'), icon: HandHelping },
        { title: 'Testimonials', href: route('admin.testimonials.index'), icon: Quote },
        { title: 'Tech Stack', href: route('admin.tech-tags.index'), icon: Tags },
    ] },
    { label: 'Blog', items: [
        { title: 'Posts', href: route('admin.blog-posts.index'), icon: Newspaper },
        { title: 'Categories', href: route('admin.blog-categories.index'), icon: FolderTree },
    ] },
    { label: 'Site', items: [
        { title: 'Pages', href: route('admin.pages.index'), icon: FileText },
        { title: 'Social Links', href: route('admin.social-links.index'), icon: Share2 },
        { title: 'Resume / CV', href: route('admin.resumes.index'), icon: FileDown },
        { title: 'Settings', href: route('admin.settings.index'), icon: Settings },
        { title: 'Maintenance', href: route('admin.tools.index'), icon: Hammer },
    ] },
];

function isActive(href: string): boolean {
    try {
        const path = new URL(href, window.location.origin).pathname;
        return page.url === path || page.url.startsWith(path + '/');
    } catch {
        return false;
    }
}
function badge(key?: string): number {
    return key === 'leads' ? (page.props.unreadLeads ?? 0) : 0;
}
</script>

<template>
    <div class="min-h-screen bg-slate-50 text-slate-800 dark:bg-slate-950 dark:text-slate-200">
        <FlashToast />

        <!-- Mobile backdrop -->
        <div v-if="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-30 bg-slate-900/50 lg:hidden"></div>

        <!-- Sidebar -->
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed inset-y-0 left-0 z-40 flex w-64 flex-col border-r border-slate-200 bg-white transition-transform dark:border-slate-800 dark:bg-slate-900"
        >
            <div class="flex h-16 items-center gap-2 border-b border-slate-200 px-5 dark:border-slate-800">
                <Link :href="route('dashboard')" class="flex items-center gap-2">
                    <img v-if="branding.logo" :src="branding.logo" :alt="branding.name" class="h-9 w-auto max-w-[150px] object-contain" />
                    <template v-else>
                        <span class="grid h-9 w-9 place-items-center rounded-lg bg-brand-600 text-sm font-bold text-white">FJ</span>
                        <div class="leading-tight">
                            <p class="font-display text-sm font-bold text-slate-900 dark:text-white">{{ branding.name }}</p>
                            <p class="text-xs text-slate-400">Admin panel</p>
                        </div>
                    </template>
                </Link>
                <button @click="sidebarOpen = false" class="ml-auto text-slate-400 lg:hidden"><X class="h-5 w-5" /></button>
            </div>

            <nav class="flex-1 space-y-6 overflow-y-auto px-3 py-5">
                <div v-for="group in groups" :key="group.label">
                    <p class="px-3 pb-1.5 text-[11px] font-semibold uppercase tracking-wider text-slate-400">{{ group.label }}</p>
                    <ul class="space-y-0.5">
                        <li v-for="item in group.items" :key="item.title">
                            <Link :href="item.href" @click="sidebarOpen = false"
                                :class="isActive(item.href)
                                    ? 'bg-brand-50 text-brand-700 dark:bg-brand-600/15 dark:text-brand-300'
                                    : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800'"
                                class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition">
                                <component :is="item.icon" class="h-4 w-4 shrink-0" />
                                <span class="flex-1">{{ item.title }}</span>
                                <span v-if="badge(item.badge) > 0" class="rounded-full bg-brand-600 px-1.5 py-0.5 text-[10px] font-semibold text-white">{{ badge(item.badge) }}</span>
                            </Link>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="border-t border-slate-200 p-3 dark:border-slate-800">
                <a href="/" target="_blank" rel="noopener" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">
                    <ExternalLink class="h-4 w-4" /> View live site
                </a>
            </div>
        </aside>

        <!-- Main -->
        <div class="lg:pl-64">
            <header class="sticky top-0 z-20 flex h-16 items-center gap-3 border-b border-slate-200 bg-white/80 px-4 backdrop-blur dark:border-slate-800 dark:bg-slate-900/80 sm:px-6">
                <button @click="sidebarOpen = true" class="text-slate-500 lg:hidden"><Menu class="h-6 w-6" /></button>
                <h1 class="font-display text-lg font-bold text-slate-900 dark:text-white">{{ pageTitle }}</h1>

                <div class="ml-auto flex items-center gap-2">
                    <button @click="toggleTheme" aria-label="Toggle theme"
                        class="grid h-9 w-9 place-items-center rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                        <Sun v-if="dark" class="h-4 w-4" />
                        <Moon v-else class="h-4 w-4" />
                    </button>

                    <div class="relative">
                        <button @click="userMenu = !userMenu" class="flex items-center gap-2 rounded-lg border border-slate-200 px-2.5 py-1.5 text-sm font-medium text-slate-700 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                            <span class="grid h-6 w-6 place-items-center rounded-full bg-brand-100 text-xs font-bold text-brand-700 dark:bg-brand-600/20 dark:text-brand-300">{{ (user?.name ?? 'A').charAt(0) }}</span>
                            <span class="hidden sm:block">{{ user?.name }}</span>
                            <ChevronDown class="h-4 w-4 text-slate-400" />
                        </button>
                        <div v-if="userMenu" @click="userMenu = false" class="fixed inset-0 z-10"></div>
                        <div v-if="userMenu" class="absolute right-0 z-20 mt-2 w-48 overflow-hidden rounded-lg border border-slate-200 bg-white py-1 shadow-lg dark:border-slate-700 dark:bg-slate-800">
                            <Link :href="route('profile.edit')" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-700"><User class="h-4 w-4" /> Profile</Link>
                            <Link :href="route('password.edit')" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-700"><KeyRound class="h-4 w-4" /> Password</Link>
                            <button @click="logout" class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm text-rose-600 hover:bg-slate-50 dark:hover:bg-slate-700"><LogOut class="h-4 w-4" /> Log out</button>
                        </div>
                    </div>
                </div>
            </header>

            <main class="min-h-[calc(100vh-4rem)]">
                <slot />
            </main>
        </div>
    </div>
</template>
