<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

interface Props {
    userType: 'employee' | 'customer';
    projects?: Array<{ id: number; name: string; customer_id?: number | null }>;
}

const props = withDefaults(defineProps<Props>(), {
    projects: () => [],
});

// Filter projects without customers for better UX
const availableProjects = props.projects?.filter(p => !p.customer_id) || [];
const hasAvailableProjects = availableProjects.length > 0;

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Users', href: '/users' },
    { title: props.userType === 'employee' ? 'Add Employee' : 'Add Customer', href: '#' },
];

const form = useForm({
    name: '',
    email: '', // No default - admin must enter manually
    password: props.userType === 'employee' ? 'Password@123' : '', // Default password only for employees
    developer_type: 'frontend',
    project_id: null as number | null,
    // New project fields
    create_new_project: false,
    new_project_name: '',
    new_project_description: '',
});

const showPassword = ref(false);

const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value;
};

const submit = () => {
    if (props.userType === 'employee') {
        form.post('/users/employee', {
            onSuccess: () => {
                // Redirect handled by backend
            },
        });
    } else {
        form.post('/users/customer', {
            onSuccess: () => {
                // Redirect handled by backend
            },
        });
    }
};
</script>

<template>
    <Head :title="userType === 'employee' ? 'Add Employee' : 'Add Customer'" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col p-8 bg-white">
            <div class="w-full max-w-2xl mx-auto">
                <!-- Header -->
                <div class="mb-6">
                    <Link href="/users" class="inline-flex items-center gap-2 text-sm text-[#3B82F6] hover:text-[#2563EB] font-medium mb-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Back to users
                    </Link>
                    <h1 class="text-2xl font-bold text-[#111827]">
                        {{ userType === 'employee' ? 'Add New Employee' : 'Add New Customer' }}
                    </h1>
                    <p class="text-sm text-[#6B7280] mt-1">
                        {{ userType === 'employee' ? 'Create a new employee account (developer)' : 'Create a new customer account' }}
                    </p>
                </div>

                <!-- Form -->
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Full Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-[#111827] mb-2">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="name"
                            v-model="form.name"
                            type="text"
                            required
                            class="w-full px-4 py-3 text-sm border border-[#E5E7EB] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5B21B6] focus:border-transparent text-[#111827] bg-white"
                            placeholder="Enter full name"
                        />
                        <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-[#111827] mb-2">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            required
                            class="w-full px-4 py-3 text-sm border border-[#E5E7EB] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5B21B6] focus:border-transparent text-[#111827] bg-white"
                            placeholder="Enter email address"
                        />
                        <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
                    </div>

                    <!-- Developer Type (only for employees) -->
                    <div v-if="userType === 'employee'">
                        <label for="developer_type" class="block text-sm font-medium text-[#111827] mb-2">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="developer_type"
                            v-model="form.developer_type"
                            required
                            class="w-full px-4 py-3 text-sm border border-[#E5E7EB] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5B21B6] focus:border-transparent text-[#111827] bg-white"
                        >
                            <option value="frontend">Frontend Developer</option>
                            <option value="backend">Backend Developer</option>
                            <option value="server_administrator">Server Administrator</option>
                        </select>
                        <p v-if="form.errors.developer_type" class="mt-1 text-sm text-red-600">{{ form.errors.developer_type }}</p>
                    </div>

                    <!-- Project (only for customers) -->
                    <div v-if="userType === 'customer'">
                        <label for="project_id" class="block text-sm font-medium text-[#111827] mb-2">
                            Assign to Project <span class="text-red-500">*</span>
                        </label>
                        
                        <!-- Show message if no projects exist at all -->
                        <div v-if="projects.length === 0" class="mb-3 p-3 bg-[#FEF3C7] border border-[#F59E0B] rounded-lg">
                            <p class="text-sm text-[#92400E]">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                No projects available. Please create a new project below.
                            </p>
                        </div>

                        <!-- Show message if projects exist but all have customers -->
                        <div v-else-if="!hasAvailableProjects" class="mb-3 p-3 bg-[#DBEAFE] border border-[#3B82F6] rounded-lg">
                            <p class="text-sm text-[#1E40AF]">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                All existing projects already have customers assigned. Create a new project below.
                            </p>
                        </div>

                        <select
                            v-if="hasAvailableProjects"
                            id="project_id"
                            v-model="form.project_id"
                            :required="!form.create_new_project"
                            class="w-full px-4 py-3 text-sm border border-[#E5E7EB] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5B21B6] focus:border-transparent text-[#111827] bg-white"
                            @change="if (form.project_id === 'new') { form.create_new_project = true; form.project_id = null; }"
                        >
                            <option :value="null" disabled>Select a project</option>
                            <option v-for="project in availableProjects" :key="project.id" :value="project.id">
                                {{ project.name }}
                            </option>
                            <option value="new" class="font-semibold text-[#5B21B6]">+ Create New Project</option>
                        </select>

                        <!-- Button to create new project when no available projects -->
                        <button
                            v-if="!hasAvailableProjects && !form.create_new_project"
                            type="button"
                            @click="form.create_new_project = true"
                            class="w-full px-4 py-3 bg-[#5B21B6] text-white rounded-lg hover:bg-[#4C1D95] transition-colors font-semibold text-sm flex items-center justify-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Create New Project
                        </button>

                        <p v-if="form.errors.project_id && !form.create_new_project" class="mt-1 text-sm text-red-600">{{ form.errors.project_id }}</p>
                        <p v-if="hasAvailableProjects && !form.create_new_project" class="mt-1 text-xs text-[#6B7280]">Customer can only be assigned to one project</p>
                    </div>

                    <!-- New Project Fields (shown when "Create New Project" is selected) -->
                    <div v-if="userType === 'customer' && form.create_new_project" class="space-y-4 p-4 bg-[#F9FAFB] rounded-lg border border-[#E5E7EB]">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-semibold text-[#111827]">New Project Details</h3>
                            <button
                                type="button"
                                @click="form.create_new_project = false; form.new_project_name = ''; form.new_project_description = '';"
                                class="text-xs text-[#EF4444] hover:text-[#DC2626] font-medium"
                            >
                                Cancel
                            </button>
                        </div>

                        <div>
                            <label for="new_project_name" class="block text-sm font-medium text-[#111827] mb-2">
                                Project Name <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="new_project_name"
                                v-model="form.new_project_name"
                                type="text"
                                :required="form.create_new_project"
                                class="w-full px-4 py-3 text-sm border border-[#E5E7EB] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5B21B6] focus:border-transparent text-[#111827] bg-white"
                                placeholder="Enter project name"
                            />
                            <p v-if="form.errors.new_project_name" class="mt-1 text-sm text-red-600">{{ form.errors.new_project_name }}</p>
                        </div>

                        <div>
                            <label for="new_project_description" class="block text-sm font-medium text-[#111827] mb-2">
                                Project Description (Optional)
                            </label>
                            <textarea
                                id="new_project_description"
                                v-model="form.new_project_description"
                                rows="3"
                                class="w-full px-4 py-3 text-sm border border-[#E5E7EB] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5B21B6] focus:border-transparent text-[#111827] bg-white"
                                placeholder="Enter project description"
                            ></textarea>
                            <p v-if="form.errors.new_project_description" class="mt-1 text-sm text-red-600">{{ form.errors.new_project_description }}</p>
                        </div>

                        <p class="text-xs text-[#6B7280]">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            The project will be created and this customer will be assigned to it. You can assign developers to the project later.
                        </p>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-[#111827] mb-2">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input
                                id="password"
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'"
                                required
                                class="w-full px-4 py-3 pr-12 text-sm border border-[#E5E7EB] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5B21B6] focus:border-transparent text-[#111827] bg-white"
                                :placeholder="userType === 'employee' ? 'Default: Password@123' : 'Enter password (min. 8 characters)'"
                            />
                            <button
                                type="button"
                                @click="togglePasswordVisibility"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-[#6B7280] hover:text-[#111827] transition-colors"
                            >
                                <!-- Eye icon (show password) -->
                                <svg v-if="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <!-- Eye-off icon (hide password) -->
                                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                </svg>
                            </button>
                        </div>
                        <p v-if="form.errors.password" class="mt-1 text-sm text-red-600">{{ form.errors.password }}</p>
                        <p v-if="userType === 'employee'" class="mt-1 text-xs text-[#10B981]">Default password is set. Employee can change it after first login.</p>
                        <p v-else class="mt-1 text-xs text-[#6B7280]">Minimum 8 characters</p>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center gap-3 pt-4">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-3 bg-[#5B21B6] text-white rounded-lg hover:bg-[#4C1D95] transition-colors font-semibold text-sm disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            {{ form.processing ? 'Creating...' : (userType === 'employee' ? 'Add Employee' : 'Add Customer') }}
                        </button>
                        <Link
                            href="/users"
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
