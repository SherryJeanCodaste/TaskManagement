<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import customerTasksRoutes from '@/routes/customer/tasks';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/customer/dashboard' },
    { title: 'Assigned Tasks', href: '/customer/tasks' },
];

// Mock data
const myTasks = [
    { id: 1, title: 'Company Logo Redesign', description: 'We need a modern refresh of our company logo. The current logo feels outdated and doesn\'t reflect our...', category: 'Graphic Design', status: 'completed', priority: 'high', createdAt: '2026-01-15' },
    { id: 2, title: 'Mobile App Dashboard UI', description: 'Design the main dashboard screen for our fitness tracking mobile app. It should show daily steps...', category: 'UI/UX Design', status: 'in-progress', priority: 'high', createdAt: '2026-01-28' },
    { id: 3, title: 'Social Media Banner Pack', description: 'Create a set of 5 social media banners for our upcoming product launch. Sizes needed: Instagram...', category: 'Graphic Design', status: 'assigned', priority: 'medium', createdAt: '2026-02-03' },
    { id: 4, title: 'E-commerce Checkout Flow', description: 'Design the complete checkout flow for our online store. Include cart review, shipping details...', category: 'UI/UX Design', status: 'review', priority: 'medium', createdAt: '2026-01-20' },
    { id: 5, title: 'Event Invitation Card', description: 'Design an elegant digital invitation card for our annual company gala. Should include event detail...', category: 'Graphic Design', status: 'pending', priority: 'low', createdAt: '2026-02-08' },
    { id: 6, title: 'Landing Page Redesign', description: 'Redesign our SaaS product landing page. Focus on clear value proposition, feature highlights...', category: 'UI/UX Design', status: 'completed', priority: 'high', createdAt: '2026-01-10' },
];

const filterStatus = ref('');
const filterCategory = ref('');
const filterPriority = ref('');

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
    <Head title="Assigned Tasks" />

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

        <div class="flex h-full flex-1 flex-col p-8 bg-white">
            <div class="w-full max-w-6xl mx-auto space-y-6">
                <!-- Header -->
                <div>
                    <h1 class="text-xl font-semibold text-[#111827] mb-6">Assigned Tasks</h1>
                </div>

                <!-- Filters -->
                <div class="flex gap-3 mb-6">
                    <select 
                        v-model="filterStatus"
                        class="px-4 py-2 text-sm border border-[#E5E7EB] rounded-lg focus:outline-none bg-white text-[#111827]"
                    >
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="assigned">Assigned</option>
                        <option value="in-progress">In Progress</option>
                        <option value="review">Review</option>
                        <option value="completed">Completed</option>
                    </select>
                    <select 
                        v-model="filterCategory"
                        class="px-4 py-2 text-sm border border-[#E5E7EB] rounded-lg focus:outline-none bg-white text-[#111827]"
                    >
                        <option value="">All Categories</option>
                        <option value="graphic">Graphic Design</option>
                        <option value="uiux">UI/UX Design</option>
                    </select>
                    <select 
                        v-model="filterPriority"
                        class="px-4 py-2 text-sm border border-[#E5E7EB] rounded-lg focus:outline-none bg-white text-[#111827]"
                    >
                        <option value="">All Priorities</option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>
                </div>

                <!-- Tasks Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <Link
                        v-for="task in myTasks" 
                        :key="task.id"
                        :href="customerTasksRoutes.show(task.id).url"
                        class="block bg-white rounded-lg p-4 border border-[#E5E7EB] hover:border-[#D1D5DB] transition-colors cursor-pointer"
                    >
                        <div class="space-y-3">
                            <!-- Task Header -->
                            <div class="flex items-start justify-between gap-2">
                                <h3 class="text-sm font-semibold text-[#111827] flex-1">{{ task.title }}</h3>
                                <span 
                                    :class="{
                                        'bg-[#D1FAE5] text-[#065F46]': task.status === 'completed',
                                        'bg-[#FFEDD5] text-[#9A3412]': task.status === 'in-progress',
                                        'bg-[#FEF3C7] text-[#92400E]': task.status === 'assigned',
                                        'bg-[#DBEAFE] text-[#1E40AF]': task.status === 'pending',
                                        'bg-[#E0E7FF] text-[#4338CA]': task.status === 'review'
                                    }"
                                    class="px-2 py-1 rounded text-xs font-medium capitalize whitespace-nowrap"
                                >
                                    {{ task.status === 'in-progress' ? 'In Progress' : task.status }}
                                </span>
                            </div>
                            
                            <!-- Task Description -->
                            <p class="text-xs text-[#6B7280] line-clamp-2 leading-relaxed">{{ task.description }}</p>
                            
                            <!-- Task Meta -->
                            <div class="flex items-center justify-between pt-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-[#8B5CF6] text-xs font-medium">
                                        {{ task.category }}
                                    </span>
                                    <span 
                                        :class="{
                                            'bg-[#FEE2E2] text-[#991B1B]': task.priority === 'high',
                                            'bg-[#FEF3C7] text-[#92400E]': task.priority === 'medium',
                                            'bg-[#F3F4F6] text-[#374151]': task.priority === 'low'
                                        }"
                                        class="px-2 py-0.5 rounded text-xs font-medium"
                                    >
                                        {{ task.priority }}
                                    </span>
                                </div>
                                <span class="text-xs text-[#9CA3AF]">{{ task.createdAt }}</span>
                            </div>
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
