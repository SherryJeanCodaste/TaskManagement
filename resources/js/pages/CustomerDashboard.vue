<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { tasks as customerTasks } from '@/routes/customer';
import customerTasksRoutes from '@/routes/customer/tasks';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/customer/dashboard' },
];

// Mock data - replace with actual props from backend
const stats = {
    totalTasks: 6,
    inProgress: 1,
    completed: 2,
};

const recentTasks = [
    { id: 1, title: 'Event Invitation Card', category: 'Graphic Design', date: '2026-02-08', status: 'pending', statusLabel: 'Pending' },
    { id: 2, title: 'Social Media Banner Pack', category: 'Graphic Design', date: '2026-02-07', status: 'assigned', statusLabel: 'Assigned' },
    { id: 3, title: 'Mobile App Dashboard UI', category: 'UI/UX Design', date: '2026-01-28', status: 'in-progress', statusLabel: 'In Progress' },
    { id: 4, title: 'E-commerce Checkout Flow', category: 'UI/UX Design', date: '2026-01-29', status: 'review', statusLabel: 'Review' },
];

// Modal state for create task
const showCreateTaskModal = ref(false);

const form = {
    taskTitle: '',
    taskDescription: '',
    category: '',
};

const handleSubmit = () => {
    // Handle form submission
    console.log('Creating task:', form);
    // Reset form
    form.taskTitle = '';
    form.taskDescription = '';
    form.category = '';
    showCreateTaskModal.value = false;
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <template #header-actions>
            <button 
                @click="showCreateTaskModal = true"
                class="px-4 py-2 bg-[#06B6D4] text-white rounded-lg hover:bg-[#0891B2] transition-colors font-semibold text-sm flex items-center gap-2 shadow-md"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create Task
            </button>
        </template>

        <div class="flex h-full flex-1 flex-col gap-6 p-8 bg-white">
            <!-- Summary Stats Cards -->
            <div class="flex gap-4">
                <!-- Total Tasks -->
                <div class="bg-white rounded-lg p-4 border border-[#E5E7EB] min-w-[200px]">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-[#3B82F6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <p class="text-xs text-[#9CA3AF] mb-1">Total Tasks</p>
                    <p class="text-2xl font-semibold text-[#111827]">{{ stats.totalTasks }}</p>
                </div>

                <!-- In Progress -->
                <div class="bg-white rounded-lg p-4 border border-[#E5E7EB] min-w-[200px]">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-[#F97316]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-xs text-[#9CA3AF] mb-1">In Progress</p>
                    <p class="text-2xl font-semibold text-[#111827]">{{ stats.inProgress }}</p>
                </div>

                <!-- Completed -->
                <div class="bg-white rounded-lg p-4 border border-[#E5E7EB] min-w-[200px]">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-[#10B981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-xs text-[#9CA3AF] mb-1">Completed</p>
                    <p class="text-2xl font-semibold text-[#111827]">{{ stats.completed }}</p>
                </div>
            </div>

            <!-- Recent Tasks Section -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-semibold text-[#111827]">Recent Tasks</h2>
                    <Link 
                        :href="customerTasks()"
                        class="text-sm text-[#6B7280] hover:text-[#111827] transition-colors"
                    >
                        View all →
                    </Link>
                </div>
                
                <div class="space-y-3">
                    <Link
                        v-for="task in recentTasks" 
                        :key="task.id"
                        :href="customerTasksRoutes.show(task.id).url"
                        class="block bg-white rounded-lg p-4 border border-[#E5E7EB] hover:border-[#D1D5DB] transition-colors cursor-pointer"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-[#111827] mb-2">{{ task.title }}</h3>
                                <div class="flex items-center gap-3 text-xs">
                                    <span class="text-[#8B5CF6] font-medium">{{ task.category }}</span>
                                    <span class="text-[#9CA3AF]">{{ task.date }}</span>
                                </div>
                            </div>
                            <span 
                                :class="{
                                    'bg-[#DBEAFE] text-[#1E40AF]': task.status === 'pending',
                                    'bg-[#FEF3C7] text-[#92400E]': task.status === 'assigned',
                                    'bg-[#FFEDD5] text-[#9A3412]': task.status === 'in-progress',
                                    'bg-[#E0E7FF] text-[#4338CA]': task.status === 'review'
                                }"
                                class="px-3 py-1 rounded-md text-xs font-medium"
                            >
                                {{ task.statusLabel }}
                            </span>
                        </div>
                    </Link>
                </div>
            </div>
        </div>

        <!-- Create Task Modal -->
        <div v-if="showCreateTaskModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="showCreateTaskModal = false">
            <div class="bg-white rounded-lg p-6 max-w-lg w-full mx-4 shadow-xl">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-[#06B6D4]/10 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-[#06B6D4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <h2 class="text-lg font-bold text-[#1E293B]">Create New Task</h2>
                    </div>
                    <button @click="showCreateTaskModal = false" class="p-1 hover:bg-[#F9FAFB] rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-[#1E293B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form @submit.prevent="handleSubmit" class="space-y-4">
                    <div>
                        <label for="taskTitle" class="block text-sm font-semibold text-[#1E293B] mb-1.5">Task Title *</label>
                        <input 
                            id="taskTitle"
                            v-model="form.taskTitle"
                            type="text" 
                            required
                            placeholder="What needs to be done?"
                            class="w-full px-3 py-2 text-sm border border-[#CBD5E1] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#06B6D4] focus:border-transparent"
                        />
                    </div>

                    <div>
                        <label for="taskDescription" class="block text-sm font-semibold text-[#1E293B] mb-1.5">Description (Optional)</label>
                        <textarea 
                            id="taskDescription"
                            v-model="form.taskDescription"
                            rows="3"
                            placeholder="Add more details..."
                            class="w-full px-3 py-2 text-sm border border-[#CBD5E1] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#06B6D4] focus:border-transparent"
                        ></textarea>
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-semibold text-[#1E293B] mb-1.5">Category *</label>
                        <div class="relative">
                            <select 
                                id="category"
                                v-model="form.category"
                                required
                                class="w-full px-3 py-2.5 text-sm border border-[#CBD5E1] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#06B6D4] focus:border-transparent appearance-none bg-white pr-10 cursor-pointer leading-relaxed"
                                style="line-height: 1.75;"
                            >
                                <option value="" disabled class="py-2">Select a category</option>
                                <option value="visual" class="py-3">🎨 Visual / Design Issue</option>
                                <option value="function" class="py-3">⚙️ Function / Feature Request</option>
                                <option value="system" class="py-3">🖥️ System / Technical Issue</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-4 h-4 text-[#1E293B]/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-[#06B6D4]/5 border border-[#06B6D4]/20 rounded-lg p-3">
                        <div class="flex gap-2">
                            <svg class="w-4 h-4 text-[#06B6D4] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-xs text-[#1E293B]/70">
                                Your task will be automatically assigned to the right team member.
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button 
                            type="button"
                            @click="showCreateTaskModal = false"
                            class="flex-1 px-4 py-2.5 bg-white border border-[#CBD5E1] text-[#1E293B] rounded-lg hover:bg-[#F9FAFB] transition-colors font-semibold text-sm"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit"
                            class="flex-1 px-4 py-2.5 bg-[#06B6D4] text-white rounded-lg hover:bg-[#0891B2] transition-colors font-semibold text-sm flex items-center justify-center gap-2 shadow-md"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Create Task
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
