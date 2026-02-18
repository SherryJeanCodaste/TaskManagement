<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { computed } from 'vue';

interface Props {
    recentTasks?: any[];
    assignedTasks?: any[];
    projects?: any[];
    myProjects?: any[];
    recentComments?: any[];
    stats: {
        total_tasks: number;
        pending: number;
        in_progress: number;
        completed: number;
    };
    categoryStats?: {
        frontend: number;
        backend: number;
        server: number;
    };
    auth: {
        user: {
            name: string;
            email: string;
            role: string;
        };
    };
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

// Determine if user is customer or developer
const isCustomer = props.recentTasks !== undefined;
const isDeveloper = props.assignedTasks !== undefined;

const displayTasks = isCustomer ? props.recentTasks : props.assignedTasks;

// Calculate completion rate for developers
const completionRate = computed(() => {
    if (!isDeveloper || props.stats.total_tasks === 0) return 0;
    return Math.round((props.stats.completed / props.stats.total_tasks) * 100);
});

// Get role display name
const roleDisplay = computed(() => {
    const role = props.auth.user.role;
    if (role === 'frontend_developer') return 'Frontend Developer';
    if (role === 'backend_developer') return 'Backend Developer';
    if (role === 'server_administrator') return 'Server Administrator';
    return 'Customer';
});
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4 bg-[#F9FAFB]">
            <!-- Welcome Header -->
            <div class="bg-gradient-to-r from-[#5B21B6] to-[#7C3AED] rounded-xl p-4 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-xl font-bold mb-1">
                            {{ props.auth.user.login_count <= 1 ? 'Welcome' : 'Welcome back' }}, {{ props.auth.user.name }}!
                        </h1>
                        <p class="text-white/90 text-sm">{{ roleDisplay }} • {{ isCustomer ? 'Create and track your tasks' : 'View and manage your assigned tasks' }}</p>
                    </div>
                    <div v-if="isDeveloper" class="text-right">
                        <p class="text-white/80 text-xs mb-1">Completion Rate</p>
                        <p class="text-2xl font-bold">{{ completionRate }}%</p>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl p-4 border border-[#CBD5E1] shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-[#1E293B] font-medium">Total Tasks</p>
                            <p class="text-2xl font-bold text-[#5B21B6] mt-1">{{ props.stats.total_tasks }}</p>
                        </div>
                        <div class="w-10 h-10 bg-[#5B21B6]/10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#5B21B6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-4 border border-[#CBD5E1] shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-[#1E293B] font-medium">Pending</p>
                            <p class="text-2xl font-bold text-[#EF4444] mt-1">{{ props.stats.pending }}</p>
                        </div>
                        <div class="w-10 h-10 bg-[#EF4444]/10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#EF4444]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-4 border border-[#CBD5E1] shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-[#1E293B] font-medium">In Progress</p>
                            <p class="text-2xl font-bold text-[#F97316] mt-1">{{ props.stats.in_progress }}</p>
                        </div>
                        <div class="w-10 h-10 bg-[#F97316]/10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#F97316]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-4 border border-[#CBD5E1] shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-[#1E293B] font-medium">Completed</p>
                            <p class="text-2xl font-bold text-[#22C55E] mt-1">{{ props.stats.completed }}</p>
                        </div>
                        <div class="w-10 h-10 bg-[#22C55E]/10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#22C55E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Projects Row -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl border border-[#CBD5E1] shadow-sm p-4">
                    <h3 class="text-base font-bold text-[#1E293B] mb-3">Quick Actions</h3>
                    <div class="space-y-2">
                        <a :href="'/tasks'" class="flex items-center gap-3 p-2 rounded-lg hover:bg-[#F9FAFB] transition-colors group">
                            <div class="w-8 h-8 bg-[#5B21B6]/10 rounded-lg flex items-center justify-center group-hover:bg-[#5B21B6]/20 transition-colors">
                                <svg class="w-4 h-4 text-[#5B21B6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-[#1E293B] text-sm">View All Tasks</p>
                                <p class="text-xs text-[#64748B]">{{ isCustomer ? 'Manage your assignments' : 'Manage your assignments' }}</p>
                            </div>
                        </a>
                        <a :href="'/projects'" class="flex items-center gap-3 p-2 rounded-lg hover:bg-[#F9FAFB] transition-colors group">
                            <div class="w-8 h-8 bg-[#06B6D4]/10 rounded-lg flex items-center justify-center group-hover:bg-[#06B6D4]/20 transition-colors">
                                <svg class="w-4 h-4 text-[#06B6D4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-[#1E293B] text-sm">My Projects</p>
                                <p class="text-xs text-[#64748B]">View project details</p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- My Projects -->
                <div class="lg:col-span-2 bg-white rounded-xl border border-[#CBD5E1] shadow-sm p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-base font-bold text-[#1E293B]">My Projects</h3>
                        <a :href="'/projects'" class="text-[#5B21B6] hover:text-[#6D28D9] font-semibold text-sm">View All →</a>
                    </div>
                    <div v-if="(isCustomer ? projects : myProjects) && (isCustomer ? projects : myProjects).length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <a v-for="project in (isCustomer ? projects : myProjects).slice(0, 4)" :key="project.id" :href="`/projects/${project.id}`" class="border border-[#E2E8F0] rounded-lg p-3 hover:border-[#5B21B6] hover:shadow-md transition-all group">
                            <div class="flex items-start gap-2">
                                <div class="w-8 h-8 bg-[#5B21B6]/10 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-[#5B21B6]/20 transition-colors">
                                    <svg class="w-4 h-4 text-[#5B21B6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-[#1E293B] truncate text-sm">{{ project.name }}</p>
                                    <p class="text-xs text-[#64748B] mt-1 line-clamp-2">{{ project.description || 'No description' }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div v-else class="text-center py-6 text-[#94A3B8]">
                        <svg class="w-10 h-10 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                        </svg>
                        <p class="text-sm mb-3">{{ isCustomer ? 'No projects yet' : 'No projects assigned yet' }}</p>
                        <a v-if="isCustomer" :href="'/projects/create'" class="inline-block bg-[#5B21B6] hover:bg-[#6D28D9] text-white font-semibold px-4 py-2 rounded-lg transition-colors text-sm">
                            Create Project
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Table -->
            <div class="bg-white rounded-xl border border-[#CBD5E1] shadow-sm overflow-hidden">
                <div class="p-4 border-b border-[#CBD5E1] flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-[#1E293B]">{{ isCustomer ? 'Your Recent Tasks' : 'Your Assigned Tasks' }}</h2>
                        <p class="text-xs text-[#1E293B]/60 mt-1">{{ isCustomer ? 'Tasks you have created' : 'Tasks assigned to you' }}</p>
                    </div>
                    <a :href="'/tasks'" class="text-[#5B21B6] hover:text-[#6D28D9] font-semibold text-sm">View All →</a>
                </div>
                <div v-if="displayTasks && displayTasks.length > 0" class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-[#F9FAFB] border-b border-[#CBD5E1]">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Task</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Project</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Category</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Status</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Created</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#CBD5E1]">
                            <tr v-for="task in displayTasks" :key="task.id" @click="router.visit(`/tasks/${task.id}`)" class="hover:bg-[#F9FAFB] transition-colors cursor-pointer">
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-[#1E293B] text-sm">{{ task.title }}</div>
                                    <div v-if="task.description" class="text-xs text-[#1E293B]/60 mt-1">{{ task.description?.substring(0, 50) }}{{ task.description?.length > 50 ? '...' : '' }}</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-[#1E293B] text-sm">{{ task.project?.name }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span 
                                        :class="{
                                            'bg-[#5B21B6]/10 text-[#5B21B6]': task.category === 'frontend',
                                            'bg-[#06B6D4]/10 text-[#06B6D4]': task.category === 'backend',
                                            'bg-[#F97316]/10 text-[#F97316]': task.category === 'server'
                                        }"
                                        class="px-2 py-1 rounded-full text-xs font-semibold capitalize"
                                    >
                                        {{ task.category }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span 
                                        :class="{
                                            'bg-[#22C55E]/10 text-[#22C55E]': task.status === 'completed',
                                            'bg-[#F97316]/10 text-[#F97316]': task.status === 'in_progress',
                                            'bg-[#EF4444]/10 text-[#EF4444]': task.status === 'pending'
                                        }"
                                        class="px-2 py-1 rounded-full text-xs font-semibold capitalize"
                                    >
                                        {{ task.status.replace('_', ' ') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-[#1E293B]/60 text-xs">{{ new Date(task.created_at).toLocaleDateString() }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else class="p-8 text-center">
                    <svg class="w-12 h-12 mx-auto text-[#CBD5E1] mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <p class="text-[#1E293B]/60 text-sm">{{ isCustomer ? 'No tasks created yet. Create your first task!' : 'No tasks assigned to you yet.' }}</p>
                    <a v-if="isCustomer" :href="'/tasks/create'" class="inline-block mt-3 px-4 py-2 bg-[#5B21B6] text-white rounded-lg hover:bg-[#6D28D9] transition-colors font-semibold text-sm">
                        Create Task
                    </a>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
