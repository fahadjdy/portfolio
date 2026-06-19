<script setup lang="ts">
import {
    Sidebar, SidebarContent, SidebarFooter, SidebarGroup, SidebarGroupLabel,
    SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem,
} from '@/components/ui/sidebar';
import NavUser from '@/components/NavUser.vue';
import { Link, usePage } from '@inertiajs/vue3';
import {
    LayoutGrid, Inbox, FolderKanban, Wrench, Briefcase, GraduationCap,
    Tags, HandHelping, Quote, Share2, FileText, FileDown, Settings, ExternalLink,
} from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const page = usePage<any>();

const groups = [
    {
        label: 'Overview',
        items: [
            { title: 'Dashboard', href: route('dashboard'), icon: LayoutGrid },
            { title: 'Leads', href: route('admin.leads.index'), icon: Inbox, badge: 'leads' },
        ],
    },
    {
        label: 'Portfolio',
        items: [
            { title: 'Projects', href: route('admin.projects.index'), icon: FolderKanban },
            { title: 'Skill Categories', href: route('admin.skill-categories.index'), icon: Wrench },
            { title: 'Skills', href: route('admin.skills.index'), icon: Wrench },
            { title: 'Experience', href: route('admin.experiences.index'), icon: Briefcase },
            { title: 'Education', href: route('admin.education.index'), icon: GraduationCap },
            { title: 'Services', href: route('admin.services.index'), icon: HandHelping },
            { title: 'Testimonials', href: route('admin.testimonials.index'), icon: Quote },
            { title: 'Tech Stack', href: route('admin.tech-tags.index'), icon: Tags },
        ],
    },
    {
        label: 'Site',
        items: [
            { title: 'Pages', href: route('admin.pages.index'), icon: FileText },
            { title: 'Social Links', href: route('admin.social-links.index'), icon: Share2 },
            { title: 'Resume / CV', href: route('admin.resumes.index'), icon: FileDown },
            { title: 'Settings', href: route('admin.settings.index'), icon: Settings },
        ],
    },
];

function isActive(href: string): boolean {
    try {
        const path = new URL(href, window.location.origin).pathname;
        return page.url === path || page.url.startsWith(path + '/');
    } catch {
        return false;
    }
}

function badgeCount(key?: string): number {
    return key === 'leads' ? (page.props.unreadLeads ?? 0) : 0;
}
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')"><AppLogo /></Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <SidebarGroup v-for="group in groups" :key="group.label" class="px-2 py-0">
                <SidebarGroupLabel>{{ group.label }}</SidebarGroupLabel>
                <SidebarMenu>
                    <SidebarMenuItem v-for="item in group.items" :key="item.title">
                        <SidebarMenuButton as-child :is-active="isActive(item.href)" :tooltip="item.title">
                            <Link :href="item.href" class="flex items-center">
                                <component :is="item.icon" />
                                <span>{{ item.title }}</span>
                                <span
                                    v-if="badgeCount(item.badge) > 0"
                                    class="ml-auto rounded-full bg-brand-600 px-1.5 py-0.5 text-[10px] font-semibold text-white"
                                >{{ badgeCount(item.badge) }}</span>
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarGroup>
        </SidebarContent>

        <SidebarFooter>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton as-child tooltip="View live site">
                        <a href="/" target="_blank" rel="noopener"><ExternalLink /><span>View live site</span></a>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
