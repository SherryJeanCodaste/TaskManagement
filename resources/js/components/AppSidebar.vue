<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { 
    LayoutGrid, 
    FolderKanban, 
    Users, 
    ClipboardList, 
    BarChart3,
    Trash2,
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
import { computed } from 'vue';
import AppLogo from './AppLogo.vue';

const page = usePage();
const user = computed(() => page.props.auth?.user);

// Filter navigation items based on user role
const mainNavItems = computed<NavItem[]>(() => {
    const allItems: NavItem[] = [
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
            title: 'Tasks',
            href: '/tasks',
            icon: ClipboardList,
        },
    ];

    // Add Trash for customers only
    if (user.value?.role === 'customer') {
        allItems.push({
            title: 'Trash',
            href: '/projects-trash',
            icon: Trash2,
        });
    }

    // Only show Users and Reports for admin (you can add admin role later)
    // For now, hide them for all users
    // If you want to show them for specific roles, uncomment and modify:
    // if (user.value?.role === 'admin') {
    //     allItems.push(
    //         {
    //             title: 'Users',
    //             href: '/users',
    //             icon: Users,
    //         },
    //         {
    //             title: 'Reports',
    //             href: '/reports',
    //             icon: BarChart3,
    //         }
    //     );
    // }

    return allItems;
});

const footerNavItems: NavItem[] = [];

// Get user role display name
const userRoleDisplay = computed(() => {
    const role = user.value?.role;
    if (role === 'customer') return 'Customer';
    if (role === 'frontend_developer') return 'Frontend Developer';
    if (role === 'backend_developer') return 'Backend Developer';
    if (role === 'server_administrator') return 'Server Administrator';
    return 'User';
});
</script>

<template>
    <Sidebar collapsible="icon" variant="inset" class="bg-white border-r border-[#CBD5E1]">
        <SidebarHeader class="bg-[#5B21B6] border-b border-[#CBD5E1]">
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child class="hover:bg-white/10">
                        <Link :href="dashboard()" class="flex items-center gap-2 text-white">
                            <!-- Basket with checkmark icon -->
                            <svg class="w-8 h-8 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <!-- Basket body -->
                                <path d="M5 9L4 19C4 19.5523 4.44772 20 5 20H19C19.5523 20 20 19.5523 20 19L19 9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <!-- Left handle -->
                                <path d="M7 9L9 4C9.26522 3.40973 9.85395 3 10.5 3H10.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <!-- Right handle -->
                                <path d="M17 9L15 4C14.7348 3.40973 14.1461 3 13.5 3H13.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <!-- Handle bar -->
                                <path d="M10.5 3H13.5" stroke-width="2" stroke-linecap="round"/>
                                <!-- Bottom line -->
                                <path d="M5 9H19" stroke-width="2" stroke-linecap="round"/>
                                <!-- Checkmark -->
                                <path d="M9 13L11 15L15 11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <div class="flex flex-col">
                                <span class="font-bold text-lg">TASKet</span>
                                <span class="text-xs text-white/80">{{ userRoleDisplay }}</span>
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
