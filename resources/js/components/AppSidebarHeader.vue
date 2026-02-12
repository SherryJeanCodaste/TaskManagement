<script setup lang="ts">
import { usePage, router } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import type { BreadcrumbItem } from '@/types';
import axios from 'axios';

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItem[];
    }>(),
    {
        breadcrumbs: () => [],
    },
);

defineSlots<{
    actions?: () => any;
}>();

const page = usePage();
const userRole = page.props.auth.user?.role || 'customer';

interface Notification {
    id: number;
    type: string;
    title: string;
    message: string;
    task_id: number | null;
    is_read: boolean;
    created_at: string;
}

// Notification state
const notificationCount = ref(0);
const notifications = ref<Notification[]>([]);
const showNotifications = ref(false);
const loading = ref(false);

const getRoleLabel = (role: string) => {
    switch(role) {
        case 'admin':
            return 'System Admin';
        case 'customer':
            return 'Customer';
        case 'employee':
            return 'Employee';
        case 'developer':
            return 'Developer';
        default:
            return 'User';
    }
};

const getNotificationColor = (type: string) => {
    switch(type) {
        case 'new_task':
            return 'bg-[#3B82F6]';
        case 'status_update':
            return 'bg-[#F59E0B]';
        case 'comment':
            return 'bg-[#8B5CF6]';
        case 'approved':
            return 'bg-[#10B981]';
        case 'rejected':
            return 'bg-[#EF4444]';
        default:
            return 'bg-[#6B7280]';
    }
};

const fetchNotifications = async () => {
    try {
        const response = await axios.get('/api/notifications');
        notifications.value = response.data;
    } catch (error) {
        console.error('Error fetching notifications:', error);
    }
};

const fetchUnreadCount = async () => {
    try {
        const response = await axios.get('/api/notifications/unread-count');
        notificationCount.value = response.data.count;
    } catch (error) {
        console.error('Error fetching unread count:', error);
    }
};

const toggleNotifications = async () => {
    showNotifications.value = !showNotifications.value;
    if (showNotifications.value && notifications.value.length === 0) {
        await fetchNotifications();
    }
};

const markAllAsRead = async () => {
    loading.value = true;
    try {
        await axios.post('/api/notifications/mark-all-read');
        notifications.value = notifications.value.map(n => ({ ...n, is_read: true }));
        notificationCount.value = 0;
    } catch (error) {
        console.error('Error marking all as read:', error);
    } finally {
        loading.value = false;
    }
};

const clearAll = async () => {
    loading.value = true;
    try {
        await axios.delete('/api/notifications/clear-all');
        notifications.value = [];
        notificationCount.value = 0;
    } catch (error) {
        console.error('Error clearing notifications:', error);
    } finally {
        loading.value = false;
    }
};

const handleNotificationClick = async (notification: Notification) => {
    try {
        // Mark as read
        if (!notification.is_read) {
            await axios.post(`/api/notifications/${notification.id}/read`);
            notificationCount.value = Math.max(0, notificationCount.value - 1);
        }
        
        // Close panel
        showNotifications.value = false;
        
        // Navigate to task if task_id exists
        if (notification.task_id) {
            const role = userRole;
            if (role === 'developer') {
                router.visit(`/developer/tasks/${notification.task_id}`);
            } else if (role === 'customer') {
                router.visit(`/customer/tasks/${notification.task_id}`);
            }
        }
    } catch (error) {
        console.error('Error handling notification click:', error);
    }
};

// Close notifications when clicking outside
const handleClickOutside = (event: MouseEvent) => {
    const target = event.target as HTMLElement;
    if (!target.closest('.notification-container')) {
        showNotifications.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
    fetchUnreadCount();
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
    <header
        class="flex h-16 shrink-0 items-center justify-between gap-2 border-b border-[#CBD5E1] bg-white px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4 shadow-sm"
    >
        <div class="flex items-center gap-2">
            <SidebarTrigger class="-ml-1 text-[#5B21B6] hover:bg-[#5B21B6]/10" />
            <template v-if="breadcrumbs && breadcrumbs.length > 0">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </template>
        </div>
        
        <!-- Right side header actions -->
        <div class="flex items-center gap-4">
            <!-- Custom actions slot -->
            <slot name="actions" />
            
            <!-- Notifications -->
            <div class="relative notification-container">
                <button 
                    @click="toggleNotifications"
                    class="relative p-2 text-[#1E293B] hover:bg-[#F9FAFB] rounded-lg transition-colors"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <!-- Notification count badge -->
                    <span 
                        v-if="notificationCount > 0"
                        class="absolute -top-1 -right-1 min-w-[20px] h-5 px-1.5 bg-[#EF4444] text-white text-xs font-semibold rounded-full flex items-center justify-center"
                    >
                        {{ notificationCount > 99 ? '99+' : notificationCount }}
                    </span>
                </button>

                <!-- Notification Dropdown -->
                <div 
                    v-if="showNotifications"
                    class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-[#E5E7EB] z-50"
                >
                    <div class="p-4 border-b border-[#E5E7EB]">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-semibold text-[#111827]">Notifications</h3>
                            <span class="text-xs text-[#6B7280]">{{ notificationCount }} new</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button 
                                @click="markAllAsRead"
                                class="flex-1 px-3 py-1.5 text-xs font-medium text-[#3B82F6] hover:bg-[#EFF6FF] rounded transition-colors"
                            >
                                Mark all as read
                            </button>
                            <button 
                                @click="clearAll"
                                class="flex-1 px-3 py-1.5 text-xs font-medium text-[#EF4444] hover:bg-[#FEF2F2] rounded transition-colors"
                            >
                                Clear all
                            </button>
                        </div>
                    </div>
                    <div class="max-h-96 overflow-y-auto">
                        <!-- Empty state -->
                        <div v-if="notifications.length === 0" class="p-8 text-center">
                            <svg class="w-12 h-12 text-[#9CA3AF] mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <p class="text-sm text-[#6B7280]">No notifications</p>
                        </div>

                        <!-- Notification list -->
                        <div 
                            v-for="notification in notifications" 
                            :key="notification.id"
                            @click="handleNotificationClick(notification)"
                            :class="[
                                'p-4 hover:bg-[#F9FAFB] transition-colors border-b border-[#E5E7EB] cursor-pointer',
                                !notification.is_read ? 'bg-[#EFF6FF]' : ''
                            ]"
                        >
                            <div class="flex items-start gap-3">
                                <div :class="[getNotificationColor(notification.type), 'w-2 h-2 rounded-full mt-1.5 flex-shrink-0']"></div>
                                <div class="flex-1">
                                    <p :class="['text-sm text-[#111827]', !notification.is_read ? 'font-semibold' : 'font-medium']">
                                        {{ notification.title }}
                                    </p>
                                    <p class="text-xs text-[#6B7280] mt-1">{{ notification.message }}</p>
                                    <span class="text-xs text-[#9CA3AF] mt-1 block">{{ notification.created_at }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-3 border-t border-[#E5E7EB]">
                        <button class="w-full text-sm text-[#3B82F6] hover:text-[#2563EB] font-medium text-center">
                            View all notifications
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- User info badge -->
            <div class="hidden md:flex items-center gap-2 px-3 py-1.5 bg-[#F9FAFB] rounded-lg border border-[#CBD5E1]">
                <div class="w-2 h-2 bg-[#22C55E] rounded-full"></div>
                <span class="text-sm font-medium text-[#1E293B]">{{ getRoleLabel(userRole) }}</span>
            </div>
        </div>
    </header>
</template>
