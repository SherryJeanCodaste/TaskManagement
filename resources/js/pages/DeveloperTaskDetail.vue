<script setup lang="ts">
import { Head, Link, useForm, router } from '@inertiajs/vue3';
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
    progress?: number | null;
    progress_image?: string | null;
    created_at: string;
    project?: {
        id: number;
        name: string;
    };
    customer?: {
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
    { title: 'Dashboard', href: '/developer/dashboard' },
    { title: 'My Tasks', href: '/developer/tasks' },
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

// Debug: log the current status and step
console.log('Task status:', props.task.status, 'Current step:', currentStep);

const statusSteps = [
    { step: 1, label: 'Assigned', color: 'bg-[#3B82F6]', description: 'Task assigned to developer' },
    { step: 2, label: 'In Progress', color: 'bg-[#F97316]', description: 'Developer is working on it' },
    { step: 3, label: 'For Review', color: 'bg-[#8B5CF6]', description: 'Submitted for checking' },
    { step: 4, label: 'Completed', color: 'bg-[#10B981]', description: 'Approved and done' },
];

// Get next status based on current status
const getNextStatus = (currentStatus: string): string => {
    const statusFlow: Record<string, string> = {
        'pending': 'in-progress',
        'assigned': 'in-progress',
        'in-progress': 'review',
        'review': 'completed',
        'completed': 'completed' // Already at final stage
    };
    return statusFlow[currentStatus] || 'in-progress';
};

// Get automatic progress based on status
const getProgressForStatus = (status: string): number => {
    const progressMap: Record<string, number> = {
        'assigned': 0,
        'in-progress': 50,
        'review': 75,
        'completed': 100
    };
    return progressMap[status] || 0;
};

// Form for updating task - only need comment now
const form = useForm({
    comment: '',
    progress_image: null as File | null,
});

const imagePreview = ref<string | null>(null);

const handleImageUpload = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];
    
    if (file) {
        form.progress_image = file;
        
        // Create preview
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target?.result as string;
        };
        reader.readAsDataURL(file);
    }
};

const removeImage = () => {
    form.progress_image = null;
    imagePreview.value = null;
    // Reset file input
    const fileInput = document.getElementById('progress_image') as HTMLInputElement;
    if (fileInput) fileInput.value = '';
};

const updateTask = () => {
    const nextStatus = getNextStatus(props.task.status);
    const nextProgress = getProgressForStatus(nextStatus);
    
    console.log('Auto-updating task:', {
        currentStatus: props.task.status,
        nextStatus: nextStatus,
        nextProgress: nextProgress,
        comment: form.comment
    });
    
    form.transform((data) => ({
        ...data,
        status: nextStatus,
        progress: nextProgress,
    })).post(`/developer/tasks/${props.task.id}/update`, {
        onSuccess: () => {
            console.log('Update successful');
            // Force fresh page load with Inertia
            router.visit(`/developer/tasks/${props.task.id}`, {
                method: 'get',
                replace: true,
            });
        },
        onError: (errors) => {
            console.error('Update failed:', errors);
        },
    });
};


</script>

<template>
    <Head :title="task.title" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col p-8 bg-white">
            <div class="w-full max-w-4xl mx-auto space-y-6">
                <!-- Back Button -->
                <Link href="/developer/tasks" class="inline-flex items-center gap-2 text-sm text-[#6B7280] hover:text-[#111827] font-medium">
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

                <!-- Task Information -->
                <div class="bg-white rounded-lg p-6 border border-[#E5E7EB]">
                    <div class="flex items-start justify-between mb-4">
                        <h1 class="text-xl font-bold text-[#111827]">{{ task.title }}</h1>
                        <span 
                            :class="{
                                'bg-[#D1FAE5] text-[#065F46]': task.status === 'completed',
                                'bg-[#FFEDD5] text-[#9A3412]': task.status === 'in-progress',
                                'bg-[#DBEAFE] text-[#1E40AF]': task.status === 'pending' || task.status === 'assigned',
                                'bg-[#E0E7FF] text-[#4338CA]': task.status === 'review'
                            }"
                            class="px-3 py-1 rounded text-xs font-semibold capitalize"
                        >
                            {{ task.status === 'in-progress' ? 'In Progress' : task.status === 'review' ? 'For Review' : task.status === 'pending' ? 'Assigned' : task.status }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-xs text-[#9CA3AF] mb-1">Project</p>
                            <p class="text-sm font-medium text-[#111827]">{{ task.project?.name || 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-[#9CA3AF] mb-1">Customer</p>
                            <p class="text-sm font-medium text-[#111827]">{{ task.customer?.name || 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-[#9CA3AF] mb-1">Category</p>
                            <p class="text-sm font-medium text-[#111827]">{{ task.category }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-[#9CA3AF] mb-1">Created</p>
                            <p class="text-sm font-medium text-[#111827]">{{ task.created_at }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-[#9CA3AF] mb-1">Progress</p>
                            <p class="text-sm font-medium text-[#111827]">{{ getProgressForStatus(task.status) }}%</p>
                        </div>
                    </div>

                    <div class="border-t border-[#E5E7EB] pt-4">
                        <p class="text-sm text-[#6B7280] leading-relaxed">{{ task.description }}</p>
                        
                        <!-- Current Progress Image -->
                        <div v-if="task.progress_image" class="mt-4 pt-4 border-t border-[#E5E7EB]">
                            <p class="text-xs text-[#9CA3AF] mb-2">Current Progress Image</p>
                            <img :src="`/${task.progress_image}`" alt="Current progress" class="w-full max-w-md rounded-lg border border-[#E5E7EB]">
                        </div>
                    </div>
                </div>

                <!-- Update Progress Section -->
                <div class="bg-white rounded-lg p-6 border border-[#E5E7EB]">
                    <h2 class="text-base font-semibold text-[#111827] mb-4">Update Progress</h2>
                    
                    <!-- Current Status Info -->
                    <div class="mb-4 p-3 bg-[#F9FAFB] rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-[#6B7280] mb-1">Current Status</p>
                                <p class="text-sm font-semibold text-[#111827]">
                                    {{ task.status === 'in-progress' ? 'In Progress' : task.status === 'review' ? 'For Review' : task.status === 'pending' ? 'Assigned' : task.status === 'assigned' ? 'Assigned' : task.status.charAt(0).toUpperCase() + task.status.slice(1) }}
                                </p>
                            </div>
                            <div v-if="task.status !== 'completed'">
                                <p class="text-xs text-[#6B7280] mb-1">Next Status</p>
                                <p class="text-sm font-semibold text-[#3B82F6]">
                                    {{ getNextStatus(task.status) === 'in-progress' ? 'In Progress' : getNextStatus(task.status) === 'review' ? 'For Review' : getNextStatus(task.status).charAt(0).toUpperCase() + getNextStatus(task.status).slice(1) }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <form @submit.prevent="updateTask" class="space-y-4">
                        <!-- Auto Progress Info -->
                        <div class="p-4 bg-[#F0F9FF] border border-[#3B82F6] rounded-lg">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-5 h-5 text-[#3B82F6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h3 class="text-sm font-semibold text-[#1E40AF]">Automatic Progress Update</h3>
                            </div>
                            <p class="text-sm text-[#1E40AF]">
                                Clicking "Update Task" will automatically advance to: 
                                <span class="font-semibold">{{ getNextStatus(task.status) === 'in-progress' ? 'In Progress (50%)' : getNextStatus(task.status) === 'review' ? 'For Review (75%)' : getNextStatus(task.status) === 'completed' ? 'Completed (100%)' : getNextStatus(task.status) }}</span>
                            </p>
                        </div>

                        <!-- Comment Box -->
                        <div>
                            <label for="comment" class="block text-sm font-medium text-[#111827] mb-2">
                                Add Comment (Optional)
                            </label>
                            <textarea
                                id="comment"
                                v-model="form.comment"
                                rows="3"
                                placeholder="Share your progress update..."
                                class="w-full px-4 py-3 text-sm border border-[#E5E7EB] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent text-[#111827] bg-white"
                            ></textarea>
                        </div>

                        <!-- Image Upload -->
                        <div>
                            <label for="progress_image" class="block text-sm font-medium text-[#111827] mb-2">
                                Add Progress Image (Optional)
                            </label>
                            
                            <!-- Image Preview -->
                            <div v-if="imagePreview" class="mb-3 relative">
                                <img :src="imagePreview" alt="Progress preview" class="w-full max-w-md h-48 object-cover rounded-lg border border-[#E5E7EB]">
                                <button
                                    type="button"
                                    @click="removeImage"
                                    class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600"
                                >
                                    ×
                                </button>
                            </div>
                            
                            <!-- File Input -->
                            <div v-if="!imagePreview" class="border-2 border-dashed border-[#E5E7EB] rounded-lg p-6 text-center hover:border-[#3B82F6] transition-colors">
                                <svg class="w-8 h-8 text-[#9CA3AF] mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-sm text-[#6B7280] mb-2">Upload a progress screenshot or image</p>
                                <input
                                    id="progress_image"
                                    type="file"
                                    accept="image/*"
                                    @change="handleImageUpload"
                                    class="hidden"
                                >
                                <label
                                    for="progress_image"
                                    class="inline-flex items-center px-4 py-2 bg-[#F3F4F6] text-[#374151] rounded-lg hover:bg-[#E5E7EB] cursor-pointer text-sm font-medium"
                                >
                                    Choose Image
                                </label>
                            </div>
                        </div>

                        <!-- Update Button -->
                        <button
                            type="submit"
                            :disabled="form.processing || task.status === 'completed'"
                            class="w-full px-6 py-3 bg-[#3B82F6] text-white rounded-lg hover:bg-[#2563EB] transition-colors font-semibold text-sm disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            {{ form.processing ? 'Updating...' : task.status === 'completed' ? 'Task Completed' : 'Update Task' }}
                        </button>
                    </form>
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
                                <span class="text-sm font-semibold text-[#111827]">You</span>
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
