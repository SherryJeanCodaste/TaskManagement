<script setup lang="ts">
import { Head, router, usePage, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { computed, ref, onMounted, onUnmounted } from 'vue';

interface Props {
    task: any;
}

const props = defineProps<Props>();
const page = usePage();
const user = computed(() => page.props.auth?.user);
const isCustomer = computed(() => user.value?.role === 'customer');
const isDeveloper = computed(() => user.value?.role !== 'customer');

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Tasks', href: '/tasks' },
    { title: props.task.title, href: `/tasks/${props.task.id}` },
];

const showUploadModal = ref(false);
const uploadType = ref('file');
const commentTextarea = ref<HTMLTextAreaElement | null>(null);

// Lightbox functionality
const showLightbox = ref(false);
const lightboxContent = ref<{
    type: string;
    url: string;
    name: string;
} | null>(null);

const openLightbox = (attachment: any) => {
    lightboxContent.value = {
        type: attachment.type,
        url: attachment.type === 'link' ? attachment.url : `/storage/${attachment.url}`,
        name: attachment.name
    };
    showLightbox.value = true;
    // Prevent body scroll when lightbox is open
    document.body.style.overflow = 'hidden';
};

const closeLightbox = () => {
    showLightbox.value = false;
    lightboxContent.value = null;
    // Restore body scroll
    document.body.style.overflow = 'auto';
};

// Handle keyboard events for lightbox
const handleKeydown = (event: KeyboardEvent) => {
    if (event.key === 'Escape' && showLightbox.value) {
        closeLightbox();
    }
};

// Add keyboard event listener
onMounted(() => {
    document.addEventListener('keydown', handleKeydown);
});

// Clean up event listener
onUnmounted(() => {
    document.removeEventListener('keydown', handleKeydown);
    // Restore body scroll if component is unmounted while lightbox is open
    document.body.style.overflow = 'auto';
});

const commentForm = useForm({
    comment: '',
});

const attachmentForm = useForm({
    type: 'file',
    link_url: '',
    file: null as File | null,
});

// Add loading state for file selection
const fileSelecting = ref(false);

const autoResizeTextarea = (event: Event) => {
    const textarea = event.target as HTMLTextAreaElement;
    textarea.style.height = 'auto';
    textarea.style.height = textarea.scrollHeight + 'px';
};

const submitComment = () => {
    commentForm.post(`/tasks/${props.task.id}/comments`, {
        onSuccess: () => {
            commentForm.reset();
            if (commentTextarea.value) {
                commentTextarea.value.style.height = 'auto';
            }
            // Reload to show the new comment
            router.reload();
        },
    });
};

// Scroll to comment if hash is present
onMounted(() => {
    const hash = window.location.hash;
    if (hash) {
        setTimeout(() => {
            const element = document.querySelector(hash);
            if (element) {
                element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                // Add highlight effect
                element.classList.add('ring-2', 'ring-[#5B21B6]', 'ring-offset-2');
                setTimeout(() => {
                    element.classList.remove('ring-2', 'ring-[#5B21B6]', 'ring-offset-2');
                }, 2000);
            }
        }, 100);
    }
});

const handleFileSelect = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        fileSelecting.value = true;
        // Small delay to show loading state
        setTimeout(() => {
            attachmentForm.file = target.files![0];
            fileSelecting.value = false;
        }, 300);
    }
};

const submitAttachment = () => {
    if (uploadType.value === 'link') {
        if (!attachmentForm.link_url) {
            alert('Please enter a URL');
            return;
        }
        
        router.post(`/tasks/${props.task.id}/attachments`, {
            type: 'link',
            link_url: attachmentForm.link_url,
        }, {
            onSuccess: () => {
                attachmentForm.reset();
                showUploadModal.value = false;
                uploadType.value = 'file';
                // Reload the page to show the new attachment
                router.reload();
            },
        });
    } else {
        if (!attachmentForm.file) {
            alert('Please select a file');
            return;
        }
        
        const formData = new FormData();
        formData.append('type', uploadType.value);
        formData.append('file', attachmentForm.file);
        
        router.post(`/tasks/${props.task.id}/attachments`, formData, {
            forceFormData: true,
            onSuccess: () => {
                attachmentForm.reset();
                attachmentForm.file = null;
                showUploadModal.value = false;
                uploadType.value = 'file';
                // Reload the page to show the new attachment
                router.reload();
            },
            onError: (errors) => {
                console.error('Upload error:', errors);
            },
        });
    }
};

const deleteAttachment = (attachmentId: number) => {
    if (confirm('Are you sure you want to delete this attachment?')) {
        router.delete(`/tasks/${props.task.id}/attachments/${attachmentId}`, {
            onSuccess: () => {
                // Reload the page to update the attachments list
                router.reload();
            },
        });
    }
};

const getFileIcon = (type: string) => {
    switch (type) {
        case 'link':
            return 'M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1';
        case 'photo':
            return 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z';
        case 'video':
            return 'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z';
        default:
            return 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z';
    }
};

const formatFileSize = (bytes: number) => {
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
    return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
};

// File type detection helpers
const isImageFile = (filename: string) => {
    const imageExtensions = ['.jpg', '.jpeg', '.png', '.gif', '.bmp', '.webp', '.svg'];
    return imageExtensions.some(ext => filename.toLowerCase().endsWith(ext));
};

const isVideoFile = (filename: string) => {
    const videoExtensions = ['.mp4', '.avi', '.mov', '.wmv', '.flv', '.webm', '.mkv'];
    return videoExtensions.some(ext => filename.toLowerCase().endsWith(ext));
};

const isPdfFile = (filename: string) => {
    return filename.toLowerCase().endsWith('.pdf');
};

const isTextFile = (filename: string) => {
    const textExtensions = ['.txt', '.md', '.json', '.xml', '.csv', '.log', '.js', '.ts', '.css', '.html', '.php', '.py', '.java', '.c', '.cpp', '.h'];
    return textExtensions.some(ext => filename.toLowerCase().endsWith(ext));
};

const getFileType = (filename: string) => {
    if (isImageFile(filename)) return 'Image File';
    if (isVideoFile(filename)) return 'Video File';
    if (isPdfFile(filename)) return 'PDF Document';
    if (isTextFile(filename)) return 'Text File';
    
    const extension = filename.split('.').pop()?.toUpperCase();
    return extension ? `${extension} File` : 'File';
};

const updateStatus = (status: string) => {
    router.put(`/tasks/${props.task.id}`, { status }, {
        preserveScroll: true,
    });
};

const deleteTask = () => {
    if (confirm('Are you sure you want to delete this task?')) {
        router.delete(`/tasks/${props.task.id}`);
    }
};

const statusColor = computed(() => {
    switch (props.task.status) {
        case 'completed':
            return 'bg-[#22C55E]/10 text-[#22C55E]';
        case 'for_review':
            return 'bg-[#8B5CF6]/10 text-[#8B5CF6]';
        case 'in_progress':
            return 'bg-[#F97316]/10 text-[#F97316]';
        default:
            return 'bg-[#EF4444]/10 text-[#EF4444]';
    }
});

const categoryColor = computed(() => {
    switch (props.task.category) {
        case 'frontend':
            return 'bg-[#5B21B6]/10 text-[#5B21B6]';
        case 'backend':
            return 'bg-[#06B6D4]/10 text-[#06B6D4]';
        default:
            return 'bg-[#F97316]/10 text-[#F97316]';
    }
});

// Progress tracker
const progressSteps = [
    { key: 'pending', label: 'Assigned' },
    { key: 'in_progress', label: 'In Progress' },
    { key: 'for_review', label: 'For Review' },
    { key: 'completed', label: 'Completed' }
];

const getStepStatus = (stepKey: string) => {
    const currentIndex = progressSteps.findIndex(step => step.key === props.task.status);
    const stepIndex = progressSteps.findIndex(step => step.key === stepKey);
    
    if (stepIndex < currentIndex) return 'completed';
    if (stepIndex === currentIndex) return 'current';
    return 'pending';
};

const progressWidth = computed(() => {
    const currentIndex = progressSteps.findIndex(step => step.key === props.task.status);
    const totalSteps = progressSteps.length - 1;
    return `${(currentIndex / totalSteps) * 100}%`;
});

// Timeline - combine comments and attachments
const timelineItems = computed(() => {
    const items = [];
    
    // Add comments
    if (props.task.comments) {
        props.task.comments.forEach(comment => {
            items.push({
                ...comment,
                type: 'comment'
            });
        });
    }
    
    // Add attachments
    if (props.task.attachments) {
        props.task.attachments.forEach(attachment => {
            items.push({
                ...attachment,
                type: 'attachment'
            });
        });
    }
    
    // Sort by created_at date
    return items.sort((a, b) => new Date(b.created_at).getTime() - new Date(a.created_at).getTime());
});

// Customer actions
const approveTask = () => {
    if (confirm('Are you sure you want to approve this task?')) {
        router.put(`/tasks/${props.task.id}`, { status: 'approved' }, {
            onSuccess: () => {
                router.reload();
            },
        });
    }
};

const requestRevision = () => {
    const reason = prompt('Please provide a reason for requesting revision:');
    if (reason) {
        // Add comment with revision request
        commentForm.comment = `Revision requested: ${reason}`;
        submitComment();
        // Update status back to in_progress
        router.put(`/tasks/${props.task.id}`, { status: 'in_progress' }, {
            onSuccess: () => {
                router.reload();
            },
        });
    }
};
</script>

<template>
    <Head :title="task.title" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6 bg-[#F9FAFB]">
            <!-- Back Button -->
            <div class="flex items-center justify-between">
                <a :href="'/tasks'" class="flex items-center gap-2 text-[#64748B] hover:text-[#5B21B6] transition-colors font-medium text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Tasks
                </a>
                <button v-if="isCustomer" @click="deleteTask" class="px-4 py-2 bg-[#EF4444] text-white rounded-lg hover:bg-[#DC2626] transition-colors font-medium text-sm">
                    Delete Task
                </button>
            </div>

            <!-- 1️⃣ Top Section - Task Info -->
            <div class="bg-white rounded-xl border border-[#CBD5E1] shadow-sm p-6">
                <!-- Task Title & Category -->
                <div class="flex items-start justify-between gap-4 mb-4">
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold text-[#1E293B] mb-2">{{ task.title }}</h1>
                        <div class="flex items-center gap-4 mb-3">
                            <span :class="categoryColor" class="px-3 py-1 rounded-full text-sm font-semibold capitalize">
                                {{ task.category }}
                            </span>
                            <span class="text-sm text-[#64748B]">
                                Created: {{ new Date(task.created_at).toLocaleDateString() }}
                            </span>
                            <span v-if="task.customer" class="text-sm text-[#64748B]">
                                By: {{ task.customer.name }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Task Description -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-[#1E293B] mb-2">Description</h3>
                    <p class="text-[#1E293B] whitespace-pre-wrap leading-relaxed">{{ task.description || 'No description provided' }}</p>
                </div>

                <!-- Progress Tracker -->
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-[#1E293B] mb-4">Progress Tracker</h3>
                    <div class="flex items-center justify-between relative">
                        <!-- Progress Line -->
                        <div class="absolute top-4 left-4 right-4 h-0.5 bg-[#E2E8F0]"></div>
                        <div 
                            class="absolute top-4 left-4 h-0.5 bg-[#5B21B6] transition-all duration-500"
                            :style="{ width: progressWidth }"
                        ></div>

                        <!-- Steps -->
                        <div v-for="(step, index) in progressSteps" :key="step.key" class="flex flex-col items-center relative z-10">
                            <div 
                                :class="[
                                    'w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold transition-all duration-300',
                                    getStepStatus(step.key) === 'completed' ? 'bg-[#22C55E] text-white' :
                                    getStepStatus(step.key) === 'current' ? 'bg-[#5B21B6] text-white' :
                                    'bg-[#E2E8F0] text-[#64748B]'
                                ]"
                            >
                                <svg v-if="getStepStatus(step.key) === 'completed'" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span v-else>{{ index + 1 }}</span>
                            </div>
                            <span 
                                :class="[
                                    'mt-2 text-xs font-medium text-center',
                                    getStepStatus(step.key) === 'completed' ? 'text-[#22C55E]' :
                                    getStepStatus(step.key) === 'current' ? 'text-[#5B21B6]' :
                                    'text-[#64748B]'
                                ]"
                            >
                                {{ step.label }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <!-- Left Column: Description & Comments -->
                <div class="lg:col-span-2 space-y-4">
                    <!-- Description -->
                    <div class="bg-white rounded-xl border border-[#CBD5E1] shadow-sm p-4">
                        <h2 class="text-lg font-bold text-[#1E293B] mb-3">Description</h2>
                        <div class="prose prose-sm max-w-none">
                            <p class="text-[#1E293B] whitespace-pre-wrap text-sm leading-relaxed">{{ task.description || 'No description provided' }}</p>
                        </div>
                    </div>

                    <!-- Comments Section -->
                    <div class="bg-white rounded-xl border border-[#CBD5E1] shadow-sm p-4">
                        <h2 class="text-lg font-bold text-[#1E293B] mb-4">Discussion</h2>
                        
                        <!-- Comment Form -->
                        <form @submit.prevent="submitComment" class="mb-4">
                            <div class="relative">
                                <textarea
                                    ref="commentTextarea"
                                    v-model="commentForm.comment"
                                    @input="autoResizeTextarea"
                                    @keydown.enter.exact.prevent="submitComment"
                                    rows="2"
                                    placeholder="Add a comment..."
                                    class="w-full px-3 py-2 pr-20 border border-[#CBD5E1] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5B21B6] focus:border-transparent resize-none text-sm text-[#1E293B] bg-white placeholder:text-[#94A3B8] overflow-hidden"
                                    required
                                ></textarea>
                                <button
                                    type="submit"
                                    :disabled="commentForm.processing || !commentForm.comment.trim()"
                                    class="absolute right-2 top-2 px-3 py-1 bg-[#5B21B6] text-white rounded-md hover:bg-[#6D28D9] transition-colors font-medium text-xs disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    {{ commentForm.processing ? 'Posting...' : 'Comment' }}
                                </button>
                            </div>
                        </form>

                        <!-- Comments List -->
                        <div v-if="task.comments && task.comments.length > 0" class="space-y-3 max-h-96 overflow-y-auto">
                            <div v-for="comment in task.comments" :key="comment.id" :id="`comment-${comment.id}`" class="border border-[#E2E8F0] rounded-lg p-3 scroll-mt-24">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-full bg-[#5B21B6]/10 flex items-center justify-center flex-shrink-0">
                                        <span class="text-[#5B21B6] font-semibold text-xs">
                                            {{ comment.user.name.charAt(0).toUpperCase() }}
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <p class="font-semibold text-[#1E293B] text-sm">{{ comment.user.name }}</p>
                                            <span 
                                                :class="{
                                                    'bg-[#06B6D4]/10 text-[#06B6D4]': comment.user.role === 'customer',
                                                    'bg-[#5B21B6]/10 text-[#5B21B6]': comment.user.role !== 'customer'
                                                }"
                                                class="px-1.5 py-0.5 rounded text-xs font-medium"
                                            >
                                                {{ comment.user.role === 'customer' ? 'Customer' : 'Dev' }}
                                            </span>
                                            <p class="text-xs text-[#64748B]">{{ new Date(comment.created_at).toLocaleString() }}</p>
                                        </div>
                                        <p class="text-[#1E293B] text-sm break-words">{{ comment.comment }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-center py-6 text-[#94A3B8]">
                            <svg class="w-8 h-8 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <p class="text-sm">No comments yet</p>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Work Submission & Status -->
                <div class="space-y-4">
                    <!-- Status Update (for developers) -->
                    <div v-if="!isCustomer" class="bg-white rounded-xl border border-[#CBD5E1] shadow-sm p-4">
                        <h3 class="text-lg font-bold text-[#1E293B] mb-3">Update Status</h3>
                        <div class="space-y-2">
                            <button
                                v-for="status in ['pending', 'in_progress', 'for_review', 'completed']"
                                :key="status"
                                @click="updateStatus(status)"
                                :class="[
                                    'w-full px-3 py-2 rounded-lg font-medium text-sm transition-all text-left',
                                    task.status === status
                                        ? status === 'completed' ? 'bg-[#22C55E] text-white'
                                        : status === 'for_review' ? 'bg-[#8B5CF6] text-white'
                                        : status === 'in_progress' ? 'bg-[#F97316] text-white'
                                        : 'bg-[#EF4444] text-white'
                                        : 'bg-[#F9FAFB] text-[#1E293B] hover:bg-[#E2E8F0] border border-[#CBD5E1]'
                                ]"
                            >
                                <div class="flex items-center gap-2">
                                    <div :class="[
                                        'w-2 h-2 rounded-full',
                                        task.status === status ? 'bg-white' : 
                                        status === 'completed' ? 'bg-[#22C55E]' :
                                        status === 'for_review' ? 'bg-[#8B5CF6]' :
                                        status === 'in_progress' ? 'bg-[#F97316]' : 'bg-[#EF4444]'
                                    ]"></div>
                                    {{ status === 'for_review' ? 'FOR REVIEW' : status.replace('_', ' ').toUpperCase() }}
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Work Submission Section -->
                    <div class="bg-white rounded-xl border border-[#CBD5E1] shadow-sm p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="text-lg font-bold text-[#1E293B]">Work Submission</h2>
                            <button
                                v-if="!isCustomer"
                                @click="showUploadModal = true"
                                class="px-3 py-1.5 bg-[#5B21B6] text-white rounded-lg hover:bg-[#6D28D9] transition-colors font-medium text-sm flex items-center gap-1.5"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Upload
                            </button>
                        </div>

                        <!-- Attachments List -->
                        <div v-if="task.attachments && task.attachments.length > 0" class="space-y-2 max-h-64 overflow-y-auto">
                            <div v-for="attachment in task.attachments" :key="attachment.id" class="border border-[#E2E8F0] rounded-lg p-3 hover:border-[#5B21B6]/30 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-[#5B21B6]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-[#5B21B6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="getFileIcon(attachment.type)"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <button 
                                            v-if="attachment.type === 'link'"
                                            @click="openLightbox(attachment)"
                                            class="font-medium text-[#5B21B6] hover:text-[#6D28D9] truncate block text-sm text-left w-full"
                                        >
                                            {{ attachment.name }}
                                        </button>
                                        <button 
                                            v-else
                                            @click="openLightbox(attachment)"
                                            class="font-medium text-[#1E293B] hover:text-[#5B21B6] truncate block text-sm text-left w-full"
                                        >
                                            {{ attachment.name }}
                                        </button>
                                        <div class="flex items-center gap-1 mt-0.5">
                                            <span class="text-xs text-[#64748B] capitalize">{{ attachment.type }}</span>
                                            <span v-if="attachment.file_size" class="text-xs text-[#64748B]">• {{ formatFileSize(attachment.file_size) }}</span>
                                        </div>
                                    </div>
                                    <button
                                        v-if="!isCustomer && attachment.user_id === user?.id"
                                        @click="deleteAttachment(attachment.id)"
                                        class="p-1 text-[#EF4444] hover:bg-[#EF4444]/10 rounded transition-colors"
                                        title="Delete"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div v-else class="border-2 border-dashed border-[#CBD5E1] rounded-lg p-6 text-center">
                            <svg class="w-8 h-8 mx-auto text-[#CBD5E1] mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <p class="text-[#1E293B]/60 text-sm mb-1">{{ isCustomer ? 'No work submitted yet' : 'No work uploaded' }}</p>
                            <p class="text-xs text-[#1E293B]/40">{{ isCustomer ? 'Developer will upload work here' : 'Click Upload to add files or links' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upload Modal -->
            <div v-if="showUploadModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" @click="showUploadModal = false">
                    <div @click.stop class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4 relative">
                        <!-- Upload Progress Overlay -->
                        <div v-if="attachmentForm.processing" class="absolute inset-0 bg-white/90 rounded-xl flex items-center justify-center z-10">
                            <div class="text-center">
                                <svg class="animate-spin w-8 h-8 text-[#5B21B6] mx-auto mb-3" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <p class="text-[#5B21B6] font-medium">Uploading your work...</p>
                                <p class="text-xs text-[#64748B] mt-1">Please wait while we process your file</p>
                            </div>
                        </div>

                        <div class="p-4 border-b border-[#E2E8F0]">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-[#1E293B]">Upload Work</h3>
                                <button @click="showUploadModal = false" class="text-[#64748B] hover:text-[#1E293B]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <form @submit.prevent="submitAttachment" class="p-4 space-y-4">
                            <!-- Error Messages -->
                            <div v-if="attachmentForm.errors && Object.keys(attachmentForm.errors).length > 0" class="bg-[#EF4444]/10 border border-[#EF4444] rounded-lg p-3">
                                <p v-for="(error, key) in attachmentForm.errors" :key="key" class="text-sm text-[#EF4444]">{{ error }}</p>
                            </div>

                            <!-- Upload Type Selection -->
                            <div>
                                <label class="block text-sm font-semibold text-[#1E293B] mb-2">Upload Type</label>
                                <div class="grid grid-cols-4 gap-2">
                                    <button
                                        type="button"
                                        v-for="type in ['link', 'file', 'photo', 'video']"
                                        :key="type"
                                        @click="uploadType = type"
                                        :class="[
                                            'p-2 border-2 rounded-lg transition-all text-center',
                                            uploadType === type
                                                ? 'border-[#5B21B6] bg-[#5B21B6]/5'
                                                : 'border-[#CBD5E1] hover:border-[#5B21B6]/50'
                                        ]"
                                    >
                                        <svg class="w-5 h-5 mx-auto mb-1" :class="uploadType === type ? 'text-[#5B21B6]' : 'text-[#64748B]'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="getFileIcon(type)"></path>
                                        </svg>
                                        <span class="text-xs font-medium capitalize" :class="uploadType === type ? 'text-[#5B21B6]' : 'text-[#64748B]'">{{ type }}</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Link Input -->
                            <div v-if="uploadType === 'link'">
                                <label class="block text-sm font-semibold text-[#1E293B] mb-2">Link URL</label>
                                <input
                                    v-model="attachmentForm.link_url"
                                    type="url"
                                    placeholder="https://example.com"
                                    required
                                    class="w-full px-3 py-2 border border-[#CBD5E1] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5B21B6] focus:border-transparent text-[#1E293B] bg-white text-sm"
                                />
                            </div>

                            <!-- File Input -->
                            <div v-else>
                                <label class="block text-sm font-semibold text-[#1E293B] mb-2">Select File</label>
                                <div class="relative">
                                    <input
                                        type="file"
                                        @change="handleFileSelect"
                                        :accept="uploadType === 'photo' ? 'image/*' : uploadType === 'video' ? 'video/*' : '*'"
                                        :disabled="fileSelecting"
                                        required
                                        class="w-full px-3 py-2 border border-[#CBD5E1] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5B21B6] focus:border-transparent text-[#1E293B] bg-white text-sm file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-medium file:bg-[#5B21B6] file:text-white hover:file:bg-[#6D28D9] disabled:opacity-50"
                                    />
                                    <!-- File Selection Loading -->
                                    <div v-if="fileSelecting" class="absolute inset-0 bg-white/80 rounded-lg flex items-center justify-center">
                                        <div class="flex items-center gap-2 text-[#5B21B6] text-sm">
                                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Processing file...
                                        </div>
                                    </div>
                                </div>
                                <p class="text-xs text-[#64748B] mt-1">Max: 50MB</p>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="flex gap-3 pt-2">
                                <button
                                    type="submit"
                                    :disabled="attachmentForm.processing"
                                    class="flex-1 px-4 py-2 bg-[#5B21B6] text-white rounded-lg hover:bg-[#6D28D9] transition-colors font-medium text-sm disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                                >
                                    <!-- Loading Spinner -->
                                    <svg 
                                        v-if="attachmentForm.processing" 
                                        class="animate-spin w-4 h-4" 
                                        fill="none" 
                                        viewBox="0 0 24 24"
                                    >
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <!-- Upload Icon -->
                                    <svg 
                                        v-else
                                        class="w-4 h-4" 
                                        fill="none" 
                                        stroke="currentColor" 
                                        viewBox="0 0 24 24"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    {{ attachmentForm.processing ? 'Uploading...' : 'Upload' }}
                                </button>
                                <button
                                    type="button"
                                    @click="showUploadModal = false"
                                    class="px-4 py-2 border border-[#CBD5E1] text-[#1E293B] rounded-lg hover:bg-[#F9FAFB] transition-colors font-medium text-sm"
                                >
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            <!-- Lightbox Modal -->
            <div v-if="showLightbox" class="fixed inset-0 bg-black/80 flex items-center justify-center z-50" @click="closeLightbox">
                <div class="relative max-w-4xl max-h-[90vh] w-full h-full flex items-center justify-center p-4">
                    <!-- Close Button -->
                    <button 
                        @click="closeLightbox"
                        class="absolute top-4 right-4 z-10 w-10 h-10 bg-black/50 hover:bg-black/70 text-white rounded-full flex items-center justify-center transition-colors"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>

                    <!-- Content -->
                    <div @click.stop class="w-full h-full flex items-center justify-center">
                        <!-- Image -->
                        <img 
                            v-if="lightboxContent?.type === 'photo' || (lightboxContent?.type === 'file' && isImageFile(lightboxContent.name))"
                            :src="lightboxContent.url"
                            :alt="lightboxContent.name"
                            class="max-w-full max-h-full object-contain rounded-lg shadow-2xl"
                        />
                        
                        <!-- Video -->
                        <video 
                            v-else-if="lightboxContent?.type === 'video' || (lightboxContent?.type === 'file' && isVideoFile(lightboxContent.name))"
                            :src="lightboxContent.url"
                            controls
                            class="max-w-full max-h-full rounded-lg shadow-2xl"
                        >
                            Your browser does not support the video tag.
                        </video>

                        <!-- PDF Viewer -->
                        <iframe
                            v-else-if="lightboxContent?.type === 'file' && isPdfFile(lightboxContent.name)"
                            :src="lightboxContent.url"
                            class="w-full h-full rounded-lg shadow-2xl bg-white"
                            frameborder="0"
                        ></iframe>

                        <!-- Text Files -->
                        <div
                            v-else-if="lightboxContent?.type === 'file' && isTextFile(lightboxContent.name)"
                            class="bg-white rounded-lg shadow-2xl p-6 max-w-4xl max-h-full overflow-auto"
                        >
                            <div class="mb-4 pb-4 border-b border-[#E2E8F0]">
                                <h3 class="text-lg font-bold text-[#1E293B]">{{ lightboxContent.name }}</h3>
                            </div>
                            <iframe
                                :src="lightboxContent.url"
                                class="w-full h-96 border border-[#E2E8F0] rounded"
                                frameborder="0"
                            ></iframe>
                        </div>
                        
                        <!-- Link Preview -->
                        <div 
                            v-else-if="lightboxContent?.type === 'link'"
                            class="bg-white rounded-lg shadow-2xl p-6 max-w-md w-full"
                        >
                            <div class="text-center">
                                <svg class="w-16 h-16 mx-auto text-[#5B21B6] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                </svg>
                                <h3 class="text-lg font-bold text-[#1E293B] mb-2">External Link</h3>
                                <p class="text-sm text-[#64748B] mb-4 break-all">{{ lightboxContent.name }}</p>
                                <div class="flex gap-3">
                                    <a 
                                        :href="lightboxContent.url" 
                                        target="_blank"
                                        class="flex-1 px-4 py-2 bg-[#5B21B6] text-white rounded-lg hover:bg-[#6D28D9] transition-colors font-medium text-sm"
                                    >
                                        Open Link
                                    </a>
                                    <button 
                                        @click="closeLightbox"
                                        class="px-4 py-2 border border-[#CBD5E1] text-[#1E293B] rounded-lg hover:bg-[#F9FAFB] transition-colors font-medium text-sm"
                                    >
                                        Close
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Other Files - Show preview with download option -->
                        <div 
                            v-else
                            class="bg-white rounded-lg shadow-2xl p-6 max-w-md w-full"
                        >
                            <div class="text-center">
                                <svg class="w-16 h-16 mx-auto text-[#5B21B6] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                <h3 class="text-lg font-bold text-[#1E293B] mb-2">{{ getFileType(lightboxContent?.name || '') }}</h3>
                                <p class="text-sm text-[#64748B] mb-4 break-all">{{ lightboxContent?.name }}</p>
                                <p class="text-xs text-[#94A3B8] mb-4">This file type cannot be previewed in the browser</p>
                                <div class="flex gap-3">
                                    <a 
                                        :href="lightboxContent?.url" 
                                        target="_blank"
                                        class="flex-1 px-4 py-2 bg-[#5B21B6] text-white rounded-lg hover:bg-[#6D28D9] transition-colors font-medium text-sm"
                                    >
                                        Download
                                    </a>
                                    <button 
                                        @click="closeLightbox"
                                        class="px-4 py-2 border border-[#CBD5E1] text-[#1E293B] rounded-lg hover:bg-[#F9FAFB] transition-colors font-medium text-sm"
                                    >
                                        Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </AppLayout>
</template>