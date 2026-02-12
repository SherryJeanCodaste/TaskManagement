<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import AppContent from '@/components/AppContent.vue';
import AppShell from '@/components/AppShell.vue';
import AppSidebar from '@/components/AppSidebar.vue';
import CustomerSidebar from '@/components/CustomerSidebar.vue';
import DeveloperSidebar from '@/components/DeveloperSidebar.vue';
import AppSidebarHeader from '@/components/AppSidebarHeader.vue';
import type { BreadcrumbItem } from '@/types';

type Props = {
    breadcrumbs?: BreadcrumbItem[];
};

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

defineSlots<{
    'header-actions'?: () => any;
    default?: () => any;
}>();

const page = usePage();
const userRole = page.props.auth.user?.role || 'customer';
</script>

<template>
    <AppShell variant="sidebar">
        <AppSidebar v-if="userRole === 'admin'" />
        <CustomerSidebar v-else-if="userRole === 'customer'" />
        <DeveloperSidebar v-else-if="userRole === 'developer'" />
        <AppSidebar v-else />
        
        <AppContent variant="sidebar" class="overflow-x-hidden">
            <AppSidebarHeader :breadcrumbs="breadcrumbs">
                <template #actions>
                    <slot name="header-actions" />
                </template>
            </AppSidebarHeader>
            <slot />
        </AppContent>
    </AppShell>
</template>
