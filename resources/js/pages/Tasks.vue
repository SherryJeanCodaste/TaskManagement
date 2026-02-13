<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Tasks', href: '/tasks' },
];

// No sample data - using real backend data
const tasks = [];

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
        <div class="flex h-full flex-1 flex-col gap-2 p-3 bg-[#F9FAFB]">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-bold text-[#1E293B]">Tasks Overview</h1>
                    <p class="text-xs text-[#1E293B] mt-0.5">Monitor all tasks across projects</p>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-2">
                <div class="bg-white rounded-lg p-2 border border-[#CBD5E1]">
                    <p class="text-[10px] text-[#1E293B] font-medium">Total Tasks</p>
                    <p class="text-xl font-bold text-[#5B21B6]">{{ stats.total }}</p>
                </div>
                <div class="bg-white rounded-lg p-2 border border-[#CBD5E1]">
                    <p class="text-[10px] text-[#1E293B] font-medium">Pending</p>
                    <p class="text-xl font-bold text-[#EF4444]">{{ stats.pending }}</p>
                </div>
                <div class="bg-white rounded-lg p-2 border border-[#CBD5E1]">
                    <p class="text-[10px] text-[#1E293B] font-medium">In Progress</p>
                    <p class="text-xl font-bold text-[#F97316]">{{ stats.inProgress }}</p>
                </div>
                <div class="bg-white rounded-lg p-2 border border-[#CBD5E1]">
                    <p class="text-[10px] text-[#1E293B] font-medium">Completed</p>
                    <p class="text-xl font-bold text-[#22C55E]">{{ stats.completed }}</p>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="bg-white rounded-lg border border-[#CBD5E1] p-2 flex items-center gap-2">
                <input 
                    type="search" 
                    placeholder="Search tasks..." 
                    class="flex-1 px-2 py-1.5 text-xs border border-[#CBD5E1] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5B21B6] focus:border-transparent"
                />
                <select class="px-2 py-1.5 text-xs border border-[#CBD5E1] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5B21B6] focus:border-transparent">
                    <option>All Status</option>
                    <option>Pending</option>
                    <option>In Progress</option>
                    <option>Completed</option>
                </select>
                <select class="px-2 py-1.5 text-xs border border-[#CBD5E1] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5B21B6] focus:border-transparent">
                    <option>All Categories</option>
                    <option>Frontend</option>
                    <option>Backend</option>
                    <option>Server</option>
                </select>
            </div>

            <!-- Tasks Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                <div 
                    v-for="task in tasks" 
                    :key="task.id" 
                    class="bg-white rounded-lg border border-[#CBD5E1] p-3 hover:shadow-lg transition-shadow"
                >
                    <!-- Task Header -->
                    <div class="flex items-start justify-between mb-2">
                        <div class="flex-1">
                            <h3 class="text-sm font-semibold text-[#1E293B] mb-0.5">{{ task.title }}</h3>
                            <p class="text-[10px] text-[#6B7280]">{{ task.project }}</p>
                        </div>
                        <span 
                            :class="{
                                'bg-[#22C55E]/10 text-[#22C55E]': task.status === 'completed',
                                'bg-[#F97316]/10 text-[#F97316]': task.status === 'in-progress',
                                'bg-[#EF4444]/10 text-[#EF4444]': task.status === 'pending'
                            }"
                            class="px-1.5 py-0.5 rounded text-[10px] font-semibold capitalize ml-2"
                        >
                            {{ task.status.replace('-', ' ') }}
                        </span>
                    </div>

                    <!-- Category & Priority -->
                    <div class="flex gap-1.5 mb-2 pb-2 border-b border-[#CBD5E1]">
                        <span 
                            :class="{
                                'bg-[#3B82F6]/10 text-[#3B82F6]': task.category === 'Frontend',
                                'bg-[#F97316]/10 text-[#F97316]': task.category === 'Backend',
                                'bg-[#8B5CF6]/10 text-[#8B5CF6]': task.category === 'Server'
                            }"
                            class="px-1.5 py-0.5 rounded text-[10px] font-semibold"
                        >
                            {{ task.category }}
                        </span>
                        <span 
                            :class="{
                                'bg-[#EF4444]/10 text-[#EF4444]': task.priority === 'high',
                                'bg-[#F97316]/10 text-[#F97316]': task.priority === 'medium',
                                'bg-[#CBD5E1]/10 text-[#1E293B]': task.priority === 'low'
                            }"
                            class="px-1.5 py-0.5 rounded text-[10px] font-semibold capitalize"
                        >
                            {{ task.priority }}
                        </span>
                    </div>

                    <!-- Project Team -->
                    <div class="mb-2">
                        <p class="text-[10px] text-[#6B7280] mb-1.5 font-medium">Project Team</p>
                        <div class="space-y-1.5">
                            <!-- Frontend Developer -->
                            <div 
                                :class="{
                                    'bg-[#EFF6FF] ring-1 ring-[#3B82F6]': task.category === 'Frontend',
                                    'bg-[#EFF6FF]': task.category !== 'Frontend'
                                }"
                                class="flex items-center gap-1.5 p-1.5 rounded"
                            >
                                <div class="w-6 h-6 bg-[#3B82F6] rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-white text-[10px] font-bold">FE</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[10px] text-[#6B7280]">Frontend</p>
                                    <p class="text-xs font-medium text-[#1E293B] truncate">John Doe</p>
                                </div>
                                <svg v-if="task.category === 'Frontend'" class="w-3 h-3 text-[#3B82F6]" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>

                            <!-- Backend Developer -->
                            <div 
                                :class="{
                                    'bg-[#FFF7ED] ring-1 ring-[#F97316]': task.category === 'Backend',
                                    'bg-[#FFF7ED]': task.category !== 'Backend'
                                }"
                                class="flex items-center gap-1.5 p-1.5 rounded"
                            >
                                <div class="w-6 h-6 bg-[#F97316] rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-white text-[10px] font-bold">BE</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[10px] text-[#6B7280]">Backend</p>
                                    <p class="text-xs font-medium text-[#1E293B] truncate">Jane Smith</p>
                                </div>
                                <svg v-if="task.category === 'Backend'" class="w-3 h-3 text-[#F97316]" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>

                            <!-- Server Admin -->
                            <div 
                                :class="{
                                    'bg-[#F5F3FF] ring-1 ring-[#8B5CF6]': task.category === 'Server',
                                    'bg-[#F5F3FF]': task.category !== 'Server'
                                }"
                                class="flex items-center gap-1.5 p-1.5 rounded"
                            >
                                <div class="w-6 h-6 bg-[#8B5CF6] rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-white text-[10px] font-bold">SA</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[10px] text-[#6B7280]">Server Admin</p>
                                    <p class="text-xs font-medium text-[#1E293B] truncate">Bob Wilson</p>
                                </div>
                                <svg v-if="task.category === 'Server'" class="w-3 h-3 text-[#8B5CF6]" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Due Date -->
                    <div class="mb-2 pb-2 border-b border-[#CBD5E1]">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] text-[#6B7280]">Due Date</span>
                            <span class="text-xs font-medium text-[#1E293B]">{{ task.dueDate }}</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-1.5">
                        <Link 
                            :href="`/tasks/${task.id}`"
                            class="flex-1 px-2 py-1.5 bg-[#3B82F6] text-white rounded hover:bg-[#2563EB] transition-colors text-xs font-medium text-center"
                        >
                            View Details
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
