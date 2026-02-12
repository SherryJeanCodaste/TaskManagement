<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

interface Props {
    taskId?: string | number;
}

const props = withDefaults(defineProps<Props>(), {
    taskId: 1
});

// Get task ID from URL or props - convert to number
const taskIdNum = Number(props.taskId) || 1;

// Mock data - would come from props in real implementation
const allTasks = [
    { id: 1, title: 'Mobile App Dashboard UI', description: 'Design the main dashboard screen for our fitness tracking mobile app. It should show daily steps, calories burned, workout history, and progress charts. Must be clean, modern, and easy to navigate.', category: 'UI/UX Design', priority: 'high', status: 'in-progress', customer: 'Acme Corp', createdAt: '2026-01-28' },
    { id: 2, title: 'API Integration', description: 'Integrate payment gateway API with the checkout system. Must handle multiple payment methods and ensure secure transactions.', category: 'Backend', priority: 'high', status: 'assigned', customer: 'Tech Solutions', createdAt: '2026-02-11' },
    { id: 3, title: 'E-commerce Checkout Flow', description: 'Design the complete checkout flow for our online store. Include cart review, shipping details, payment options, and order confirmation.', category: 'UI/UX Design', priority: 'medium', status: 'review', customer: 'Online Store', createdAt: '2026-01-29' },
    { id: 4, title: 'Social Media Banner Pack', description: 'Create a set of 5 social media banners for our upcoming product launch. Sizes needed: Instagram, Facebook, Twitter, LinkedIn.', category: 'Graphic Design', priority: 'medium', status: 'in-progress', customer: 'Marketing Campaign', createdAt: '2026-02-03' },
];

const task = ref(allTasks.find(t => t.id === taskIdNum) || allTasks[0]);

// Mark task as viewed when page loads
onMounted(() => {
    const viewedTasks = JSON.parse(localStorage.getItem('viewedTasks') || '[]');
    if (!viewedTasks.includes(taskIdNum)) {
        viewedTasks.push(taskIdNum);
        localStorage.setItem('viewedTasks', JSON.stringify(viewedTasks));
    }
});

// Map status to step number
const statusToStep: Record<string, number> = {
    'pending': 1,
    'assigned': 2,
    'in-progress': 3,
    'review': 4,
    'completed': 5
};

const currentStep = computed(() => statusToStep[task.value.status] || 1);

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/developer/dashboard' },
    { title: 'My Tasks', href: '/developer/tasks' },
    { title: task.value.title, href: '#' },
];

const statusSteps = [
    { step: 1, label: 'Pending', color: 'bg-[#3B82F6]', lightColor: 'bg-[#3B82F6]/30' },
    { step: 2, label: 'Assigned', color: 'bg-[#F59E0B]', lightColor: 'bg-[#F59E0B]/30' },
    { step: 3, label: 'In Progress', color: 'bg-[#F97316]', lightColor: 'bg-[#F97316]/30' },
    { step: 4, label: 'Review', color: 'bg-[#8B5CF6]', lightColor: 'bg-[#8B5CF6]/30' },
    { step: 5, label: 'Completed', color: 'bg-[#10B981]', lightColor: 'bg-[#10B981]/30' },
];

// Status control
const uploadedImage = ref<string | null>(null);
const comment = ref('');
const imageFile = ref<File | null>(null);

const handleImageUpload = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];
    if (file) {
        imageFile.value = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            uploadedImage.value = e.target?.result as string;
        };
        reader.readAsDataURL(file);
    }
};

const handleStartTask = () => {
    console.log('Starting task...');
    // Update status to in-progress
};

const handleSubmitForReview = () => {
    console.log('Submitting for review...');
    console.log('Image:', imageFile.value);
    console.log('Comment:', comment.value);
    // Update status to review with proof
};

const actionButton = computed(() => {
    if (task.value.status === 'assigned') {
        return { label: 'Start Task', action: handleStartTask, color: 'bg-[#F97316] hover:bg-[#EA580C]' };
    } else if (task.value.status === 'in-progress') {
        return { label: 'Submit for Review', action: handleSubmitForReview, color: 'bg-[#8B5CF6] hover:bg-[#7C3AED]' };
    }
    return null;
});
</script>

<template>
    <Head :title="task.title" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col p-8 bg-[#F9FAFB]">
            <div class="w-full max-w-5xl mx-auto space-y-6">
                <!-- Back Button -->
                <Link href="/developer/dashboard" class="inline-flex items-center gap-2 text-sm text-[#3B82F6] hover:text-[#2563EB] font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to dashboard
                </Link>

                <!-- Progress Tracker -->
                <div class="bg-white rounded-lg p-6 border border-[#E5E7EB]">
                    <h2 class="text-sm font-semibold text-[#6B7280] uppercase tracking-wider mb-6">Task Progress</h2>
                    <div class="relative">
                        <!-- Progress Bar -->
                        <div class="absolute top-5 left-0 right-0 h-1 bg-[#E5E7EB]">
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
                                        'w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold text-sm transition-all',
                                        currentStep >= status.step ? status.color : status.lightColor
                                    ]"
                                >
                                    <svg v-if="currentStep > status.step" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span v-else>{{ status.step }}</span>
                                </div>
                                <span 
                                    :class="[
                                        'text-xs mt-2 font-medium',
                                        currentStep >= status.step ? 'text-[#111827]' : 'text-[#9CA3AF]'
                                    ]"
                                >
                                    {{ status.label }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Task Details Card -->
                <div class="bg-white rounded-lg p-6 border border-[#E5E7EB]">
                    <div class="flex items-start justify-between mb-4">
                        <h1 class="text-2xl font-semibold text-[#111827]">{{ task.title }}</h1>
                        <span 
                            :class="{
                                'bg-[#DBEAFE] text-[#1E40AF]': task.status === 'assigned',
                                'bg-[#FFEDD5] text-[#9A3412]': task.status === 'in-progress',
                                'bg-[#E0E7FF] text-[#4338CA]': task.status === 'review',
                                'bg-[#D1FAE5] text-[#065F46]': task.status === 'completed'
                            }"
                            class="px-3 py-1.5 rounded text-sm font-medium capitalize"
                        >
                            {{ task.status === 'in-progress' ? 'In Progress' : task.status }}
                        </span>
                    </div>

                    <div class="flex items-center gap-4 mb-4">
                        <span class="text-[#8B5CF6] text-sm font-medium">{{ task.category }}</span>
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
                        <span class="text-sm text-[#6B7280]">Customer: {{ task.customer }}</span>
                        <span class="text-sm text-[#9CA3AF]">Created {{ task.createdAt }}</span>
                    </div>

                    <div class="border-t border-[#E5E7EB] pt-4">
                        <h3 class="text-sm font-semibold text-[#111827] mb-2">Description</h3>
                        <p class="text-sm text-[#6B7280] leading-relaxed">{{ task.description }}</p>
                    </div>
                </div>

                <!-- Status Control Section -->
                <div v-if="actionButton" class="bg-white rounded-lg p-6 border border-[#E5E7EB]">
                    <h2 class="text-lg font-semibold text-[#111827] mb-4">Update Status</h2>
                    
                    <!-- Upload Section (only for in-progress) -->
                    <div v-if="task.status === 'in-progress'" class="space-y-4 mb-6">
                        <!-- Image Upload -->
                        <div>
                            <label class="block text-sm font-medium text-[#111827] mb-2">Upload Proof (Screenshot/Image)</label>
                            <div class="border-2 border-dashed border-[#E5E7EB] rounded-lg p-6 text-center">
                                <input 
                                    type="file" 
                                    accept="image/*"
                                    @change="handleImageUpload"
                                    class="hidden"
                                    id="imageUpload"
                                />
                                <label 
                                    for="imageUpload"
                                    class="cursor-pointer inline-flex flex-col items-center"
                                >
                                    <svg class="w-12 h-12 text-[#9CA3AF] mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-sm text-[#3B82F6] font-medium">Click to upload image</span>
                                    <span class="text-xs text-[#9CA3AF] mt-1">PNG, JPG up to 10MB</span>
                                </label>
                            </div>
                            
                            <!-- Image Preview -->
                            <div v-if="uploadedImage" class="mt-4">
                                <img :src="uploadedImage" alt="Preview" class="max-w-md rounded-lg border border-[#E5E7EB]" />
                            </div>
                        </div>

                        <!-- Comment -->
                        <div>
                            <label for="comment" class="block text-sm font-medium text-[#111827] mb-2">Add Comment (Optional)</label>
                            <textarea 
                                id="comment"
                                v-model="comment"
                                rows="4"
                                placeholder="Explain what you've completed or any notes..."
                                class="w-full px-4 py-3 text-sm border border-[#E5E7EB] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent"
                            ></textarea>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <button 
                        @click="actionButton.action"
                        :class="actionButton.color"
                        class="w-full px-6 py-3 text-white rounded-lg transition-colors font-semibold text-sm flex items-center justify-center gap-2"
                    >
                        <svg v-if="task.status === 'assigned'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ actionButton.label }}
                    </button>
                </div>

                <!-- Locked Status Message -->
                <div v-else-if="task.status === 'review' || task.status === 'completed'" class="bg-[#F9FAFB] rounded-lg p-6 border border-[#E5E7EB]">
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6 text-[#6B7280]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-[#111827]">
                                {{ task.status === 'review' ? 'Task Under Review' : 'Task Completed' }}
                            </p>
                            <p class="text-sm text-[#6B7280] mt-1">
                                {{ task.status === 'review' ? 'Waiting for customer/admin approval.' : 'This task has been completed and approved.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
