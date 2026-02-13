<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

interface Task {
    id: number;
    title: string;
    description: string;
    category: string;
    priority: string;
    status: string;
    created_at: string;
    project?: {
        id: number;
        name: string;
    };
}

interface Props {
    tasks: Task[];
    filters: {
        status: string;
        category: string;
        priority: string;
    };
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/developer/dashboard' },
    { title: 'My Tasks', href: '/developer/tasks' },
];

const statusFilter = ref(props.filters.status);
const categoryFilter = ref(props.filters.category);
const priorityFilter = ref(props.filters.priority);

const getStatusColor = (status: string) => {
    const colors: Record<string, string> = {
        'completed': 'bg-[#D1FAE5] text-[#065F46]',
        'in-progress': 'bg-[#FFEDD5] text-[#9A3412]',
        'assigned': 'bg-[#DBEAFE] text-[#1E40AF]',
        'review': 'bg-[#E0E7FF] text-[#4338CA]',
        'pending': 'bg-[#DBEAFE] text-[#1E40AF]',
    };
    return colors[status] || 'bg-[#F3F4F6] text-[#374151]';
};

const getStatusLabel = (status: string) => {
    const labels: Record<string, string> = {
        'completed': 'Completed',
        'in-progress': 'In Progress',
        'assigned': 'Assigned',
        'review': 'For Review',
        'pending': 'Assigned',
    };
    return labels[status] || status;
};

const getPriorityColor = (priority: string) => {
    const colors: Record<string, string> = {
        'high': 'bg-[#FEE2E2] text-[#991B1B]',
        'medium': 'bg-[#FEF3C7] text-[#92400E]',
        'low': 'bg-[#F3F4F6] text-[#374151]',
    };
    return colors[priority] || 'bg-[#F3F4F6] text-[#374151]';
};

const getCategoryColor = (category: string) => {
    const colors: Record<string, string> = {
        'frontend': 'bg-[#D1FAE5] text-[#065F46]',
        'backend': 'bg-[#FFEDD5] text-[#9A3412]',
        'server': 'bg-[#E0E7FF] text-[#4338CA]',
    };
    return colors[category] || 'bg-[#F3F4F6] text-[#374151]';
};
</script>

<template>
    <Head title="My Tasks" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col p-6 bg-white">
            <div class="w-full max-w-7xl mx-auto">
                <!-- Header -->
                <div class="mb-6">
                    <h1 class="text-xl font-bold text-[#111827]">My Tasks</h1>
                </div>

                <!-- Filters -->
                <div class="flex gap-4 mb-6">
                    <select 
                        v-model="statusFilter"
                        class="px-3 py-2 border border-[#E5E7EB] rounded-lg text-sm text-[#111827] bg-white focus:outline-none focus:ring-2 focus:ring-[#5B21B6]"
                    >
                        <option value="all">All Statuses</option>
                        <option value="assigned">Assigned</option>
                        <option value="in_progress">In Progress</option>
                        <option value="review">For Review</option>
                        <option value="completed">Completed</option>
                    </select>

                    <select 
                        v-model="categoryFilter"
                        class="px-3 py-2 border border-[#E5E7EB] rounded-lg text-sm text-[#111827] bg-white focus:outline-none focus:ring-2 focus:ring-[#5B21B6]"
                    >
                        <option value="all">All Categories</option>
                        <option value="UI/UX Design">UI/UX Design</option>
                        <option value="Graphic Design">Graphic Design</option>
                        <option value="Backend">Backend</option>
                        <option value="Frontend">Frontend</option>
                    </select>

                    <select 
                        v-model="priorityFilter"
                        class="px-3 py-2 border border-[#E5E7EB] rounded-lg text-sm text-[#111827] bg-white focus:outline-none focus:ring-2 focus:ring-[#5B21B6]"
                    >
                        <option value="all">All Priorities</option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>
                </div>

                <!-- Tasks Grid -->
                <div v-if="tasks.length === 0" class="text-center py-12">
                    <p class="text-[#6B7280]">No tasks assigned yet.</p>
                </div>

                <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <Link
                        v-for="task in tasks"
                        :key="task.id"
                        :href="`/developer/tasks/${task.id}`"
                        class="block bg-white rounded-lg border border-[#E5E7EB] p-4 hover:shadow-md transition-shadow"
                    >
                        <!-- Header -->
                        <div class="flex items-start justify-between gap-2 mb-3">
                            <h3 class="text-sm font-semibold text-[#111827] flex-1">{{ task.title }}</h3>
                            <span 
                                :class="getStatusColor(task.status)"
                                class="px-2 py-1 rounded text-xs font-medium whitespace-nowrap"
                            >
                                {{ getStatusLabel(task.status) }}
                            </span>
                        </div>

                        <!-- Description -->
                        <p class="text-xs text-[#6B7280] mb-3 line-clamp-2">
                            {{ task.description }}
                        </p>

                        <!-- Tags -->
                        <div class="flex items-center gap-2 mb-3">
                            <span 
                                :class="getCategoryColor(task.category)"
                                class="px-2 py-1 rounded text-xs font-medium capitalize"
                            >
                                {{ task.category }}
                            </span>
                            <span 
                                :class="getPriorityColor(task.priority)"
                                class="px-2 py-1 rounded text-xs font-medium capitalize"
                            >
                                {{ task.priority }}
                            </span>
                        </div>

                        <!-- Project -->
                        <div class="text-xs text-[#6B7280]">
                            {{ task.project?.name || 'No project' }}
                        </div>
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
