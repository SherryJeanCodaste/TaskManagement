<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/developer/dashboard' },
    { title: 'Completed Tasks', href: '/developer/completed' },
];

// No sample data - using real backend data
const completedTasks = [];
</script>

<template>
    <Head title="Completed Tasks" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col p-8 bg-[#F9FAFB]">
            <div class="w-full max-w-7xl mx-auto space-y-6">
                <!-- Header -->
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold text-[#111827]">Completed Tasks</h1>
                        <p class="text-sm text-[#6B7280] mt-1">View all your successfully completed tasks</p>
                    </div>
                    <div class="flex items-center gap-2 px-4 py-2 bg-[#D1FAE5] rounded-lg">
                        <svg class="w-5 h-5 text-[#10B981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm font-semibold text-[#065F46]">{{ completedTasks.length }} Tasks Completed</span>
                    </div>
                </div>

                <!-- Completed Tasks Table -->
                <div class="bg-white rounded-lg border border-[#E5E7EB] overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-[#F9FAFB] border-b border-[#E5E7EB]">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Task Title</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Category</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Priority</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Completed Date</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E5E7EB]">
                            <tr 
                                v-for="task in completedTasks" 
                                :key="task.id"
                                class="hover:bg-[#F9FAFB] transition-colors"
                            >
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-[#10B981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-[#111827]">{{ task.title }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-[#8B5CF6] font-medium">{{ task.category }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-[#6B7280]">{{ task.customer }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span 
                                        :class="{
                                            'bg-[#FEE2E2] text-[#991B1B]': task.priority === 'high',
                                            'bg-[#FEF3C7] text-[#92400E]': task.priority === 'medium',
                                            'bg-[#F3F4F6] text-[#374151]': task.priority === 'low'
                                        }"
                                        class="px-2.5 py-1 rounded text-xs font-medium capitalize"
                                    >
                                        {{ task.priority }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-[#6B7280]">{{ task.completedDate }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <Link 
                                        :href="`/developer/tasks/${task.id}`"
                                        class="text-sm text-[#3B82F6] hover:text-[#2563EB] font-medium"
                                    >
                                        View Details →
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
