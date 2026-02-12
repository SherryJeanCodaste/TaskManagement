<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

// Mock data - would come from props in real implementation
const task = {
    id: 1,
    title: 'Design Homepage',
    description: 'Create a modern, responsive homepage design for the e-commerce platform. Should include hero section, featured products, testimonials, and call-to-action sections.',
    project: 'E-Commerce Platform',
    category: 'Frontend',
    priority: 'high',
    status: 'in-progress',
    createdAt: '2026-02-08',
    dueDate: '2026-02-15',
    customer: 'John Smith',
    assignedDeveloper: 'Alice Johnson',
    completionProof: null,
    comments: [
        { id: 1, author: 'Alice Johnson', message: 'Started working on the homepage layout. Will share initial mockups by tomorrow.', date: '2026-02-09' },
        { id: 2, author: 'System', message: 'Task status updated to In Progress.', date: '2026-02-09' }
    ]
};

// Map status to step number
const statusToStep: Record<string, number> = {
    'pending': 1,
    'assigned': 2,
    'in-progress': 3,
    'review': 4,
    'completed': 5
};

const currentStep = statusToStep[task.status] || 1;

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Tasks', href: '/tasks' },
    { title: task.title, href: '#' },
];

const statusSteps = [
    { step: 1, label: 'Pending', color: 'bg-[#3B82F6]', lightColor: 'bg-[#3B82F6]/30' },
    { step: 2, label: 'Assigned', color: 'bg-[#F59E0B]', lightColor: 'bg-[#F59E0B]/30' },
    { step: 3, label: 'In Progress', color: 'bg-[#F97316]', lightColor: 'bg-[#F97316]/30' },
    { step: 4, label: 'Review', color: 'bg-[#8B5CF6]', lightColor: 'bg-[#8B5CF6]/30' },
    { step: 5, label: 'Completed', color: 'bg-[#10B981]', lightColor: 'bg-[#10B981]/30' },
];

// Get developer box color based on category
const getDeveloperColor = (category: string) => {
    if (category === 'Frontend') return { bg: 'bg-[#EFF6FF]', icon: 'bg-[#3B82F6]', text: 'text-[#3B82F6]' };
    if (category === 'Backend') return { bg: 'bg-[#FFF7ED]', icon: 'bg-[#F97316]', text: 'text-[#F97316]' };
    return { bg: 'bg-[#F5F3FF]', icon: 'bg-[#8B5CF6]', text: 'text-[#8B5CF6]' };
};

const devColor = getDeveloperColor(task.category);
</script>

<template>
    <Head :title="task.title" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col p-6 bg-white">
            <div class="w-full max-w-4xl mx-auto space-y-4">
                <!-- Back Button -->
                <Link href="/tasks" class="inline-flex items-center gap-2 text-sm text-[#111827] hover:text-[#374151]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to tasks
                </Link>

                <!-- Progress Tracker -->
                <div class="relative">
                    <!-- Progress Bar -->
                    <div class="absolute top-4 left-0 right-0 h-0.5 bg-[#E5E7EB]">
                        <div 
                            class="h-full bg-[#111827] transition-all duration-500"
                            :style="{ width: currentStep === 1 ? '0%' : `${((currentStep - 1) / 4) * 100}%` }"
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
                                    'w-8 h-8 rounded-full flex items-center justify-center text-white font-semibold text-xs transition-all',
                                    currentStep >= status.step ? status.color : status.lightColor
                                ]"
                            >
                                <svg v-if="currentStep > status.step" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span v-else>{{ status.step }}</span>
                            </div>
                            <span 
                                :class="[
                                    'text-[10px] mt-1.5 font-medium',
                                    currentStep >= status.step ? 'text-[#111827]' : 'text-[#9CA3AF]'
                                ]"
                            >
                                {{ status.label }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Task Details Card -->
                <div class="bg-white rounded-lg p-4 border border-[#E5E7EB]">
                    <div class="flex items-start justify-between mb-3">
                        <h1 class="text-lg font-semibold text-[#111827]">{{ task.title }}</h1>
                        <span 
                            :class="{
                                'bg-[#D1FAE5] text-[#065F46]': task.status === 'completed',
                                'bg-[#FFEDD5] text-[#9A3412]': task.status === 'in-progress',
                                'bg-[#FEF3C7] text-[#92400E]': task.status === 'assigned',
                                'bg-[#DBEAFE] text-[#1E40AF]': task.status === 'pending',
                                'bg-[#E0E7FF] text-[#4338CA]': task.status === 'review'
                            }"
                            class="px-2 py-1 rounded text-[10px] font-medium capitalize"
                        >
                            {{ task.status === 'in-progress' ? 'In Progress' : task.status }}
                        </span>
                    </div>

                    <div class="flex items-center gap-2 mb-3 flex-wrap">
                        <span class="text-xs text-[#6B7280]">Project:</span>
                        <span class="text-xs font-medium text-[#111827]">{{ task.project }}</span>
                        <span class="text-xs text-[#E5E7EB]">|</span>
                        <span 
                            :class="{
                                'bg-[#3B82F6]/10 text-[#3B82F6]': task.category === 'Frontend',
                                'bg-[#F97316]/10 text-[#F97316]': task.category === 'Backend',
                                'bg-[#8B5CF6]/10 text-[#8B5CF6]': task.category === 'Server'
                            }"
                            class="px-2 py-0.5 rounded text-[10px] font-semibold"
                        >
                            {{ task.category }}
                        </span>
                        <span 
                            :class="{
                                'bg-[#FEE2E2] text-[#991B1B]': task.priority === 'high',
                                'bg-[#FEF3C7] text-[#92400E]': task.priority === 'medium',
                                'bg-[#F3F4F6] text-[#374151]': task.priority === 'low'
                            }"
                            class="px-2 py-0.5 rounded text-[10px] font-medium capitalize"
                        >
                            {{ task.priority }}
                        </span>
                    </div>

                    <div class="flex items-center gap-3 mb-3 text-xs text-[#9CA3AF]">
                        <span>Created: {{ task.createdAt }}</span>
                        <span>•</span>
                        <span>Due: {{ task.dueDate }}</span>
                    </div>

                    <p class="text-sm text-[#6B7280] leading-relaxed">{{ task.description }}</p>
                </div>

                <!-- Customer & Developer Info -->
                <div class="grid grid-cols-2 gap-3">
                    <!-- Customer -->
                    <div class="bg-white rounded-lg p-4 border border-[#E5E7EB]">
                        <p class="text-[10px] text-[#6B7280] mb-2 font-medium">Customer</p>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-[#E5E7EB] rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-[#6B7280]" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-[#111827]">{{ task.customer }}</span>
                        </div>
                    </div>

                    <!-- Assigned Developer -->
                    <div class="bg-white rounded-lg p-4 border border-[#E5E7EB]">
                        <p class="text-[10px] text-[#6B7280] mb-2 font-medium">Assigned Developer</p>
                        <div :class="devColor.bg" class="flex items-center gap-2 p-2 rounded">
                            <div :class="devColor.icon" class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-white text-[10px] font-bold">
                                    {{ task.category === 'Frontend' ? 'FE' : task.category === 'Backend' ? 'BE' : 'SA' }}
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-[#111827] truncate">{{ task.assignedDeveloper }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Completion Proof (if available) -->
                <div v-if="task.completionProof" class="bg-white rounded-lg p-4 border border-[#E5E7EB]">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-4 h-4 text-[#111827]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <h2 class="text-sm font-semibold text-[#111827]">Completion Proof</h2>
                    </div>
                    <img 
                        :src="task.completionProof" 
                        alt="Completion proof"
                        class="w-full rounded-lg"
                    />
                </div>

                <!-- Comments Section -->
                <div class="bg-white rounded-lg p-4 border border-[#E5E7EB]">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-4 h-4 text-[#111827]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <h2 class="text-sm font-semibold text-[#111827]">Comments</h2>
                    </div>

                    <div v-if="task.comments.length === 0" class="text-xs text-[#9CA3AF]">
                        No comments yet.
                    </div>

                    <div v-else class="space-y-2">
                        <div 
                            v-for="comment in task.comments" 
                            :key="comment.id"
                            class="bg-[#F9FAFB] rounded-lg p-3"
                        >
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-xs font-medium text-[#111827]">{{ comment.author }}</span>
                                <span class="text-[10px] text-[#9CA3AF]">{{ comment.date }}</span>
                            </div>
                            <p class="text-xs text-[#6B7280]">{{ comment.message }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
