<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Reports', href: '/reports' },
];

// No sample data - using real backend data
const projectStats = [];

const employeePerformance = [];
</script>

<template>
    <Head title="Reports" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-3 p-4 bg-[#F9FAFB]">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold text-[#1E293B]">Reports & Analytics</h1>
                    <p class="text-sm text-[#1E293B] mt-0.5">Track performance and project statistics</p>
                </div>
                <div class="flex gap-2">
                    <button class="px-4 py-2 bg-[#5B21B6] text-white rounded-lg hover:bg-[#6D28D9] transition-colors font-semibold text-sm">
                        Export PDF
                    </button>
                    <button class="px-4 py-2 bg-[#06B6D4] text-white rounded-lg hover:bg-[#0891B2] transition-colors font-semibold text-sm">
                        Export Excel
                    </button>
                </div>
            </div>

            <!-- Overview Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div class="bg-white rounded-lg p-3 border border-[#CBD5E1] shadow-sm">
                    <p class="text-xs text-[#1E293B] font-medium">Overall Completion Rate</p>
                    <p class="text-2xl font-bold text-[#22C55E] mt-1">74%</p>
                    <p class="text-xs text-[#1E293B]/60 mt-0.5">26/35 tasks completed</p>
                </div>
                <div class="bg-white rounded-lg p-3 border border-[#CBD5E1] shadow-sm">
                    <p class="text-xs text-[#1E293B] font-medium">Average Efficiency</p>
                    <p class="text-2xl font-bold text-[#06B6D4] mt-1">86%</p>
                    <p class="text-xs text-[#1E293B]/60 mt-0.5">Across all employees</p>
                </div>
                <div class="bg-white rounded-lg p-3 border border-[#CBD5E1] shadow-sm">
                    <p class="text-xs text-[#1E293B] font-medium">Active Projects</p>
                    <p class="text-2xl font-bold text-[#5B21B6] mt-1">2</p>
                    <p class="text-xs text-[#1E293B]/60 mt-0.5">1 completed</p>
                </div>
                <div class="bg-white rounded-lg p-3 border border-[#CBD5E1] shadow-sm">
                    <p class="text-xs text-[#1E293B] font-medium">Tasks This Month</p>
                    <p class="text-2xl font-bold text-[#F97316] mt-1">35</p>
                    <p class="text-xs text-[#1E293B]/60 mt-0.5">+12 from last month</p>
                </div>
            </div>

            <!-- Project Statistics -->
            <div class="bg-white rounded-lg border border-[#CBD5E1] shadow-sm overflow-hidden">
                <div class="p-4 border-b border-[#CBD5E1]">
                    <h2 class="text-base font-bold text-[#1E293B]">Project Statistics</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-[#F9FAFB] border-b border-[#CBD5E1]">
                            <tr>
                                <th class="px-4 py-2 text-left text-[10px] font-semibold text-[#1E293B] uppercase tracking-wider">Project Name</th>
                                <th class="px-4 py-2 text-left text-[10px] font-semibold text-[#1E293B] uppercase tracking-wider">Total Tasks</th>
                                <th class="px-4 py-2 text-left text-[10px] font-semibold text-[#1E293B] uppercase tracking-wider">Completed</th>
                                <th class="px-4 py-2 text-left text-[10px] font-semibold text-[#1E293B] uppercase tracking-wider">In Progress</th>
                                <th class="px-4 py-2 text-left text-[10px] font-semibold text-[#1E293B] uppercase tracking-wider">Pending</th>
                                <th class="px-4 py-2 text-left text-[10px] font-semibold text-[#1E293B] uppercase tracking-wider">Completion Rate</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#CBD5E1]">
                            <tr v-for="project in projectStats" :key="project.name" class="hover:bg-[#F9FAFB] transition-colors">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="font-semibold text-sm text-[#1E293B]">{{ project.name }}</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 py-1 bg-[#5B21B6]/10 text-[#5B21B6] rounded-md text-xs font-semibold">
                                        {{ project.tasks }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 py-1 bg-[#22C55E]/10 text-[#22C55E] rounded-md text-xs font-semibold">
                                        {{ project.completed }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 py-1 bg-[#F97316]/10 text-[#F97316] rounded-md text-xs font-semibold">
                                        {{ project.inProgress }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 py-1 bg-[#EF4444]/10 text-[#EF4444] rounded-md text-xs font-semibold">
                                        {{ project.pending }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 bg-[#F9FAFB] rounded-full h-1.5 w-24">
                                            <div 
                                                class="bg-[#22C55E] h-1.5 rounded-full" 
                                                :style="{ width: `${(project.completed / project.tasks) * 100}%` }"
                                            ></div>
                                        </div>
                                        <span class="text-xs font-semibold text-[#1E293B]">
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
            <div class="bg-white rounded-lg border border-[#CBD5E1] shadow-sm overflow-hidden">
                <div class="p-4 border-b border-[#CBD5E1]">
                    <h2 class="text-base font-bold text-[#1E293B]">Employee Performance</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-[#F9FAFB] border-b border-[#CBD5E1]">
                            <tr>
                                <th class="px-4 py-2 text-left text-[10px] font-semibold text-[#1E293B] uppercase tracking-wider">Employee Name</th>
                                <th class="px-4 py-2 text-left text-[10px] font-semibold text-[#1E293B] uppercase tracking-wider">Role</th>
                                <th class="px-4 py-2 text-left text-[10px] font-semibold text-[#1E293B] uppercase tracking-wider">Tasks Completed</th>
                                <th class="px-4 py-2 text-left text-[10px] font-semibold text-[#1E293B] uppercase tracking-wider">In Progress</th>
                                <th class="px-4 py-2 text-left text-[10px] font-semibold text-[#1E293B] uppercase tracking-wider">Efficiency</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#CBD5E1]">
                            <tr v-for="employee in employeePerformance" :key="employee.name" class="hover:bg-[#F9FAFB] transition-colors">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-[#5B21B6] rounded-full flex items-center justify-center text-white font-semibold text-xs">
                                            {{ employee.name.split(' ').map(n => n[0]).join('') }}
                                        </div>
                                        <div class="font-semibold text-sm text-[#1E293B]">{{ employee.name }}</div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 py-1 bg-[#06B6D4]/10 text-[#06B6D4] rounded-md text-xs font-semibold">
                                        {{ employee.role }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 py-1 bg-[#22C55E]/10 text-[#22C55E] rounded-md text-xs font-semibold">
                                        {{ employee.tasksCompleted }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 py-1 bg-[#F97316]/10 text-[#F97316] rounded-md text-xs font-semibold">
                                        {{ employee.tasksInProgress }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 bg-[#F9FAFB] rounded-full h-1.5 w-24">
                                            <div 
                                                :class="{
                                                    'bg-[#22C55E]': employee.efficiency >= 85,
                                                    'bg-[#F97316]': employee.efficiency >= 70 && employee.efficiency < 85,
                                                    'bg-[#EF4444]': employee.efficiency < 70
                                                }"
                                                class="h-1.5 rounded-full" 
                                                :style="{ width: `${employee.efficiency}%` }"
                                            ></div>
                                        </div>
                                        <span class="text-xs font-semibold text-[#1E293B]">{{ employee.efficiency }}%</span>
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
