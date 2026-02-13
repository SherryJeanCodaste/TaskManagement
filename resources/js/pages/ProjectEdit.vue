<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

interface Project {
    id: number;
    name: string;
    description: string;
    status: 'active' | 'inactive';
    customer_id: number;
    frontend_developer_id: number | null;
    backend_developer_id: number | null;
    server_admin_id: number | null;
}

interface User {
    id: number;
    name: string;
    developer_type?: string;
}

interface Props {
    project: Project;
}

const props = defineProps<Props>();

const customers = ref<User[]>([]);
const frontendDevelopers = ref<User[]>([]);
const backendDevelopers = ref<User[]>([]);
const serverAdministrators = ref<User[]>([]);
const loading = ref(true);

const form = useForm({
    name: props.project.name,
    description: props.project.description,
    status: props.project.status,
    customer_id: props.project.customer_id,
    frontend_developer_id: props.project.frontend_developer_id,
    backend_developer_id: props.project.backend_developer_id,
    server_admin_id: props.project.server_admin_id,
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Projects', href: '/admin/projects' },
    { title: 'Edit Project', href: '#' },
];

// Fetch project data and users
onMounted(async () => {
    try {
        const response = await fetch(`/admin/projects/${props.project.id}/edit`);
        const data = await response.json();
        
        customers.value = data.customers;
        frontendDevelopers.value = data.frontendDevelopers;
        backendDevelopers.value = data.backendDevelopers;
        serverAdministrators.value = data.serverAdministrators;
    } catch (error) {
        console.error('Error fetching project data:', error);
    } finally {
        loading.value = false;
    }
});

function updateProject() {
    form.put(`/admin/projects/${props.project.id}`);
}
</script>

<template>
    <Head title="Edit Project" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col p-6 bg-white">
            <div class="w-full max-w-4xl mx-auto">
                <!-- Header -->
                <div class="mb-6">
                    <h1 class="text-xl font-bold text-[#111827] mb-2">Edit Project</h1>
                    <p class="text-sm text-[#6B7280]">Update project details and team assignments</p>
                </div>

                <!-- Loading State -->
                <div v-if="loading" class="text-center py-8">
                    <div class="text-sm text-[#6B7280]">Loading project data...</div>
                </div>

                <!-- Form -->
                <form v-else @submit.prevent="updateProject" class="space-y-6">
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
                                    class="w-full px-3 py-2 border border-[#E5E7EB] rounded-lg text-sm text-[#111827] bg-white focus:outline-none focus:ring-2 focus:ring-[#5B21B6]"
                                />
                                <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
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
                                    class="w-full px-3 py-2 border border-[#E5E7EB] rounded-lg text-sm text-[#111827] bg-white focus:outline-none focus:ring-2 focus:ring-[#5B21B6] resize-none"
                                ></textarea>
                                <div v-if="form.errors.description" class="text-red-500 text-xs mt-1">{{ form.errors.description }}</div>
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
                                <div v-if="form.errors.status" class="text-red-500 text-xs mt-1">{{ form.errors.status }}</div>
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
                                <div v-if="form.errors.customer_id" class="text-red-500 text-xs mt-1">{{ form.errors.customer_id }}</div>
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
                                <div v-if="form.errors.frontend_developer_id" class="text-red-500 text-xs mt-1">{{ form.errors.frontend_developer_id }}</div>
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
                                <div v-if="form.errors.backend_developer_id" class="text-red-500 text-xs mt-1">{{ form.errors.backend_developer_id }}</div>
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
                                <div v-if="form.errors.server_admin_id" class="text-red-500 text-xs mt-1">{{ form.errors.server_admin_id }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-3 pt-6 border-t border-[#E5E7EB]">
                        <Link 
                            href="/admin/projects" 
                            class="px-4 py-2 border border-[#E5E7EB] text-[#111827] rounded-lg hover:bg-[#F9FAFB] text-sm font-medium"
                        >
                            Cancel
                        </Link>
                        <button 
                            type="submit" 
                            :disabled="form.processing"
                            class="px-4 py-2 bg-[#5B21B6] text-white rounded-lg hover:bg-[#6D28D9] text-sm font-medium disabled:opacity-50"
                        >
                            {{ form.processing ? 'Updating...' : 'Update Project' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>