<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

// Mock data - replace with actual props from backend
const stats = {
    totalProjects: 5,
    totalCustomers: 5,
    totalEmployees: 12,
    totalTasks: 48,
    pendingTasks: 15,
    completedTasks: 33,
    overdueTasks: 3,
};

const recentActivity = [
    { id: 1, customer: 'ABC Corp', task: 'Design Homepage', category: 'Frontend', project: 'E-Commerce Platform', status: 'pending', createdAt: '2 hours ago' },
    { id: 2, customer: 'XYZ Ltd', task: 'Setup API Endpoints', category: 'Backend', project: 'Mobile App', status: 'in-progress', createdAt: '5 hours ago' },
    { id: 3, customer: 'Tech Solutions', task: 'Configure Server', category: 'Server', project: 'CRM System', status: 'pending', createdAt: '1 day ago' },
    { id: 4, customer: 'ABC Corp', task: 'Payment Integration', category: 'Backend', project: 'E-Commerce Platform', status: 'pending', createdAt: '1 day ago' },
];

const alerts = [
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col p-8 bg-white">
            <div class="w-full max-w-5xl mx-auto space-y-6">
                <!-- Welcome Header -->
                <div class="bg-[#5B21B6] rounded-lg p-5 text-white">
                    <h1 class="text-xl font-semibold mb-1">Welcome back, System Admin!</h1>
                    
                </div>

                <!-- Alerts / Notifications -->
                <div class="flex gap-4">
                    <div v-for="alert in alerts" :key="alert.id" 
                        :class="{
                            'bg-[#FEE2E2] border-[#EF4444]': alert.type === 'overdue',
                            'bg-[#DBEAFE] border-[#3B82F6]': alert.type === 'info'
                        }"
                        class="flex items-center gap-3 p-4 rounded-lg border flex-1">
                        <div v-if="alert.icon === 'alert'" 
                            :class="{
                                'text-[#EF4444]': alert.type === 'overdue',
                                'text-[#3B82F6]': alert.type === 'info'
                            }">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div v-if="alert.icon === 'user'" class="text-[#3B82F6]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-[#111827]">{{ alert.message }}</span>
                    </div>
                </div>

                <!-- Summary Stats Cards -->
                <div class="flex gap-6">
                    <!-- Total Projects -->
                    <div class="bg-white rounded-lg p-5 border border-[#E5E7EB] w-[250px]">
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-5 h-5 text-[#5B21B6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                            </svg>
                        </div>
                        <p class="text-xs text-[#9CA3AF] mb-1">Total Projects</p>
                        <p class="text-3xl font-semibold text-[#111827]">{{ stats.totalProjects }}</p>
                    </div>

                    <!-- Total Customers -->
                    <div class="bg-white rounded-lg p-5 border border-[#E5E7EB] w-[250px]">
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-5 h-5 text-[#3B82F6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <p class="text-xs text-[#9CA3AF] mb-1">Total Customers</p>
                        <p class="text-3xl font-semibold text-[#111827]">{{ stats.totalCustomers }}</p>
                    </div>

                    <!-- Total Employees -->
                    <div class="bg-white rounded-lg p-5 border border-[#E5E7EB] w-[250px]">
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-5 h-5 text-[#10B981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <p class="text-xs text-[#9CA3AF] mb-1">Total Employees</p>
                        <p class="text-3xl font-semibold text-[#111827]">{{ stats.totalEmployees }}</p>
                    </div>

                    <!-- Total Tasks -->
                    <div class="bg-white rounded-lg p-5 border border-[#E5E7EB] w-[250px]">
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-5 h-5 text-[#F97316]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                        <p class="text-xs text-[#9CA3AF] mb-1">Total Tasks</p>
                        <p class="text-3xl font-semibold text-[#111827]">{{ stats.totalTasks }}</p>
                    </div>
                </div>

                <!-- Recent Activity Table -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-base font-semibold text-[#111827]">Recent Activity</h2>
                    </div>
                    
                    <div class="space-y-0 border border-[#E5E7EB] rounded-lg overflow-hidden">
                        <Link 
                            v-for="(activity, index) in recentActivity" 
                            :key="activity.id" 
                            :href="`/tasks/${activity.id}`"
                            :class="{ 'border-t border-[#E5E7EB]': index > 0 }"
                            class="block bg-[#FAFAFA] p-4 hover:bg-[#F5F5F5] transition-colors cursor-pointer"
                        >
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h3 class="text-sm font-medium text-[#111827] mb-2">{{ activity.task }}</h3>
                                    <div class="flex items-center gap-3 text-xs">
                                        <span class="text-[#8B5CF6] font-medium">{{ activity.customer }}</span>
                                        <span class="text-[#9CA3AF]">{{ activity.project }}</span>
                                        <span class="text-[#9CA3AF]">{{ activity.createdAt }}</span>
                                    </div>
                                </div>
                                <span 
                                    :class="{
                                        'bg-[#DBEAFE] text-[#1E40AF]': activity.status === 'pending',
                                        'bg-[#FFEDD5] text-[#9A3412]': activity.status === 'in-progress'
                                    }"
                                    class="px-3 py-1 rounded-md text-xs font-medium"
                                >
                                    {{ activity.status.replace('-', ' ') }}
                                </span>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
