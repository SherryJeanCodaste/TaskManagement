<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { 
    LayoutGrid, 
    FolderKanban, 
    Users, 
    ClipboardList, 
    BarChart3,
    LogOut 
} from 'lucide-vue-next';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { type NavItem } from '@/types';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Projects',
        href: '/projects',
        icon: FolderKanban,
    },
    {
        title: 'Users',
        href: '/users',
        icon: Users,
    },
    {
        title: 'Tasks',
        href: '/tasks',
        icon: ClipboardList,
    },
    {
        title: 'Reports',
        href: '/reports',
        icon: BarChart3,
    },
];

const footerNavItems: NavItem[] = [];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset" class="bg-white border-r border-[#CBD5E1]">
        <SidebarHeader class="bg-[#5B21B6] border-b border-[#CBD5E1]">
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child class="hover:bg-white/10">
                        <Link :href="dashboard()" class="flex items-center gap-3 text-white">
                            <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                                <AppLogo class="text-white" />
                            </div>
                            <div class="flex flex-col">
                                <span class="font-bold text-base">TaskHive</span>
                                <span class="text-xs text-white/80">System Admin</span>
                            </div>
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent class="bg-white">
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter class="bg-white border-t border-[#CBD5E1]">
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
