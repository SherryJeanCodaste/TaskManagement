<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Reports', href: '/reports' },
];

// Mock data for reports
const projectStats = [
    { name: 'E-Commerce Platform', tasks: 12, completed: 8, inProgress: 3, pending: 1 },
    { name: 'Mobile App', tasks: 8, completed: 3, inProgress: 3, pending: 2 },
    { name: 'CRM System', tasks: 15, completed: 15, inProgress: 0, pending: 0 },
];

const employeePerformance = [
    { name: 'John Doe', role: 'Frontend', tasksCompleted: 15, tasksInProgress: 2, efficiency: 88 },
    { name: 'Jane Smith', role: 'Backend', tasksCompleted: 18, tasksInProgress: 3, efficiency: 92 },
    { name: 'Bob Wilson', role: 'Server Admin', tasksCompleted: 12, tasksInProgress: 1, efficiency: 85 },
    { name: 'Alice Brown', role: 'Frontend', tasksCompleted: 8, tasksInProgress: 2, efficiency: 80 },
    { name: 'Charlie Davis', role: 'Backend', tasksCompleted: 10, tasksInProgress: 1, efficiency: 83 },
];
</script>

<template>
    <Head title="Reports" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6 bg-[#F9FAFB]">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-[#1E293B]">Reports & Analytics</h1>
                    <p class="text-[#1E293B] mt-1">Track performance and project statistics</p>
                </div>
                <div class="flex gap-3">
                    <button class="px-6 py-3 bg-[#5B21B6] text-white rounded-lg hover:bg-[#6D28D9] transition-colors font-semibold">
                        Export PDF
                    </button>
                    <button class="px-6 py-3 bg-[#06B6D4] text-white rounded-lg hover:bg-[#0891B2] transition-colors font-semibold">
                        Export Excel
                    </button>
                </div>
            </div>

            <!-- Overview Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl p-4 border border-[#CBD5E1] shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-[#1E293B] font-medium">Overall Completion Rate</p>
                            <p class="text-2xl font-bold text-[#22C55E] mt-1">74%</p>
                            <p class="text-xs text-[#1E293B]/60 mt-1">26/35 tasks completed</p>
                        </div>
                        <div class="w-10 h-10 bg-[#22C55E]/10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#22C55E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-4 border border-[#CBD5E1] shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-[#1E293B] font-medium">Average Efficiency</p>
                            <p class="text-2xl font-bold text-[#06B6D4] mt-1">86%</p>
                            <p class="text-xs text-[#1E293B]/60 mt-1">Across all employees</p>
                        </div>
                        <div class="w-10 h-10 bg-[#06B6D4]/10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#06B6D4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-4 border border-[#CBD5E1] shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-[#1E293B] font-medium">Active Projects</p>
                            <p class="text-2xl font-bold text-[#5B21B6] mt-1">2</p>
                            <p class="text-xs text-[#1E293B]/60 mt-1">1 completed</p>
                        </div>
                        <div class="w-10 h-10 bg-[#5B21B6]/10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#5B21B6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-4 border border-[#CBD5E1] shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-[#1E293B] font-medium">Tasks This Month</p>
                            <p class="text-2xl font-bold text-[#F97316] mt-1">35</p>
                            <p class="text-xs text-[#1E293B]/60 mt-1">+12 from last month</p>
                        </div>
                        <div class="w-10 h-10 bg-[#F97316]/10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#F97316]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Project Statistics -->
            <div class="bg-white rounded-xl border border-[#CBD5E1] shadow-sm overflow-hidden">
                <div class="p-6 border-b border-[#CBD5E1]">
                    <h2 class="text-xl font-bold text-[#1E293B]">Project Statistics</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-[#F9FAFB] border-b border-[#CBD5E1]">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Project Name</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Total Tasks</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Completed</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">In Progress</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Pending</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Completion Rate</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#CBD5E1]">
                            <tr v-for="project in projectStats" :key="project.name" class="hover:bg-[#F9FAFB] transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-semibold text-[#1E293B]">{{ project.name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 bg-[#5B21B6]/10 text-[#5B21B6] rounded-full text-sm font-semibold">
                                        {{ project.tasks }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 bg-[#22C55E]/10 text-[#22C55E] rounded-full text-sm font-semibold">
                                        {{ project.completed }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 bg-[#F97316]/10 text-[#F97316] rounded-full text-sm font-semibold">
                                        {{ project.inProgress }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 bg-[#EF4444]/10 text-[#EF4444] rounded-full text-sm font-semibold">
                                        {{ project.pending }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1 bg-[#F9FAFB] rounded-full h-2 w-32">
                                            <div 
                                                class="bg-[#22C55E] h-2 rounded-full" 
                                                :style="{ width: `${(project.completed / project.tasks) * 100}%` }"
                                            ></div>
                                        </div>
                                        <span class="text-sm font-semibold text-[#1E293B]">
                                            {{ Math.round((project.completed / project.tasks) * 100) }}%
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Employee Performance -->
            <div class="bg-white rounded-xl border border-[#CBD5E1] shadow-sm overflow-hidden">
                <div class="p-6 border-b border-[#CBD5E1]">
                    <h2 class="text-xl font-bold text-[#1E293B]">Employee Performance</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-[#F9FAFB] border-b border-[#CBD5E1]">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Employee Name</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Tasks Completed</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">In Progress</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Efficiency</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#CBD5E1]">
                            <tr v-for="employee in employeePerformance" :key="employee.name" class="hover:bg-[#F9FAFB] transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-[#5B21B6] rounded-full flex items-center justify-center text-white font-semibold">
                                            {{ employee.name.split(' ').map(n => n[0]).join('') }}
                                        </div>
                                        <div class="font-semibold text-[#1E293B]">{{ employee.name }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 bg-[#06B6D4]/10 text-[#06B6D4] rounded-full text-sm font-semibold">
                                        {{ employee.role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 bg-[#22C55E]/10 text-[#22C55E] rounded-full text-sm font-semibold">
                                        {{ employee.tasksCompleted }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 bg-[#F97316]/10 text-[#F97316] rounded-full text-sm font-semibold">
                                        {{ employee.tasksInProgress }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1 bg-[#F9FAFB] rounded-full h-2 w-32">
                                            <div 
                                                :class="{
                                                    'bg-[#22C55E]': employee.efficiency >= 85,
                                                    'bg-[#F97316]': employee.efficiency >= 70 && employee.efficiency < 85,
                                                    'bg-[#EF4444]': employee.efficiency < 70
                                                }"
                                                class="h-2 rounded-full" 
                                                :style="{ width: `${employee.efficiency}%` }"
                                            ></div>
                                        </div>
                                        <span class="text-sm font-semibold text-[#1E293B]">{{ employee.efficiency }}%</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
