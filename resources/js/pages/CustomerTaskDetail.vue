<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

interface Task {
    id: number;
    title: string;
    description: string;
    category: string;
    priority: string;
    status: string;
    progress_image?: string | null;
    created_at: string;
    completion_proof?: string | null;
    project?: {
        id: number;
        name: string;
    };
    assignedDeveloper?: {
        id: number;
        name: string;
    };
    comments?: TaskComment[];
}

interface TaskComment {
    id: number;
    comment: string | null;
    image: string | null;
    status_update: string | null;
    progress_update: number | null;
    created_at: string;
    user: {
        id: number;
        name: string;
        role: string;
    };
}

interface Props {
    task: Task;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/customer/dashboard' },
    { title: 'Assigned Tasks', href: '/customer/tasks' },
    { title: props.task.title, href: '#' },
];

// Map status to step number (4-step flow)
const statusToStep: Record<string, number> = {
    'assigned': 1,
    'pending': 1, // Treat pending same as assigned
    'in-progress': 2,
    'review': 3,
    'completed': 4
};

const currentStep = statusToStep[props.task.status] || 1;

const statusSteps = [
    { step: 1, label: 'Assigned', color: 'bg-[#3B82F6]' },      // Blue
    { step: 2, label: 'In Progress', color: 'bg-[#F97316]' },  // Orange
    { step: 3, label: 'For Review', color: 'bg-[#8B5CF6]' },   // Purple
    { step: 4, label: 'Completed', color: 'bg-[#10B981]' },    // Green
];

// Get automatic progress based on status
const getProgressForStatus = (status: string): number => {
    const progressMap: Record<string, number> = {
        'assigned': 0,
        'pending': 0,
        'in-progress': 50,
        'review': 75,
        'completed': 100
    };
    return progressMap[status] || 0;
};


</script>

<template>
    <Head :title="task.title" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col p-8 bg-white">
            <div class="w-full max-w-4xl mx-auto space-y-6">
                <!-- Back Button -->
                <Link href="/customer/tasks" class="inline-flex items-center gap-2 text-sm text-[#6B7280] hover:text-[#111827] font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to tasks
                </Link>

                <!-- Progress Tracker -->
                <div class="relative pt-4 pb-2">
                    <!-- Progress Bar -->
                    <div class="absolute top-9 left-0 right-0 h-1 bg-[#E5E7EB]">
                        <div 
                            class="h-full bg-[#111827] transition-all duration-500"
                            :style="{ width: currentStep === 1 ? '0%' : `${((currentStep - 1) / 3) * 100}%` }"
                        ></div>
                    </div>

                    <!-- Steps -->
                    <div class="relative flex justify-between">
                        <div 
                            v-for="status in statusSteps" 
                            :key="status.step"
                            class="flex flex-col items-center"
                        >
                            <div 
                                :class="[
                                    'w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold text-sm transition-all',
                                    currentStep >= status.step ? status.color : 'bg-[#E5E7EB] text-[#9CA3AF]'
                                ]"
                            >
                                <svg v-if="currentStep > status.step" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span v-else>{{ status.step }}</span>
                            </div>
                            <span 
                                :class="[
                                    'text-xs mt-2 font-medium text-center',
                                    currentStep >= status.step ? 'text-[#111827]' : 'text-[#9CA3AF]'
                                ]"
                            >
                                {{ status.label }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Task Details Card -->
                <div class="bg-white rounded-lg p-6 border border-[#E5E7EB]">
                    <div class="flex items-start justify-between mb-4">
                        <h1 class="text-xl font-bold text-[#111827]">{{ task.title }}</h1>
                        <span 
                            :class="{
                                'bg-[#D1FAE5] text-[#065F46]': task.status === 'completed',
                                'bg-[#FFEDD5] text-[#9A3412]': task.status === 'in-progress',
                                'bg-[#FEF3C7] text-[#92400E]': task.status === 'assigned',
                                'bg-[#DBEAFE] text-[#1E40AF]': task.status === 'pending',
                                'bg-[#E0E7FF] text-[#4338CA]': task.status === 'review'
                            }"
                            class="px-3 py-1 rounded text-xs font-semibold capitalize"
                        >
                            {{ task.status === 'in-progress' ? 'In Progress' : task.status }}
                        </span>
                    </div>

                    <div class="flex items-center gap-3 mb-4">
                        <span class="px-2.5 py-1 bg-[#DBEAFE] text-[#1E40AF] text-xs font-medium rounded">{{ task.category }}</span>
                        <span 
                            :class="{
                                'bg-[#FEE2E2] text-[#991B1B]': task.priority === 'high',
                                'bg-[#FEF3C7] text-[#92400E]': task.priority === 'medium',
                                'bg-[#F3F4F6] text-[#374151]': task.priority === 'low'
                            }"
                            class="px-2.5 py-1 rounded text-xs font-medium"
                        >
                            {{ task.priority }}
                        </span>
                        <span class="px-2.5 py-1 bg-[#F0F9FF] text-[#1E40AF] text-xs font-medium rounded">{{ getProgressForStatus(task.status) }}% Complete</span>
                        <span class="text-xs text-[#9CA3AF]">Created {{ task.created_at }}</span>
                    </div>

                    <p class="text-sm text-[#6B7280] leading-relaxed">{{ task.description }}</p>
                </div>

                <!-- Progress Image (if available) -->
                <div v-if="task.progress_image" class="bg-white rounded-lg p-6 border border-[#E5E7EB]">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-5 h-5 text-[#111827]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 002 2z"></path>
                        </svg>
                        <h2 class="text-base font-semibold text-[#111827]">Progress Update</h2>
                    </div>
                    <img :src="`/${task.progress_image}`" alt="Progress update" class="w-full max-w-2xl rounded-lg border border-[#E5E7EB]">
                </div>

                <!-- Completion Proof (if available) -->
                <div v-if="task.completion_proof" class="bg-white rounded-lg p-6 border border-[#E5E7EB]">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-5 h-5 text-[#111827]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <h2 class="text-base font-semibold text-[#111827]">Completion Proof</h2>
                    </div>
                    <img 
                        :src="task.completion_proof" 
                        alt="Completion proof"
                        class="w-full rounded-lg"
                    />
                </div>

                <!-- Comments Section -->
                <div class="bg-white rounded-lg p-6 border border-[#E5E7EB]">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-5 h-5 text-[#111827]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <h2 class="text-base font-semibold text-[#111827]">Progress Updates</h2>
                    </div>

                    <div v-if="!task.comments || task.comments.length === 0" class="text-sm text-[#9CA3AF]">
                        No progress updates yet.
                    </div>

                    <div v-else class="space-y-4">
                        <div 
                            v-for="comment in task.comments" 
                            :key="comment.id"
                            class="bg-[#F9FAFB] rounded-lg p-4"
                        >
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-semibold text-[#111827]">Developer</span>
                                <span class="text-xs text-[#9CA3AF]">{{ new Date(comment.created_at).toLocaleDateString() }} at {{ new Date(comment.created_at).toLocaleTimeString() }}</span>
                            </div>
                            
                            <!-- Status/Progress Updates -->
                            <div v-if="comment.status_update || comment.progress_update" class="mb-2">
                                <div v-if="comment.status_update" class="inline-flex items-center gap-1 px-2 py-1 bg-[#3B82F6] text-white text-xs rounded mr-2">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    Status: {{ comment.status_update === 'in-progress' ? 'In Progress' : comment.status_update === 'review' ? 'For Review' : comment.status_update }}
                                </div>
                                <div v-if="comment.progress_update" class="inline-flex items-center gap-1 px-2 py-1 bg-[#10B981] text-white text-xs rounded">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    Progress: {{ comment.progress_update }}%
                                </div>
                            </div>
                            
                            <!-- Comment Text -->
                            <p v-if="comment.comment" class="text-sm text-[#6B7280] mb-3">{{ comment.comment }}</p>
                            
                            <!-- Comment Image -->
                            <div v-if="comment.image" class="mt-3">
                                <img :src="`/${comment.image}`" alt="Progress update" class="w-full max-w-md rounded-lg border border-[#E5E7EB]">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
