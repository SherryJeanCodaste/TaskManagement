<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
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
    { id: 1, type: 'overdue', message: '3 tasks are overdue', icon: 'alert' },
    { id: 2, type: 'info', message: 'New employee added: Alice Brown', icon: 'user' },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6 bg-[#F9FAFB]">
            <!-- Welcome Header -->
            <div class="bg-[#5B21B6] rounded-2xl p-8 text-white shadow-lg">
                <h1 class="text-3xl font-bold mb-2">Welcome back, System Admin!</h1>
                <p class="text-white/90">Here's what's happening with your projects today.</p>
            </div>

            <!-- Alerts / Notifications -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div v-for="alert in alerts" :key="alert.id" 
                    :class="{
                        'bg-[#EF4444]/10 border-[#EF4444]': alert.type === 'overdue',
                        'bg-[#06B6D4]/10 border-[#06B6D4]': alert.type === 'info'
                    }"
                    class="flex items-center gap-3 p-4 rounded-lg border-l-4">
                    <div v-if="alert.icon === 'alert'" 
                        :class="{
                            'bg-[#EF4444] text-white': alert.type === 'overdue',
                            'bg-[#06B6D4] text-white': alert.type === 'info'
                        }"
                        class="w-10 h-10 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div v-if="alert.icon === 'user'" 
                        class="w-10 h-10 bg-[#06B6D4] text-white rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <span class="font-medium text-[#1E293B]">{{ alert.message }}</span>
                </div>
            </div>

            <!-- Summary Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Projects -->
                <div class="bg-white rounded-xl p-6 border border-[#CBD5E1] shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-[#1E293B] font-medium">Total Projects</p>
                            <p class="text-3xl font-bold text-[#5B21B6] mt-2">{{ stats.totalProjects }}</p>
                        </div>
                        <div class="w-12 h-12 bg-[#5B21B6]/10 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-[#5B21B6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Customers -->
                <div class="bg-white rounded-xl p-6 border border-[#CBD5E1] shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-[#1E293B] font-medium">Total Customers</p>
                            <p class="text-3xl font-bold text-[#06B6D4] mt-2">{{ stats.totalCustomers }}</p>
                        </div>
                        <div class="w-12 h-12 bg-[#06B6D4]/10 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-[#06B6D4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Employees -->
                <div class="bg-white rounded-xl p-6 border border-[#CBD5E1] shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-[#1E293B] font-medium">Total Employees</p>
                            <p class="text-3xl font-bold text-[#22C55E] mt-2">{{ stats.totalEmployees }}</p>
                            <p class="text-xs text-[#1E293B]/60 mt-1">FE / BE / SA</p>
                        </div>
                        <div class="w-12 h-12 bg-[#22C55E]/10 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-[#22C55E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Tasks -->
                <div class="bg-white rounded-xl p-6 border border-[#CBD5E1] shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-[#1E293B] font-medium">Total Tasks</p>
                            <p class="text-3xl font-bold text-[#F97316] mt-2">{{ stats.totalTasks }}</p>
                            <div class="flex gap-2 mt-1 text-xs">
                                <span class="text-[#EF4444]">{{ stats.overdueTasks }} overdue</span>
                                <span class="text-[#F97316]">{{ stats.pendingTasks }} pending</span>
                                <span class="text-[#22C55E]">{{ stats.completedTasks }} done</span>
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-[#F97316]/10 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-[#F97316]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Table -->
            <div class="bg-white rounded-xl border border-[#CBD5E1] shadow-sm overflow-hidden">
                <div class="p-6 border-b border-[#CBD5E1]">
                    <h2 class="text-xl font-bold text-[#1E293B]">Recent Activity</h2>
                    <p class="text-sm text-[#1E293B]/60 mt-1">Latest tasks created by customers</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-[#F9FAFB] border-b border-[#CBD5E1]">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Task</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Project</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Created</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#CBD5E1]">
                            <tr v-for="activity in recentActivity" :key="activity.id" class="hover:bg-[#F9FAFB] transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-semibold text-[#1E293B]">{{ activity.customer }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-[#1E293B]">{{ activity.task }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span 
                                        :class="{
                                            'bg-[#5B21B6]/10 text-[#5B21B6]': activity.category === 'Frontend',
                                            'bg-[#06B6D4]/10 text-[#06B6D4]': activity.category === 'Backend',
                                            'bg-[#F97316]/10 text-[#F97316]': activity.category === 'Server'
                                        }"
                                        class="px-3 py-1 rounded-full text-sm font-semibold"
                                    >
                                        {{ activity.category }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-[#1E293B]">{{ activity.project }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span 
                                        :class="{
                                            'bg-[#22C55E]/10 text-[#22C55E]': activity.status === 'completed',
                                            'bg-[#F97316]/10 text-[#F97316]': activity.status === 'in-progress',
                                            'bg-[#EF4444]/10 text-[#EF4444]': activity.status === 'pending'
                                        }"
                                        class="px-3 py-1 rounded-full text-sm font-semibold capitalize"
                                    >
                                        {{ activity.status.replace('-', ' ') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-[#1E293B]/60 text-sm">{{ activity.createdAt }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
