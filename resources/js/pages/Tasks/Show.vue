<script setup lang="ts">
import { Head, router, usePage, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { computed, ref, onMounted } from 'vue';

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

const commentForm = useForm({
    comment: '',
});

const attachmentForm = useForm({
    type: 'file',
    link_url: '',
    file: null as File | null,
});

const autoResizeTextarea = (event: Event) => {
    const textarea = event.target as HTMLTextAreaElement;
    textarea.style.height = 'auto';
    textarea.style.height = textarea.scrollHeight + 'px';
};

const submitComment = () => {
    commentForm.post(`/tasks/${props.task.id}/comments`, {
        preserveScroll: true,
        onSuccess: () => {
            commentForm.reset();
            if (commentTextarea.value) {
                commentTextarea.value.style.height = 'auto';
            }
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
        attachmentForm.file = target.files[0];
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
            preserveScroll: true,
            onSuccess: () => {
                attachmentForm.reset();
                showUploadModal.value = false;
                uploadType.value = 'file';
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
            preserveScroll: true,
            forceFormData: true,
            onSuccess: () => {
                attachmentForm.reset();
                attachmentForm.file = null;
                showUploadModal.value = false;
                uploadType.value = 'file';
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
            preserveScroll: true,
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
</script>

<template>
    <Head :title="task.title" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4 bg-[#F9FAFB]">
            <!-- Compact Header -->
            <div class="bg-white rounded-xl border border-[#CBD5E1] shadow-sm p-4">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-2">
                            <h1 class="text-xl font-bold text-[#1E293B] truncate">{{ task.title }}</h1>
                            <span :class="categoryColor" class="px-2 py-1 rounded-full text-xs font-semibold capitalize flex-shrink-0">
                                {{ task.category }}
                            </span>
                        </div>
                        <div class="flex items-center gap-4 text-sm text-[#64748B]">
                            <span>Project: {{ task.project?.name || 'No project' }}</span>
                            <span>•</span>
                            <span>Created: {{ new Date(task.created_at).toLocaleDateString() }}</span>
                            <span v-if="task.customer">•</span>
                            <span v-if="task.customer">By: {{ task.customer.name }}</span>
                        </div>
                    </div>
                    <span :class="statusColor" class="px-3 py-1.5 rounded-full text-sm font-semibold capitalize flex-shrink-0">
                        {{ task.status.replace('_', ' ') }}
                    </span>
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
                                v-for="status in ['pending', 'in_progress', 'completed']"
                                :key="status"
                                @click="updateStatus(status)"
                                :class="[
                                    'w-full px-3 py-2 rounded-lg font-medium text-sm transition-all text-left',
                                    task.status === status
                                        ? status === 'completed' ? 'bg-[#22C55E] text-white'
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
                                        status === 'in_progress' ? 'bg-[#F97316]' : 'bg-[#EF4444]'
                                    ]"></div>
                                    {{ status.replace('_', ' ').toUpperCase() }}
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
                                        <a 
                                            v-if="attachment.type === 'link'"
                                            :href="attachment.url"
                                            target="_blank"
                                            class="font-medium text-[#5B21B6] hover:text-[#6D28D9] truncate block text-sm"
                                        >
                                            {{ attachment.name }}
                                        </a>
                                        <a 
                                            v-else
                                            :href="`/storage/${attachment.url}`"
                                            target="_blank"
                                            class="font-medium text-[#1E293B] hover:text-[#5B21B6] truncate block text-sm"
                                        >
                                            {{ attachment.name }}
                                        </a>
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
                    <div @click.stop class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4">
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
                                <input
                                    type="file"
                                    @change="handleFileSelect"
                                    :accept="uploadType === 'photo' ? 'image/*' : uploadType === 'video' ? 'video/*' : '*'"
                                    required
                                    class="w-full px-3 py-2 border border-[#CBD5E1] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5B21B6] focus:border-transparent text-[#1E293B] bg-white text-sm file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-medium file:bg-[#5B21B6] file:text-white hover:file:bg-[#6D28D9]"
                                />
                                <p class="text-xs text-[#64748B] mt-1">Max: 50MB</p>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="flex gap-3 pt-2">
                                <button
                                    type="submit"
                                    :disabled="attachmentForm.processing"
                                    class="flex-1 px-4 py-2 bg-[#5B21B6] text-white rounded-lg hover:bg-[#6D28D9] transition-colors font-medium text-sm disabled:opacity-50 disabled:cursor-not-allowed"
                                >
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

            <!-- Bottom Actions -->
            <div class="flex gap-3 justify-between">
                <a :href="'/tasks'" class="px-6 py-2 border border-[#CBD5E1] text-[#1E293B] rounded-lg hover:bg-[#F9FAFB] transition-colors font-medium text-sm">
                    ← Back to Tasks
                </a>
                <button v-if="isCustomer" @click="deleteTask" class="px-6 py-2 bg-[#EF4444] text-white rounded-lg hover:bg-[#DC2626] transition-colors font-medium text-sm">
                    Delete Task
                </button>
            </div>
        </div>
    </AppLayout>
</template>