<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { ref } from 'vue';

interface Props {
    projects: any[];
    tasks: any[];
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Trash', href: '/projects-trash' },
];

const activeTab = ref<'projects' | 'tasks'>('projects');

const restoreProject = (projectId: number) => {
    if (confirm('Are you sure you want to restore this project and all its tasks?')) {
        router.post(`/projects/${projectId}/restore`, {}, {
            preserveScroll: true,
        });
    }
};

const permanentlyDeleteProject = (projectId: number) => {
    if (confirm('Are you sure you want to PERMANENTLY delete this project? This action cannot be undone!')) {
        router.delete(`/projects/${projectId}/force-delete`, {
            preserveScroll: true,
        });
    }
};

const restoreTask = (taskId: number) => {
    if (confirm('Are you sure you want to restore this task?')) {
        router.post(`/tasks/${taskId}/restore`, {}, {
            preserveScroll: true,
        });
    }
};

const permanentlyDeleteTask = (taskId: number) => {
    if (confirm('Are you sure you want to PERMANENTLY delete this task? This action cannot be undone!')) {
        router.delete(`/tasks/${taskId}/force-delete`, {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head title="Trash" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6 bg-[#F9FAFB]">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-[#1E293B]">Trash</h1>
                    <p class="text-[#1E293B] mt-1">Deleted projects and tasks can be restored or permanently deleted</p>
                </div>
                <a :href="'/projects'" class="px-6 py-3 bg-[#5B21B6] text-white rounded-lg hover:bg-[#6D28D9] transition-colors font-semibold">
                    Back to Projects
                </a>
            </div>

            <!-- Tabs -->
            <div class="flex gap-2 border-b border-[#CBD5E1]">
                <button
                    @click="activeTab = 'projects'"
                    :class="[
                        'px-6 py-3 font-semibold transition-colors',
                        activeTab === 'projects'
                            ? 'text-[#5B21B6] border-b-2 border-[#5B21B6]'
                            : 'text-[#64748B] hover:text-[#1E293B]'
                    ]"
                >
                    Projects ({{ props.projects.length }})
                </button>
                <button
                    @click="activeTab = 'tasks'"
                    :class="[
                        'px-6 py-3 font-semibold transition-colors',
                        activeTab === 'tasks'
                            ? 'text-[#5B21B6] border-b-2 border-[#5B21B6]'
                            : 'text-[#64748B] hover:text-[#1E293B]'
                    ]"
                >
                    Tasks ({{ props.tasks.length }})
                </button>
            </div>

            <!-- Projects Table -->
            <div v-show="activeTab === 'projects'" class="bg-white rounded-xl border border-[#CBD5E1] shadow-sm overflow-hidden">
                <div class="p-6 border-b border-[#CBD5E1]">
                    <h2 class="text-xl font-bold text-[#1E293B]">Deleted Projects</h2>
                </div>
                <div v-if="props.projects.length > 0" class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-[#F9FAFB] border-b border-[#CBD5E1]">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Project Name</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Tasks</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Deleted At</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#CBD5E1]">
                            <tr v-for="project in props.projects" :key="project.id" class="hover:bg-[#F9FAFB] transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-[#1E293B]">{{ project.name }}</div>
                                </td>
                                <td class="px-6 py-4 text-[#1E293B]">
                                    {{ project.description || 'No description' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold bg-[#94A3B8]/10 text-[#64748B]">
                                        {{ project.tasks_count || 0 }} tasks
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-[#1E293B]">{{ new Date(project.deleted_at).toLocaleDateString() }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <button 
                                            @click="restoreProject(project.id)" 
                                            class="px-4 py-2 bg-[#22C55E] text-white rounded-lg hover:bg-[#16A34A] transition-colors font-semibold text-sm"
                                            title="Restore project"
                                        >
                                            Restore
                                        </button>
                                        <button 
                                            @click="permanentlyDeleteProject(project.id)" 
                                            class="p-2 text-[#EF4444] hover:bg-[#EF4444]/10 rounded-lg transition-colors"
                                            title="Permanently delete"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else class="p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-[#CBD5E1] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    <p class="text-[#1E293B]/60">Trash is empty</p>
                </div>
            </div>

            <!-- Tasks Table -->
            <div v-show="activeTab === 'tasks'" class="bg-white rounded-xl border border-[#CBD5E1] shadow-sm overflow-hidden">
                <div class="p-6 border-b border-[#CBD5E1]">
                    <h2 class="text-xl font-bold text-[#1E293B]">Deleted Tasks</h2>
                </div>
                <div v-if="props.tasks.length > 0" class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-[#F9FAFB] border-b border-[#CBD5E1]">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Task Name</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Project</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Deleted At</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#CBD5E1]">
                            <tr v-for="task in props.tasks" :key="task.id" class="hover:bg-[#F9FAFB] transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-[#1E293B]">{{ task.title }}</div>
                                </td>
                                <td class="px-6 py-4 text-[#1E293B]">
                                    {{ task.project?.name || 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span 
                                        :class="{
                                            'bg-[#5B21B6]/10 text-[#5B21B6]': task.category === 'frontend',
                                            'bg-[#06B6D4]/10 text-[#06B6D4]': task.category === 'backend',
                                            'bg-[#F97316]/10 text-[#F97316]': task.category === 'server'
                                        }"
                                        class="px-3 py-1 rounded-full text-sm font-semibold capitalize"
                                    >
                                        {{ task.category }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-[#1E293B]">{{ new Date(task.deleted_at).toLocaleDateString() }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <button 
                                            @click="restoreTask(task.id)" 
                                            class="px-4 py-2 bg-[#22C55E] text-white rounded-lg hover:bg-[#16A34A] transition-colors font-semibold text-sm"
                                            title="Restore task"
                                        >
                                            Restore
                                        </button>
                                        <button 
                                            @click="permanentlyDeleteTask(task.id)" 
                                            class="p-2 text-[#EF4444] hover:bg-[#EF4444]/10 rounded-lg transition-colors"
                                            title="Permanently delete"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else class="p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-[#CBD5E1] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <p class="text-[#1E293B]/60">No deleted tasks</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
