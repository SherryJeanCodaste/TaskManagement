<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Projects', href: '/projects' },
];

// Mock data - replace with actual props from backend
const projects = [
    { 
        id: 1, 
        name: 'E-Commerce Platform', 
        customer: 'ABC Corp', 
        frontend: 'John Doe', 
        backend: 'Jane Smith', 
        serverAdmin: 'Bob Wilson', 
        tasks: 12,
        completedTasks: 8,
        status: 'active',
        createdAt: '2026-01-15'
    },
    { 
        id: 2, 
        name: 'Mobile App', 
        customer: 'XYZ Ltd', 
        frontend: 'Alice Brown', 
        backend: 'Charlie Davis', 
        serverAdmin: 'Bob Wilson', 
        tasks: 8,
        completedTasks: 3,
        status: 'active',
        createdAt: '2026-01-20'
    },
    { 
        id: 3, 
        name: 'CRM System', 
        customer: 'Tech Solutions', 
        frontend: 'John Doe', 
        backend: 'Jane Smith', 
        serverAdmin: 'Bob Wilson', 
        tasks: 15,
        completedTasks: 15,
        status: 'completed',
        createdAt: '2025-12-10'
    },
];
</script>

<template>
    <Head title="Projects" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6 bg-[#F9FAFB]">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-[#1E293B]">Projects</h1>
                    <p class="text-[#1E293B] mt-1">Manage all your projects and assignments</p>
                </div>
                <button class="flex items-center gap-2 px-6 py-3 bg-[#5B21B6] text-white rounded-lg hover:bg-[#6D28D9] transition-colors font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create New Project
                </button>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl p-6 border border-[#CBD5E1] shadow-sm">
                    <p class="text-sm text-[#1E293B] font-medium">Total Projects</p>
                    <p class="text-3xl font-bold text-[#5B21B6] mt-2">{{ projects.length }}</p>
                </div>
                <div class="bg-white rounded-xl p-6 border border-[#CBD5E1] shadow-sm">
                    <p class="text-sm text-[#1E293B] font-medium">Active Projects</p>
                    <p class="text-3xl font-bold text-[#06B6D4] mt-2">{{ projects.filter(p => p.status === 'active').length }}</p>
                </div>
                <div class="bg-white rounded-xl p-6 border border-[#CBD5E1] shadow-sm">
                    <p class="text-sm text-[#1E293B] font-medium">Completed</p>
                    <p class="text-3xl font-bold text-[#22C55E] mt-2">{{ projects.filter(p => p.status === 'completed').length }}</p>
                </div>
                <div class="bg-white rounded-xl p-6 border border-[#CBD5E1] shadow-sm">
                    <p class="text-sm text-[#1E293B] font-medium">Total Tasks</p>
                    <p class="text-3xl font-bold text-[#F97316] mt-2">{{ projects.reduce((sum, p) => sum + p.tasks, 0) }}</p>
                </div>
            </div>

            <!-- Projects Table -->
            <div class="bg-white rounded-xl border border-[#CBD5E1] shadow-sm overflow-hidden">
                <div class="p-6 border-b border-[#CBD5E1] flex items-center justify-between">
                    <h2 class="text-xl font-bold text-[#1E293B]">All Projects</h2>
                    <div class="flex gap-3">
                        <input 
                            type="search" 
                            placeholder="Search projects..." 
                            class="px-4 py-2 border border-[#CBD5E1] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#06B6D4] focus:border-transparent"
                        />
                        <select class="px-4 py-2 border border-[#CBD5E1] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#06B6D4] focus:border-transparent">
                            <option>All Status</option>
                            <option>Active</option>
                            <option>Completed</option>
                        </select>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-[#F9FAFB] border-b border-[#CBD5E1]">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Project Name</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Frontend Dev</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Backend Dev</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Server Admin</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Progress</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#CBD5E1]">
                            <tr v-for="project in projects" :key="project.id" class="hover:bg-[#F9FAFB] transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-semibold text-[#1E293B]">{{ project.name }}</div>
                                    <div class="text-sm text-[#1E293B]/60">Created {{ project.createdAt }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-[#1E293B]">{{ project.customer }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-[#1E293B]">{{ project.frontend }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-[#1E293B]">{{ project.backend }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-[#1E293B]">{{ project.serverAdmin }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 bg-[#F9FAFB] rounded-full h-2 w-24">
                                            <div 
                                                class="bg-[#06B6D4] h-2 rounded-full" 
                                                :style="{ width: `${(project.completedTasks / project.tasks) * 100}%` }"
                                            ></div>
                                        </div>
                                        <span class="text-sm text-[#1E293B]">{{ project.completedTasks }}/{{ project.tasks }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span 
                                        :class="{
                                            'bg-[#22C55E]/10 text-[#22C55E]': project.status === 'completed',
                                            'bg-[#06B6D4]/10 text-[#06B6D4]': project.status === 'active'
                                        }"
                                        class="px-3 py-1 rounded-full text-sm font-semibold capitalize"
                                    >
                                        {{ project.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex gap-2">
                                        <button class="text-[#06B6D4] hover:text-[#0891B2] font-semibold text-sm">Edit</button>
                                        <button class="text-[#5B21B6] hover:text-[#6D28D9] font-semibold text-sm">View</button>
                                        <button class="text-[#EF4444] hover:text-[#DC2626] font-semibold text-sm">Delete</button>
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
