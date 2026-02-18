<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { ref, onMounted } from 'vue';

interface Props {
    projects: any[];
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Tasks', href: '/tasks' },
    { title: 'Create Task', href: '/tasks/create' },
    { title: 'Review', href: '/tasks/review' },
];

interface Task {
    id: number;
    title: string;
    description: string;
    category: string;
}

const projectId = ref('');
const projectName = ref('');
const tasks = ref<Task[]>([]);

const form = useForm({
    project_id: '',
    tasks: [] as Task[],
});

onMounted(() => {
    const pendingData = sessionStorage.getItem('pendingTasks');
    if (pendingData) {
        const data = JSON.parse(pendingData);
        projectId.value = data.project_id;
        tasks.value = data.tasks;
        form.project_id = data.project_id;
        form.tasks = data.tasks;

        // Get project name
        const project = props.projects.find(p => p.id == data.project_id);
        projectName.value = project?.name || '';
    } else {
        // No pending tasks, redirect back
        router.visit('/tasks/create');
    }
});

const getCategoryColor = (category: string) => {
    const colors = {
        frontend: 'bg-[#5B21B6]/10 text-[#5B21B6] border-[#5B21B6]/20',
        backend: 'bg-[#06B6D4]/10 text-[#06B6D4] border-[#06B6D4]/20',
        server: 'bg-[#F97316]/10 text-[#F97316] border-[#F97316]/20',
    };
    return colors[category as keyof typeof colors] || '';
};

const removeTask = (id: number) => {
    tasks.value = tasks.value.filter(task => task.id !== id);
    form.tasks = tasks.value;
    
    // Update session storage
    sessionStorage.setItem('pendingTasks', JSON.stringify({
        project_id: projectId.value,
        tasks: tasks.value
    }));

    // If no tasks left, go back
    if (tasks.value.length === 0) {
        router.visit('/tasks/create');
    }
};

const editTask = (id: number) => {
    const task = tasks.value.find(t => t.id === id);
    if (task) {
        // Store the task to be edited in sessionStorage
        sessionStorage.setItem('editingTask', JSON.stringify(task));
        
        // Remove it from the tasks list
        removeTask(id);
        
        // Go back to create page where it will be loaded into the form
        router.visit('/tasks/create');
    }
};

const goBack = () => {
    router.visit('/tasks/create');
};

const submit = () => {
    form.post('/tasks', {
        preserveScroll: true,
        onSuccess: () => {
            sessionStorage.removeItem('pendingTasks');
        },
    });
};
</script>

<template>
    <Head title="Review Tasks" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6 bg-[#F9FAFB]">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-[#1E293B]">Review Tasks</h1>
                    <p class="text-[#1E293B] mt-1">Review all tasks before submitting</p>
                </div>
                <button
                    @click="goBack"
                    class="px-4 py-2 text-[#1E293B] hover:bg-white rounded-lg transition-colors border border-[#CBD5E1]"
                >
                    ← Back to Add More
                </button>
            </div>

            <!-- Project Info -->
            <div class="bg-white rounded-xl border border-[#CBD5E1] shadow-sm p-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-lg bg-[#5B21B6]/10 flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#5B21B6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-[#64748B]">Project</p>
                        <p class="text-lg font-semibold text-[#1E293B]">{{ projectName }}</p>
                    </div>
                    <div class="ml-auto">
                        <p class="text-sm text-[#64748B]">Total Tasks</p>
                        <p class="text-2xl font-bold text-[#5B21B6]">{{ tasks.length }}</p>
                    </div>
                </div>
            </div>

            <!-- Tasks List -->
            <div class="grid grid-cols-1 gap-4">
                <div v-for="(task, index) in tasks" :key="task.id" 
                    class="bg-white rounded-xl border border-[#CBD5E1] shadow-sm p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-[#F1F5F9] flex items-center justify-center">
                                <span class="text-lg font-bold text-[#64748B]">{{ index + 1 }}</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-[#1E293B]">{{ task.title }}</h3>
                                <span class="inline-block mt-1 px-3 py-1 text-xs font-medium rounded-full border capitalize"
                                    :class="getCategoryColor(task.category)">
                                    {{ task.category }}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button
                                @click="editTask(task.id)"
                                class="text-[#5B21B6] hover:text-[#6D28D9] transition-colors p-2"
                                title="Edit task"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button
                                @click="removeTask(task.id)"
                                class="text-[#EF4444] hover:text-[#DC2626] transition-colors p-2"
                                title="Remove task"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <div v-if="task.description" class="mt-3 pl-13">
                        <p class="text-sm text-[#64748B] leading-relaxed">{{ task.description }}</p>
                    </div>
                    <div v-else class="mt-3 pl-13">
                        <p class="text-sm text-[#94A3B8] italic">No description provided</p>
                    </div>
                </div>
            </div>

            <!-- Submit Section -->
            <div class="bg-white rounded-xl border border-[#CBD5E1] shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-[#64748B] mb-1">Ready to submit?</p>
                        <p class="text-sm text-[#1E293B]">All tasks will be automatically assigned to the appropriate developers</p>
                    </div>
                    <button
                        @click="submit"
                        :disabled="form.processing"
                        class="px-8 py-3 bg-[#5B21B6] text-white rounded-lg hover:bg-[#6D28D9] transition-colors font-semibold disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        {{ form.processing ? 'Creating...' : 'Create All Tasks' }}
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
