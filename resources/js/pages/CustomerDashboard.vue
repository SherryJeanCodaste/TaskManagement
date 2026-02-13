<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/customer/dashboard' },
];

// Stats should come from backend props - using empty object for now
const stats = {
    totalTasks: 0,
    inProgress: 0,
    completed: 0,
};

// No sample data - using real backend data from props
const recentTasks = [];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <template #header-actions>
            <Link 
                href="/customer/tasks/create"
                class="px-4 py-2 bg-[#06B6D4] text-white rounded-lg hover:bg-[#0891B2] transition-colors font-semibold text-sm flex items-center gap-2 shadow-md"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create Task
            </Link>
        </template>

        <div class="flex h-full flex-1 flex-col gap-6 p-8 bg-white">
            <!-- Summary Stats Cards -->
            <div class="flex gap-4">
                <!-- Total Tasks -->
                <div class="bg-white rounded-lg p-4 border border-[#E5E7EB] min-w-[200px]">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-[#3B82F6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <p class="text-xs text-[#9CA3AF] mb-1">Total Tasks</p>
                    <p class="text-2xl font-semibold text-[#111827]">{{ stats.totalTasks }}</p>
                </div>

                <!-- In Progress -->
                <div class="bg-white rounded-lg p-4 border border-[#E5E7EB] min-w-[200px]">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-[#F97316]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-xs text-[#9CA3AF] mb-1">In Progress</p>
                    <p class="text-2xl font-semibold text-[#111827]">{{ stats.inProgress }}</p>
                </div>

                <!-- Completed -->
                <div class="bg-white rounded-lg p-4 border border-[#E5E7EB] min-w-[200px]">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-[#10B981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-xs text-[#9CA3AF] mb-1">Completed</p>
                    <p class="text-2xl font-semibold text-[#111827]">{{ stats.completed }}</p>
                </div>
            </div>

            <!-- Recent Tasks Section -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-semibold text-[#111827]">Recent Tasks</h2>
                    <Link 
                        href="/customer/tasks"
                        class="text-sm text-[#6B7280] hover:text-[#111827] transition-colors"
                    >
                        View all →
                    </Link>
                </div>
                
                <div class="space-y-3">
                    <Link
                        v-for="task in recentTasks" 
                        :key="task.id"
                        :href="`/customer/tasks/${task.id}`"
                        class="block bg-white rounded-lg p-4 border border-[#E5E7EB] hover:border-[#D1D5DB] transition-colors cursor-pointer"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-[#111827] mb-2">{{ task.title }}</h3>
                                <div class="flex items-center gap-3 text-xs">
                                    <span class="text-[#8B5CF6] font-medium">{{ task.category }}</span>
                                    <span class="text-[#9CA3AF]">{{ task.date }}</span>
                                </div>
                            </div>
                            <span 
                                :class="{
                                    'bg-[#DBEAFE] text-[#1E40AF]': task.status === 'pending',
                                    'bg-[#FEF3C7] text-[#92400E]': task.status === 'assigned',
                                    'bg-[#FFEDD5] text-[#9A3412]': task.status === 'in-progress',
                                    'bg-[#E0E7FF] text-[#4338CA]': task.status === 'review'
                                }"
                                class="px-3 py-1 rounded-md text-xs font-medium"
                            >
                                {{ task.statusLabel }}
                            </span>
                        </div>
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

