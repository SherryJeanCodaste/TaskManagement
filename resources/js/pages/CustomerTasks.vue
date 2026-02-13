<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

interface Task {
    id: number;
    title: string;
    description: string;
    category: string;
    status: string;
    priority: string;
    created_at: string;
    project?: {
        id: number;
        name: string;
    };
    assignedDeveloper?: {
        id: number;
        name: string;
    };
}

interface Props {
    tasks: Task[];
    filters: {
        status: string;
    };
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/customer/dashboard' },
    { title: 'Assigned Tasks', href: '/customer/tasks' },
];

const filterStatus = ref(props.filters.status);
const filterCategory = ref('');
const filterPriority = ref('');
</script>

<template>
    <Head title="Assigned Tasks" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <template #header-actions>
            <Link 
                href="/customer/tasks/create"
                class="px-4 py-2 bg-[#06B6D4] text-white rounded-lg hover:bg-[#0891B2] transition-colors font-semibold text-sm flex items-center gap-2 shadow-md"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create Task
            </Link>
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
                        <option value="assigned">Assigned</option>
                        <option value="in-progress">In Progress</option>
                        <option value="review">For Review</option>
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
                        v-for="task in tasks" 
                        :key="task.id"
                        :href="`/customer/tasks/${task.id}`"
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
                                        'bg-[#DBEAFE] text-[#1E40AF]': task.status === 'assigned' || task.status === 'pending',
                                        'bg-[#E0E7FF] text-[#4338CA]': task.status === 'review'
                                    }"
                                    class="px-2 py-1 rounded text-xs font-medium capitalize whitespace-nowrap"
                                >
                                    {{ task.status === 'in-progress' ? 'In Progress' : task.status === 'review' ? 'For Review' : task.status === 'pending' ? 'Assigned' : task.status }}
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
                                <span class="text-xs text-[#9CA3AF]">{{ task.created_at }}</span>
                            </div>
                        </div>
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
