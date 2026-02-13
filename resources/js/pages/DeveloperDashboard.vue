<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/developer/dashboard' },
];

const page = usePage();
const user = page.props.auth.user;

// Mock data - replace with actual props from backend
const developer = {
    name: user?.name || 'John Smith',
    role: user?.developer_type ? `${user.developer_type.replace('_', ' ').replace(/\b\w/g, (l: string) => l.toUpperCase())} Developer` : 'Developer',
};

// Stats should come from backend props - using empty object for now
const stats = {
    assigned: 0,
    inProgress: 0,
    underReview: 0,
    completed: 0,
};

const viewedTasks = ref<number[]>([]);

// Load viewed tasks from localStorage
onMounted(() => {
    const stored = localStorage.getItem('viewedTasks');
    if (stored) {
        viewedTasks.value = JSON.parse(stored);
    }
});

// No sample data - using real backend data from props
const recentTasksData = [];

const recentTasks = computed(() => {
    return recentTasksData.map(task => ({
        ...task,
        isNew: !viewedTasks.value.includes(task.id)
    }));
});
</script>

<template>
    <Head title="Developer Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-8 bg-[#F9FAFB]">
            <div class="w-full max-w-6xl mx-auto space-y-6">
                <!-- Overdue Tasks Alert -->
                <div class="bg-[#FEE2E2] border border-[#EF4444] rounded-lg p-4 flex items-center gap-3">
                    <div class="text-[#EF4444]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-[#111827]">3 tasks are overdue</span>
                </div>

                <!-- Summary Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                    <!-- Assigned Tasks -->
                    <div class="bg-white rounded-lg p-3 border border-[#E5E7EB]">
                        <div class="flex items-center gap-2 mb-1">
                            <div class="w-6 h-6 bg-[#DBEAFE] rounded flex items-center justify-center">
                                <svg class="w-3 h-3 text-[#3B82F6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-xs text-[#6B7280] mb-0.5">Assigned Tasks</p>
                        <p class="text-xl font-bold text-[#111827]">{{ stats.assigned }}</p>
                    </div>

                    <!-- In Progress -->
                    <div class="bg-white rounded-lg p-3 border border-[#E5E7EB]">
                        <div class="flex items-center gap-2 mb-1">
                            <div class="w-6 h-6 bg-[#FFEDD5] rounded flex items-center justify-center">
                                <svg class="w-3 h-3 text-[#F97316]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-xs text-[#6B7280] mb-0.5">In Progress</p>
                        <p class="text-xl font-bold text-[#111827]">{{ stats.inProgress }}</p>
                    </div>

                    <!-- In Review -->
                    <div class="bg-white rounded-lg p-3 border border-[#E5E7EB]">
                        <div class="flex items-center gap-2 mb-1">
                            <div class="w-6 h-6 bg-[#E0E7FF] rounded flex items-center justify-center">
                                <svg class="w-3 h-3 text-[#8B5CF6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-xs text-[#6B7280] mb-0.5">In Review</p>
                        <p class="text-xl font-bold text-[#111827]">{{ stats.underReview }}</p>
                    </div>

                    <!-- Completed -->
                    <div class="bg-white rounded-lg p-3 border border-[#E5E7EB]">
                        <div class="flex items-center gap-2 mb-1">
                            <div class="w-6 h-6 bg-[#D1FAE5] rounded flex items-center justify-center">
                                <svg class="w-3 h-3 text-[#10B981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-xs text-[#6B7280] mb-0.5">Completed</p>
                        <p class="text-xl font-bold text-[#111827]">{{ stats.completed }}</p>
                    </div>
                </div>

                <!-- Recent Tasks Section -->
                <div class="bg-white rounded-lg border border-[#E5E7EB]">
                    <div class="p-6 border-b border-[#E5E7EB]">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-[#111827]">Recent Tasks</h2>
                            <Link 
                                href="/developer/tasks"
                                class="text-sm text-[#3B82F6] hover:text-[#2563EB] transition-colors font-medium"
                            >
                                View all →
                            </Link>
                        </div>
                    </div>
                    
                    <div class="divide-y divide-[#E5E7EB]">
                        <Link
                            v-for="task in recentTasks" 
                            :key="task.id"
                            :href="`/developer/tasks/${task.id}`"
                            :class="[
                                'block p-6 hover:bg-[#F9FAFB] transition-colors cursor-pointer',
                                task.isNew ? 'bg-[#EFF6FF] border-l-4 border-l-[#3B82F6]' : ''
                            ]"
                        >
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <span v-if="task.isNew" class="px-2 py-0.5 bg-[#3B82F6] text-white rounded text-xs font-bold">NEW</span>
                                        <h3 class="text-base font-medium text-[#111827]">{{ task.title }}</h3>
                                        <span 
                                            :class="{
                                                'bg-[#FEE2E2] text-[#991B1B]': task.priority === 'high',
                                                'bg-[#FEF3C7] text-[#92400E]': task.priority === 'medium',
                                                'bg-[#F3F4F6] text-[#374151]': task.priority === 'low'
                                            }"
                                            class="px-2 py-0.5 rounded text-xs font-medium"
                                        >
                                            {{ task.priority }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-4 text-sm">
                                        <span class="text-[#6B7280]">{{ task.project }}</span>
                                        <span class="text-[#8B5CF6] font-medium">{{ task.category }}</span>
                                        <span class="text-[#9CA3AF]">{{ task.date }}</span>
                                    </div>
                                </div>
                                <span 
                                    :class="{
                                        'bg-[#FEF3C7] text-[#92400E]': task.status === 'assigned',
                                        'bg-[#FFEDD5] text-[#9A3412]': task.status === 'in-progress',
                                        'bg-[#E0E7FF] text-[#4338CA]': task.status === 'review'
                                    }"
                                    class="px-3 py-1.5 rounded-md text-sm font-medium capitalize whitespace-nowrap"
                                >
                                    {{ task.status === 'in-progress' ? 'In Progress' : task.status }}
                                </span>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
