<script setup lang="ts">
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Projects', href: '/admin/projects' },
    { title: 'Create Project', href: '#' },
];

// Hardcoded data for frontend-only version
const customers = [
    { id: 2, name: 'Test User' },
    { id: 5, name: 'John Doe' },
    { id: 6, name: 'Jane Smith' },
    { id: 3, name: 'Customer User' },
];

const frontendDevelopers = [
    { id: 8, name: 'John Smith' },
];

const backendDevelopers = [
    { id: 9, name: 'Sarah Johnson' },
];

const serverAdministrators = [
    { id: 10, name: 'Mike Wilson' },
];

const form = useForm({
    name: '',
    description: '',
    status: 'active' as 'active' | 'inactive',
    customer_id: null as number | null,
    frontend_developer_id: null as number | null,
    backend_developer_id: null as number | null,
    server_admin_id: null as number | null,
});

const showSuccess = ref(false);

function createProject() {
    // Frontend-only: Just show success and redirect
    // TODO: Connect to backend when opcache is disabled
    // form.post('/admin/projects');
    
    console.log('Project data:', form.data());
    showSuccess.value = true;
    
    setTimeout(() => {
        router.visit('/admin/projects');
    }, 1500);
}
</script>

<template>
    <Head title="Create Project" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col p-6 bg-white">
            <div class="w-full max-w-6xl mx-auto">
                <!-- Success Message -->
                <div v-if="showSuccess" class="mb-4 bg-[#D1FAE5] border border-[#10B981] text-[#065F46] px-4 py-3 rounded-lg">
                    Project created successfully! Redirecting...
                </div>

                <!-- Header -->
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-xl font-bold text-[#111827]">Create New Project</h1>
                            <p class="text-sm text-[#6B7280] mt-1">Fill in the project details and assign team members</p>
                        </div>
                        <Link 
                            href="/admin/projects" 
                            class="text-sm text-[#6B7280] hover:text-[#111827]"
                        >
                            Cancel
                        </Link>
                    </div>
                </div>

                <!-- Form -->
                <form @submit.prevent="createProject">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-4">
                            <!-- Project Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-[#111827] mb-1">
                                    Project Name <span class="text-[#EF4444]">*</span>
                                </label>
                                <input 
                                    id="name" 
                                    v-model="form.name" 
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-[#E5E7EB] rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#5B21B6]"
                                    placeholder="Enter project name"
                                />
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-[#111827] mb-1">
                                    Project Description
                                </label>
                                <textarea 
                                    id="description" 
                                    v-model="form.description" 
                                    rows="3"
                                    class="w-full px-3 py-2 border border-[#E5E7EB] rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#5B21B6] resize-none"
                                    placeholder="Optional description"
                                ></textarea>
                            </div>

                            <!-- Project Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-[#111827] mb-1">
                                    Project Status
                                </label>
                                <select 
                                    id="status"
                                    v-model="form.status"
                                    class="w-full px-3 py-2 border border-[#E5E7EB] rounded-lg text-sm text-[#111827] bg-white focus:outline-none focus:ring-2 focus:ring-[#5B21B6]"
                                >
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>

                            <!-- Customer -->
                            <div>
                                <label for="customer" class="block text-sm font-medium text-[#111827] mb-1">
                                    Customer <span class="text-[#EF4444]">*</span>
                                </label>
                                <select 
                                    id="customer"
                                    v-model="form.customer_id" 
                                    required
                                    class="w-full px-3 py-2 border border-[#E5E7EB] rounded-lg text-sm text-[#111827] bg-white focus:outline-none focus:ring-2 focus:ring-[#5B21B6]"
                                >
                                    <option :value="null">Select customer</option>
                                    <option v-for="customer in customers" :key="customer.id" :value="customer.id">
                                        {{ customer.name }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-4">
                            <div class="text-sm font-medium text-[#111827] mb-3">Assign Team Members</div>

                            <!-- Frontend Developer -->
                            <div>
                                <label for="frontend" class="block text-xs text-[#6B7280] mb-1">
                                    Frontend Developer
                                </label>
                                <select 
                                    id="frontend"
                                    v-model="form.frontend_developer_id"
                                    class="w-full px-3 py-2 border border-[#E5E7EB] rounded-lg text-sm text-[#111827] bg-white focus:outline-none focus:ring-2 focus:ring-[#5B21B6]"
                                >
                                    <option :value="null">None</option>
                                    <option v-for="dev in frontendDevelopers" :key="dev.id" :value="dev.id">
                                        {{ dev.name }}
                                    </option>
                                </select>
                            </div>

                            <!-- Backend Developer -->
                            <div>
                                <label for="backend" class="block text-xs text-[#6B7280] mb-1">
                                    Backend Developer
                                </label>
                                <select 
                                    id="backend"
                                    v-model="form.backend_developer_id"
                                    class="w-full px-3 py-2 border border-[#E5E7EB] rounded-lg text-sm text-[#111827] bg-white focus:outline-none focus:ring-2 focus:ring-[#5B21B6]"
                                >
                                    <option :value="null">None</option>
                                    <option v-for="dev in backendDevelopers" :key="dev.id" :value="dev.id">
                                        {{ dev.name }}
                                    </option>
                                </select>
                            </div>

                            <!-- Server Administrator -->
                            <div>
                                <label for="server" class="block text-xs text-[#6B7280] mb-1">
                                    Server Administrator
                                </label>
                                <select 
                                    id="server"
                                    v-model="form.server_admin_id"
                                    class="w-full px-3 py-2 border border-[#E5E7EB] rounded-lg text-sm text-[#111827] bg-white focus:outline-none focus:ring-2 focus:ring-[#5B21B6]"
                                >
                                    <option :value="null">None</option>
                                    <option v-for="admin in serverAdministrators" :key="admin.id" :value="admin.id">
                                        {{ admin.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-[#E5E7EB]">
                        <Link 
                            href="/admin/projects" 
                            class="px-4 py-2 border border-[#E5E7EB] text-[#111827] rounded-lg hover:bg-[#F9FAFB] text-sm font-medium"
                        >
                            Cancel
                        </Link>
                        <button 
                            type="submit" 
                            class="px-4 py-2 bg-[#5B21B6] text-white rounded-lg hover:bg-[#6D28D9] text-sm font-medium"
                        >
                            Create Project
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
