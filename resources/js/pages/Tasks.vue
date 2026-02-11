<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Tasks', href: '/tasks' },
];

// Mock data
const tasks = [
    { id: 1, title: 'Design Homepage', project: 'E-Commerce Platform', category: 'Frontend', status: 'completed', assignee: 'John Doe', priority: 'high', dueDate: '2026-02-10' },
    { id: 2, title: 'Setup API Endpoints', project: 'Mobile App', category: 'Backend', status: 'in-progress', assignee: 'Jane Smith', priority: 'high', dueDate: '2026-02-12' },
    { id: 3, title: 'Configure Server', project: 'CRM System', category: 'Server', status: 'pending', assignee: 'Bob Wilson', priority: 'medium', dueDate: '2026-02-15' },
    { id: 4, title: 'User Authentication', project: 'E-Commerce Platform', category: 'Backend', status: 'in-progress', assignee: 'Charlie Davis', priority: 'high', dueDate: '2026-02-11' },
    { id: 5, title: 'Payment Integration', project: 'E-Commerce Platform', category: 'Backend', status: 'pending', assignee: 'Jane Smith', priority: 'high', dueDate: '2026-02-14' },
    { id: 6, title: 'Mobile UI Design', project: 'Mobile App', category: 'Frontend', status: 'in-progress', assignee: 'Alice Brown', priority: 'medium', dueDate: '2026-02-13' },
    { id: 7, title: 'Database Optimization', project: 'CRM System', category: 'Backend', status: 'completed', assignee: 'Jane Smith', priority: 'low', dueDate: '2026-02-08' },
    { id: 8, title: 'SSL Certificate Setup', project: 'Mobile App', category: 'Server', status: 'pending', assignee: 'Bob Wilson', priority: 'high', dueDate: '2026-02-16' },
];

const stats = {
    total: tasks.length,
    pending: tasks.filter(t => t.status === 'pending').length,
    inProgress: tasks.filter(t => t.status === 'in-progress').length,
    completed: tasks.filter(t => t.status === 'completed').length,
};
</script>

<template>
    <Head title="Tasks" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6 bg-[#F9FAFB]">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-[#1E293B]">Tasks Overview</h1>
                    <p class="text-[#1E293B] mt-1">Monitor all tasks across projects</p>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl p-6 border border-[#CBD5E1] shadow-sm">
                    <p class="text-sm text-[#1E293B] font-medium">Total Tasks</p>
                    <p class="text-3xl font-bold text-[#5B21B6] mt-2">{{ stats.total }}</p>
                </div>
                <div class="bg-white rounded-xl p-6 border border-[#CBD5E1] shadow-sm">
                    <p class="text-sm text-[#1E293B] font-medium">Pending</p>
                    <p class="text-3xl font-bold text-[#EF4444] mt-2">{{ stats.pending }}</p>
                </div>
                <div class="bg-white rounded-xl p-6 border border-[#CBD5E1] shadow-sm">
                    <p class="text-sm text-[#1E293B] font-medium">In Progress</p>
                    <p class="text-3xl font-bold text-[#F97316] mt-2">{{ stats.inProgress }}</p>
                </div>
                <div class="bg-white rounded-xl p-6 border border-[#CBD5E1] shadow-sm">
                    <p class="text-sm text-[#1E293B] font-medium">Completed</p>
                    <p class="text-3xl font-bold text-[#22C55E] mt-2">{{ stats.completed }}</p>
                </div>
            </div>

            <!-- Tasks Table -->
            <div class="bg-white rounded-xl border border-[#CBD5E1] shadow-sm overflow-hidden">
                <div class="p-6 border-b border-[#CBD5E1] flex items-center justify-between">
                    <h2 class="text-xl font-bold text-[#1E293B]">All Tasks</h2>
                    <div class="flex gap-3">
                        <input 
                            type="search" 
                            placeholder="Search tasks..." 
                            class="px-4 py-2 border border-[#CBD5E1] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#06B6D4] focus:border-transparent"
                        />
                        <select class="px-4 py-2 border border-[#CBD5E1] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#06B6D4] focus:border-transparent">
                            <option>All Status</option>
                            <option>Pending</option>
                            <option>In Progress</option>
                            <option>Completed</option>
                        </select>
                        <select class="px-4 py-2 border border-[#CBD5E1] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#06B6D4] focus:border-transparent">
                            <option>All Categories</option>
                            <option>Frontend</option>
                            <option>Backend</option>
                            <option>Server</option>
                        </select>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-[#F9FAFB] border-b border-[#CBD5E1]">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Task Title</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Project</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Assignee</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Priority</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Due Date</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#CBD5E1]">
                            <tr v-for="task in tasks" :key="task.id" class="hover:bg-[#F9FAFB] transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-semibold text-[#1E293B]">{{ task.title }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-[#1E293B]">{{ task.project }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span 
                                        :class="{
                                            'bg-[#5B21B6]/10 text-[#5B21B6]': task.category === 'Frontend',
                                            'bg-[#06B6D4]/10 text-[#06B6D4]': task.category === 'Backend',
                                            'bg-[#F97316]/10 text-[#F97316]': task.category === 'Server'
                                        }"
                                        class="px-3 py-1 rounded-full text-sm font-semibold"
                                    >
                                        {{ task.category }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-[#1E293B]">{{ task.assignee }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span 
                                        :class="{
                                            'bg-[#EF4444]/10 text-[#EF4444]': task.priority === 'high',
                                            'bg-[#F97316]/10 text-[#F97316]': task.priority === 'medium',
                                            'bg-[#CBD5E1]/10 text-[#1E293B]': task.priority === 'low'
                                        }"
                                        class="px-3 py-1 rounded-full text-sm font-semibold capitalize"
                                    >
                                        {{ task.priority }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-[#1E293B]">{{ task.dueDate }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span 
                                        :class="{
                                            'bg-[#22C55E]/10 text-[#22C55E]': task.status === 'completed',
                                            'bg-[#F97316]/10 text-[#F97316]': task.status === 'in-progress',
                                            'bg-[#EF4444]/10 text-[#EF4444]': task.status === 'pending'
                                        }"
                                        class="px-3 py-1 rounded-full text-sm font-semibold capitalize"
                                    >
                                        {{ task.status.replace('-', ' ') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex gap-2">
                                        <button class="text-[#06B6D4] hover:text-[#0891B2] font-semibold text-sm">View</button>
                                        <button v-if="task.status !== 'completed'" class="text-[#22C55E] hover:text-[#16A34A] font-semibold text-sm">Complete</button>
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
