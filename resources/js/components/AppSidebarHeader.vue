<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import type { BreadcrumbItem } from '@/types';
import { usePage, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItem[];
    }>(),
    {
        breadcrumbs: () => [],
    },
);

const page = usePage();
const showNotifications = ref(false);

const notifications = computed(() => page.props.notifications || []);
const unreadCount = computed(() => notifications.value.length);
const user = computed(() => page.props.auth?.user);

const roleDisplay = computed(() => {
    const role = user.value?.role;
    if (role === 'frontend_developer') return 'Frontend Developer';
    if (role === 'backend_developer') return 'Backend Developer';
    if (role === 'server_administrator') return 'Server Administrator';
    return 'Customer';
});

const markAsReadAndNavigate = async (notification: any) => {
    // Close dropdown first
    showNotifications.value = false;
    
    try {
        // Mark as read based on type
        if (notification.type === 'comment') {
            await fetch(`/comments/${notification.id}/mark-read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                },
            });
            
            // Force reload to update notification count, then navigate
            router.reload({
                only: ['notifications'],
                onSuccess: () => {
                    // After reload, navigate to the comment
                    router.visit(`/tasks/${notification.task_id}#comment-${notification.id}`, {
                        preserveState: false,
                        onSuccess: () => {
                            setTimeout(() => {
                                const element = document.getElementById(`comment-${notification.id}`);
                                if (element) {
                                    element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                }
                            }, 100);
                        }
                    });
                }
            });
        } else if (notification.type === 'attachment') {
            await fetch(`/attachments/${notification.id}/mark-read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                },
            });
            
            // Force reload to update notification count, then navigate
            router.reload({
                only: ['notifications'],
                onSuccess: () => {
                    // After reload, navigate to the task
                    router.visit(`/tasks/${notification.task_id}`, {
                        preserveState: false,
                    });
                }
            });
        }
    } catch (error) {
        console.error('Error marking notification as read:', error);
    }
};

const getNotificationIcon = (notification: any) => {
    if (notification.type === 'comment') {
        return 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z';
    } else if (notification.type === 'attachment') {
        return 'M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13';
    }
    return '';
};

const getNotificationText = (notification: any) => {
    if (notification.type === 'comment') {
        return notification.comment;
    } else if (notification.type === 'attachment') {
        return `Submitted work: ${notification.attachment_name}`;
    }
    return '';
};

const getNotificationLabel = (notification: any) => {
    if (notification.type === 'comment') {
        return 'commented on';
    } else if (notification.type === 'attachment') {
        return 'submitted work for';
    }
    return '';
};
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
            <!-- Notifications -->
            <div class="relative">
                <button 
                    @click="showNotifications = !showNotifications"
                    class="relative p-2 text-[#1E293B] hover:bg-[#F9FAFB] rounded-lg transition-colors"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <span v-if="unreadCount > 0" class="absolute top-1 right-1 w-5 h-5 bg-[#EF4444] rounded-full text-white text-xs flex items-center justify-center font-semibold">
                        {{ unreadCount > 9 ? '9+' : unreadCount }}
                    </span>
                </button>

                <!-- Notifications Dropdown -->
                <div 
                    v-if="showNotifications"
                    @click.stop
                    class="absolute right-0 mt-2 w-96 bg-white rounded-xl border border-[#CBD5E1] shadow-lg z-50"
                >
                    <div class="p-4 border-b border-[#E2E8F0]">
                        <h3 class="font-bold text-[#1E293B]">Notifications</h3>
                        <p class="text-xs text-[#64748B] mt-1">{{ unreadCount }} new notification{{ unreadCount !== 1 ? 's' : '' }}</p>
                    </div>
                    
                    <div class="max-h-96 overflow-y-auto">
                        <div v-if="notifications.length > 0" class="divide-y divide-[#E2E8F0]">
                            <button 
                                v-for="notification in notifications" 
                                :key="`${notification.type}-${notification.id}`"
                                @click="markAsReadAndNavigate(notification)"
                                class="block w-full text-left p-4 hover:bg-[#F9FAFB] transition-colors"
                            >
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-full bg-[#06B6D4]/10 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-[#06B6D4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="getNotificationIcon(notification)"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <p class="font-semibold text-[#1E293B] text-sm">{{ notification.user.name }}</p>
                                            <span class="text-xs text-[#64748B]">{{ getNotificationLabel(notification) }}</span>
                                        </div>
                                        <p class="text-xs text-[#5B21B6] font-medium mb-1">{{ notification.task?.title }}</p>
                                        <p class="text-sm text-[#64748B] line-clamp-2">{{ getNotificationText(notification) }}</p>
                                        <p class="text-xs text-[#94A3B8] mt-1">{{ new Date(notification.created_at).toLocaleString() }}</p>
                                    </div>
                                </div>
                            </button>
                        </div>
                        <div v-else class="p-8 text-center text-[#94A3B8]">
                            <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <p class="text-sm">No new notifications</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- User info badge -->
            <div class="hidden md:flex items-center gap-2 px-3 py-1.5 bg-[#F9FAFB] rounded-lg border border-[#CBD5E1]">
                <div class="w-2 h-2 bg-[#22C55E] rounded-full"></div>
                <span class="text-sm font-medium text-[#1E293B]">{{ roleDisplay }}</span>
            </div>
        </div>
    </header>
</template>
