<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

interface Project {
    id: number;
    name: string;
}

interface Props {
    projects: Project[];
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/customer/dashboard' },
    { title: 'Assigned Tasks', href: '/customer/tasks' },
    { title: 'Create Task', href: '#' },
];

const form = useForm({
    title: '',
    description: '',
    category: '',
    priority: 'medium',
    project_id: props.projects.length > 0 ? props.projects[0].id : null,
});

const submit = () => {
    form.post('/customer/tasks', {
        onSuccess: () => {
            // Redirect handled by backend
        },
    });
};
</script>

<template>
    <Head title="Create Task" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col p-8 bg-white">
            <div class="w-full max-w-2xl mx-auto">
                <!-- Header -->
                <div class="mb-6">
                    <Link href="/customer/tasks" class="inline-flex items-center gap-2 text-sm text-[#3B82F6] hover:text-[#2563EB] font-medium mb-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Back to tasks
                    </Link>
                    <h1 class="text-2xl font-bold text-[#111827]">Create New Task</h1>
                    <p class="text-sm text-[#6B7280] mt-1">Fill in the details below to create a new task</p>
                </div>

                <!-- Form -->
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Task Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-[#111827] mb-2">
                            Task Title <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="title"
                            v-model="form.title"
                            type="text"
                            required
                            class="w-full px-4 py-3 text-sm border border-[#E5E7EB] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#06B6D4] focus:border-transparent text-[#111827] bg-white"
                            placeholder="What needs to be done?"
                        />
                        <p v-if="form.errors.title" class="mt-1 text-sm text-red-600">{{ form.errors.title }}</p>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-[#111827] mb-2">
                            Description (Optional)
                        </label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="4"
                            class="w-full px-4 py-3 text-sm border border-[#E5E7EB] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#06B6D4] focus:border-transparent text-[#111827] bg-white"
                            placeholder="Add more details about the task..."
                        ></textarea>
                        <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">{{ form.errors.description }}</p>
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-[#111827] mb-2">
                            Category <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="category"
                            v-model="form.category"
                            required
                            class="w-full px-4 py-3 text-sm border border-[#E5E7EB] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#06B6D4] focus:border-transparent text-[#111827] bg-white"
                        >
                            <option value="" disabled>Select a category</option>
                            <option value="frontend">Frontend Development</option>
                            <option value="backend">Backend Development</option>
                            <option value="server">Server Administration</option>
                        </select>
                        <p v-if="form.errors.category" class="mt-1 text-sm text-red-600">{{ form.errors.category }}</p>
                    </div>

                    <!-- Priority -->
                    <div>
                        <label for="priority" class="block text-sm font-medium text-[#111827] mb-2">
                            Priority <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="priority"
                            v-model="form.priority"
                            required
                            class="w-full px-4 py-3 text-sm border border-[#E5E7EB] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#06B6D4] focus:border-transparent text-[#111827] bg-white"
                        >
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                        <p v-if="form.errors.priority" class="mt-1 text-sm text-red-600">{{ form.errors.priority }}</p>
                    </div>

                    <!-- Project -->
                    <div v-if="projects.length > 0">
                        <label for="project_id" class="block text-sm font-medium text-[#111827] mb-2">
                            Project <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="project_id"
                            v-model="form.project_id"
                            required
                            class="w-full px-4 py-3 text-sm border border-[#E5E7EB] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#06B6D4] focus:border-transparent text-[#111827] bg-white"
                        >
                            <option v-for="project in projects" :key="project.id" :value="project.id">
                                {{ project.name }}
                            </option>
                        </select>
                        <p v-if="form.errors.project_id" class="mt-1 text-sm text-red-600">{{ form.errors.project_id }}</p>
                    </div>

                    <!-- Info Box -->
                    <div class="bg-[#DBEAFE] border border-[#3B82F6] rounded-lg p-4">
                        <div class="flex gap-3">
                            <svg class="w-5 h-5 text-[#3B82F6] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm text-[#1E40AF]">
                                Your task will be automatically assigned to the appropriate developer based on the category you select.
                            </p>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center gap-3 pt-4">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-3 bg-[#06B6D4] text-white rounded-lg hover:bg-[#0891B2] transition-colors font-semibold text-sm disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            {{ form.processing ? 'Creating...' : 'Create Task' }}
                        </button>
                        <Link
                            href="/customer/tasks"
                            class="px-6 py-3 bg-[#F3F4F6] text-[#111827] rounded-lg hover:bg-[#E5E7EB] transition-colors font-semibold text-sm"
                        >
                            Cancel
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
