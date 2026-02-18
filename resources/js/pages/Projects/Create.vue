<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Projects', href: '/projects' },
    { title: 'Create Project', href: '/projects/create' },
];

const form = useForm({
    name: '',
    description: '',
});

const submit = () => {
    form.post('/projects', {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Create Project" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6 bg-[#F9FAFB]">
            <!-- Header -->
            <div>
                <h1 class="text-3xl font-bold text-[#1E293B]">Create New Project</h1>
                <p class="text-[#1E293B] mt-1">Create a new project to organize your tasks</p>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-xl border border-[#CBD5E1] shadow-sm p-6 max-w-2xl">
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Project Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-[#1E293B] mb-2">
                            Project Name <span class="text-[#EF4444]">*</span>
                        </label>
                        <input
                            id="name"
                            v-model="form.name"
                            type="text"
                            required
                            class="w-full px-4 py-3 border border-[#CBD5E1] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5B21B6] focus:border-transparent text-[#1E293B] bg-white"
                            placeholder="Enter project name"
                        />
                        <p v-if="form.errors.name" class="mt-1 text-sm text-[#EF4444]">{{ form.errors.name }}</p>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-semibold text-[#1E293B] mb-2">
                            Description
                        </label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="4"
                            class="w-full px-4 py-3 border border-[#CBD5E1] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5B21B6] focus:border-transparent resize-none text-[#1E293B] bg-white"
                            placeholder="Enter project description (optional)"
                        ></textarea>
                        <p v-if="form.errors.description" class="mt-1 text-sm text-[#EF4444]">{{ form.errors.description }}</p>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-4 pt-4">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-3 bg-[#5B21B6] text-white rounded-lg hover:bg-[#6D28D9] transition-colors font-semibold disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            {{ form.processing ? 'Creating...' : 'Create Project' }}
                        </button>
                        <a
                            :href="'/projects'"
                            class="px-6 py-3 border border-[#CBD5E1] text-[#1E293B] rounded-lg hover:bg-[#F9FAFB] transition-colors font-semibold"
                        >
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
