<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { 
    LayoutGrid, 
    ClipboardList,
    CheckCircle2
} from 'lucide-vue-next';
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
import { type NavItem } from '@/types';
import AppLogo from './AppLogo.vue';

const newTasksCount = 2; // Would come from backend

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/developer/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'My Tasks',
        href: '/developer/tasks',
        icon: ClipboardList,
        badge: newTasksCount > 0 ? newTasksCount.toString() : undefined,
    },
    {
        title: 'Completed Tasks',
        href: '/developer/completed',
        icon: CheckCircle2,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset" class="bg-white border-r border-[#CBD5E1]">
        <SidebarHeader class="bg-[#5B21B6] border-b border-[#6D28D9]">
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child class="hover:bg-white/10">
                        <Link href="/developer/dashboard" class="flex items-center justify-center py-3">
                            <img 
                                src="/logo.png" 
                                alt="TaskHive Logo" 
                                class="h-8 w-auto object-contain max-w-full" 
                            />
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
