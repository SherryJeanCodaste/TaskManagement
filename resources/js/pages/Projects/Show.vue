<script setup lang="ts">
import { Head, router, usePage, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { computed, ref } from 'vue';

interface Props {
    project: any;
}

const props = defineProps<Props>();
const page = usePage();
const user = computed(() => page.props.auth?.user);
const isCustomer = computed(() => user.value?.role === 'customer');

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Projects', href: '/projects' },
    { title: props.project.name, href: `/projects/${props.project.id}` },
];

// Edit project modal
const showEditModal = ref(false);
const editForm = useForm({
    name: props.project.name,
    description: props.project.description || '',
});

const updateProject = () => {
    editForm.put(`/projects/${props.project.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            showEditModal.value = false;
        },
    });
};

// Filters
const categoryFilter = ref('all');
const statusFilter = ref('all');
const dateFilter = ref('all');

// Filtered tasks
const filteredTasks = computed(() => {
    let filtered = [...(props.project.tasks || [])];

    // Category filter
    if (categoryFilter.value !== 'all') {
        filtered = filtered.filter(t => t.category === categoryFilter.value);
    }

    // Status filter
    if (statusFilter.value !== 'all') {
        filtered = filtered.filter(t => t.status === statusFilter.value);
    }

    // Date filter
    if (dateFilter.value !== 'all') {
        const now = new Date();
        const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
        
        filtered = filtered.filter(t => {
            const taskDate = new Date(t.created_at);
            const taskDay = new Date(taskDate.getFullYear(), taskDate.getMonth(), taskDate.getDate());
            
            if (dateFilter.value === 'today') {
                return taskDay.getTime() === today.getTime();
            } else if (dateFilter.value === 'week') {
                const weekAgo = new Date(today);
                weekAgo.setDate(weekAgo.getDate() - 7);
                return taskDay >= weekAgo;
            } else if (dateFilter.value === 'month') {
                const monthAgo = new Date(today);
                monthAgo.setMonth(monthAgo.getMonth() - 1);
                return taskDay >= monthAgo;
            }
            return true;
        });
    }

    return filtered;
});

const updateTaskStatus = (taskId: number, status: string) => {
    router.put(`/tasks/${taskId}`, { status }, {
        preserveScroll: true,
    });
};

const deleteTask = (taskId: number) => {
    if (confirm('Are you sure you want to delete this task?')) {
        router.delete(`/tasks/${taskId}`, {
            preserveScroll: true,
        });
    }
};

const stats = computed(() => {
    return {
        total: filteredTasks.value.length,
        pending: filteredTasks.value.filter(t => t.status === 'pending').length,
        in_progress: filteredTasks.value.filter(t => t.status === 'in_progress').length,
        completed: filteredTasks.value.filter(t => t.status === 'completed').length,
    };
});

const clearFilters = () => {
    categoryFilter.value = 'all';
    statusFilter.value = 'all';
    dateFilter.value = 'all';
};
</script>

<template>
    <Head :title="project.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6 bg-[#F9FAFB]">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-[#1E293B]">{{ project.name }}</h1>
                    <p class="text-[#1E293B] mt-1">{{ project.description || 'No description' }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <button v-if="isCustomer" @click="showEditModal = true" class="flex items-center gap-2 px-6 py-3 border border-[#5B21B6] text-[#5B21B6] rounded-lg hover:bg-[#5B21B6]/10 transition-colors font-semibold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Project
                    </button>
                    <a v-if="isCustomer" :href="`/tasks/create?project=${project.id}`" class="flex items-center gap-2 px-6 py-3 bg-[#5B21B6] text-white rounded-lg hover:bg-[#6D28D9] transition-colors font-semibold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create New Task
                    </a>
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
                    <p class="text-3xl font-bold text-[#F97316] mt-2">{{ stats.in_progress }}</p>
                </div>
                <div class="bg-white rounded-xl p-6 border border-[#CBD5E1] shadow-sm">
                    <p class="text-sm text-[#1E293B] font-medium">Completed</p>
                    <p class="text-3xl font-bold text-[#22C55E] mt-2">{{ stats.completed }}</p>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-xl border border-[#CBD5E1] shadow-sm p-6">
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#64748B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        <span class="text-sm font-semibold text-[#1E293B]">Filters:</span>
                    </div>

                    <!-- Category Filter (only for customers) -->
                    <div v-if="isCustomer" class="flex items-center gap-2">
                        <label class="text-sm text-[#64748B]">Category:</label>
                        <select 
                            v-model="categoryFilter"
                            class="px-3 py-2 border border-[#CBD5E1] rounded-lg text-sm text-[#1E293B] bg-white focus:outline-none focus:ring-2 focus:ring-[#5B21B6] focus:border-transparent"
                        >
                            <option value="all">All Categories</option>
                            <option value="frontend">Frontend</option>
                            <option value="backend">Backend</option>
                            <option value="server">Server</option>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div class="flex items-center gap-2">
                        <label class="text-sm text-[#64748B]">Status:</label>
                        <select 
                            v-model="statusFilter"
                            class="px-3 py-2 border border-[#CBD5E1] rounded-lg text-sm text-[#1E293B] bg-white focus:outline-none focus:ring-2 focus:ring-[#5B21B6] focus:border-transparent"
                        >
                            <option value="all">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>

                    <!-- Date Filter -->
                    <div class="flex items-center gap-2">
                        <label class="text-sm text-[#64748B]">Date:</label>
                        <select 
                            v-model="dateFilter"
                            class="px-3 py-2 border border-[#CBD5E1] rounded-lg text-sm text-[#1E293B] bg-white focus:outline-none focus:ring-2 focus:ring-[#5B21B6] focus:border-transparent"
                        >
                            <option value="all">All Time</option>
                            <option value="today">Today</option>
                            <option value="week">Last 7 Days</option>
                            <option value="month">Last 30 Days</option>
                        </select>
                    </div>

                    <!-- Clear Filters -->
                    <button
                        v-if="categoryFilter !== 'all' || statusFilter !== 'all' || dateFilter !== 'all'"
                        @click="clearFilters"
                        class="ml-auto px-4 py-2 text-sm text-[#EF4444] hover:bg-[#EF4444]/10 rounded-lg transition-colors font-medium"
                    >
                        Clear Filters
                    </button>

                    <!-- Results Count -->
                    <div class="ml-auto text-sm text-[#64748B]">
                        Showing {{ filteredTasks.length }} of {{ project.tasks?.length || 0 }} tasks
                    </div>
                </div>
            </div>

            <!-- Tasks Table -->
            <div class="bg-white rounded-xl border border-[#CBD5E1] shadow-sm overflow-hidden">
                <div class="p-6 border-b border-[#CBD5E1]">
                    <h2 class="text-xl font-bold text-[#1E293B]">{{ isCustomer ? 'Your Tasks' : 'All Tasks' }}</h2>
                </div>
                <div v-if="filteredTasks.length > 0" class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-[#F9FAFB] border-b border-[#CBD5E1]">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Task</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Created</th>
                                <th v-if="isCustomer" class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#CBD5E1]">
                            <tr v-for="task in filteredTasks" :key="task.id" @click="router.visit(`/tasks/${task.id}`)" class="hover:bg-[#F9FAFB] transition-colors cursor-pointer">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-[#1E293B]">{{ task.title }}</div>
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
                                <td class="px-6 py-4 whitespace-nowrap" @click.stop>
                                    <select 
                                        v-if="!isCustomer && task.assigned_to"
                                        :value="task.status"
                                        @change="updateTaskStatus(task.id, ($event.target as HTMLSelectElement).value)"
                                        :class="{
                                            'bg-[#22C55E]/10 text-[#22C55E]': task.status === 'completed',
                                            'bg-[#F97316]/10 text-[#F97316]': task.status === 'in_progress',
                                            'bg-[#EF4444]/10 text-[#EF4444]': task.status === 'pending'
                                        }"
                                        class="px-3 py-1 rounded-full text-sm font-semibold capitalize border-0 cursor-pointer"
                                    >
                                        <option value="pending">Pending</option>
                                        <option value="in_progress">In Progress</option>
                                        <option value="completed">Completed</option>
                                    </select>
                                    <span 
                                        v-else
                                        :class="{
                                            'bg-[#22C55E]/10 text-[#22C55E]': task.status === 'completed',
                                            'bg-[#F97316]/10 text-[#F97316]': task.status === 'in_progress',
                                            'bg-[#EF4444]/10 text-[#EF4444]': task.status === 'pending'
                                        }"
                                        class="px-3 py-1 rounded-full text-sm font-semibold capitalize"
                                    >
                                        {{ task.status.replace('_', ' ') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-[#1E293B]">{{ new Date(task.created_at).toLocaleDateString() }}</td>
                                <td v-if="isCustomer" class="px-6 py-4 whitespace-nowrap" @click.stop>
                                    <button 
                                        @click="deleteTask(task.id)" 
                                        class="p-2 text-[#EF4444] hover:bg-[#EF4444]/10 rounded-lg transition-colors"
                                        title="Delete task"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else class="p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-[#CBD5E1] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <p class="text-[#1E293B]/60 mb-2">
                        {{ (project.tasks?.length || 0) === 0 ? 'No tasks in this project yet.' : 'No tasks match your filters.' }}
                    </p>
                    <button 
                        v-if="(project.tasks?.length || 0) > 0 && filteredTasks.length === 0"
                        @click="clearFilters"
                        class="text-[#5B21B6] hover:text-[#6D28D9] font-semibold"
                    >
                        Clear Filters
                    </button>
                    <a 
                        v-else-if="isCustomer"
                        :href="'/tasks/create'" 
                        class="inline-block px-6 py-2 bg-[#5B21B6] text-white rounded-lg hover:bg-[#6D28D9] transition-colors font-semibold"
                    >
                        Create Task
                    </a>
                </div>
            </div>

            <!-- Edit Project Modal -->
            <div v-if="showEditModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" @click="showEditModal = false">
                <div @click.stop class="bg-white rounded-xl p-6 max-w-md w-full mx-4 shadow-xl">
                    <h3 class="text-xl font-bold text-[#1E293B] mb-4">Edit Project</h3>
                    <form @submit.prevent="updateProject" class="space-y-4">
                        <div>
                            <label for="edit-name" class="block text-sm font-semibold text-[#1E293B] mb-2">
                                Project Name <span class="text-[#EF4444]">*</span>
                            </label>
                            <input
                                id="edit-name"
                                v-model="editForm.name"
                                type="text"
                                required
                                class="w-full px-4 py-3 border border-[#CBD5E1] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5B21B6] focus:border-transparent text-[#1E293B] bg-white"
                            />
                            <p v-if="editForm.errors.name" class="mt-1 text-sm text-[#EF4444]">{{ editForm.errors.name }}</p>
                        </div>
                        <div>
                            <label for="edit-description" class="block text-sm font-semibold text-[#1E293B] mb-2">
                                Description
                            </label>
                            <textarea
                                id="edit-description"
                                v-model="editForm.description"
                                rows="3"
                                class="w-full px-4 py-3 border border-[#CBD5E1] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5B21B6] focus:border-transparent resize-none text-[#1E293B] bg-white"
                            ></textarea>
                            <p v-if="editForm.errors.description" class="mt-1 text-sm text-[#EF4444]">{{ editForm.errors.description }}</p>
                        </div>
                        <div class="flex items-center gap-3 pt-4">
                            <button
                                type="submit"
                                :disabled="editForm.processing"
                                class="flex-1 px-6 py-3 bg-[#5B21B6] text-white rounded-lg hover:bg-[#6D28D9] transition-colors font-semibold disabled:opacity-50"
                            >
                                {{ editForm.processing ? 'Saving...' : 'Save Changes' }}
                            </button>
                            <button
                                type="button"
                                @click="showEditModal = false"
                                class="flex-1 px-6 py-3 border border-[#CBD5E1] text-[#1E293B] rounded-lg hover:bg-[#F9FAFB] transition-colors font-semibold"
                            >
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>