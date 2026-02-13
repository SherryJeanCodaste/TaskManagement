<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';

interface Project {
    id: number;
    name: string;
    description: string;
    status: 'active' | 'inactive';
    customer: string;
    frontend_developer: string | null;
    backend_developer: string | null;
    server_admin: string | null;
    total_tasks: number;
    overall_progress: number;
}

interface Stats {
    total_projects: number;
    active_projects: number;
    total_users: number;
    total_tasks: number;
    pending_tasks: number;
    in_progress_tasks: number;
    completed_tasks: number;
}

defineProps<{
    stats?: Stats;
    projects?: Project[];
}>();

const showDeleteModal = ref(false);
const showEditModal = ref(false);
const selectedProject = ref<Project | null>(null);
const editForm = ref({
    name: '',
    description: '',
    customer_id: null,
    frontend_developer_id: null,
    backend_developer_id: null,
    server_admin_id: null,
    status: 'active'
});
const customers = ref([]);
const frontendDevelopers = ref([]);
const backendDevelopers = ref([]);
const serverAdministrators = ref([]);

// Functions
async function openEditModal(project: Project) {
    selectedProject.value = project;
    
    // Fetch project data and form options
    try {
        const response = await fetch(`/admin/projects/${project.id}/edit`);
        const data = await response.json();
        
        // Populate form with current project data
        editForm.value = {
            name: data.project.name,
            description: data.project.description || '',
            customer_id: data.project.customer_id,
            frontend_developer_id: data.project.frontend_developer_id,
            backend_developer_id: data.project.backend_developer_id,
            server_admin_id: data.project.server_admin_id,
            status: data.project.status
        };
        
        // Set dropdown options
        customers.value = data.customers;
        frontendDevelopers.value = data.frontendDevelopers;
        backendDevelopers.value = data.backendDevelopers;
        serverAdministrators.value = data.serverAdministrators;
        
        showEditModal.value = true;
    } catch (error) {
        console.error('Error fetching project data:', error);
    }
}

function closeEditModal() {
    showEditModal.value = false;
    selectedProject.value = null;
    editForm.value = {
        name: '',
        description: '',
        customer_id: null,
        frontend_developer_id: null,
        backend_developer_id: null,
        server_admin_id: null,
        status: 'active'
    };
}

function updateProject() {
    if (!selectedProject.value) return;
    
    router.put(`/admin/projects/${selectedProject.value.id}`, editForm.value, {
        onSuccess: () => {
            closeEditModal();
        },
        preserveScroll: true,
    });
}

function archiveProject(project: Project) {
    const action = project.status === 'active' ? 'archive' : 'activate';
    router.post(`/admin/projects/${project.id}/${action}`, {}, {
        preserveScroll: true,
    });
}

function openDeleteModal(project: Project) {
    selectedProject.value = project;
    showDeleteModal.value = true;
}

function closeDeleteModal() {
    showDeleteModal.value = false;
    selectedProject.value = null;
}

function confirmDelete() {
    if (!selectedProject.value) return;
    
    router.delete(`/admin/projects/${selectedProject.value.id}`, {
        onSuccess: () => {
            closeDeleteModal();
        },
    });
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];
</script>

<template>
    <Head title="Admin Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col p-6 bg-white">
            <div class="w-full max-w-7xl mx-auto space-y-6">
                <!-- Header -->
                <div class="mb-6">
                    <h1 class="text-xl font-bold text-[#111827] mb-2">Admin Dashboard</h1>
                    <p class="text-sm text-[#6B7280]">Manage projects, users, and system-wide task activity.</p>
                </div>

                <!-- Summary Stats Cards -->
                <div class="grid grid-cols-4 gap-4 mb-6">
                    <!-- Total Projects -->
                    <div class="bg-white rounded-lg p-3 border border-[#E5E7EB]">
                        <p class="text-xs text-[#9CA3AF] mb-1">Total Projects</p>
                        <p class="text-xl font-semibold text-[#111827]">{{ stats?.total_projects || 0 }}</p>
                    </div>

                    <!-- Active Projects -->
                    <div class="bg-white rounded-lg p-3 border border-[#E5E7EB]">
                        <p class="text-xs text-[#9CA3AF] mb-1">Active Projects</p>
                        <p class="text-xl font-semibold text-[#111827]">{{ stats?.active_projects || 0 }}</p>
                    </div>

                    <!-- Total Users -->
                    <div class="bg-white rounded-lg p-3 border border-[#E5E7EB]">
                        <p class="text-xs text-[#9CA3AF] mb-1">Total Users</p>
                        <p class="text-xl font-semibold text-[#111827]">{{ stats?.total_users || 0 }}</p>
                    </div>

                    <!-- Total Tasks -->
                    <div class="bg-white rounded-lg p-3 border border-[#E5E7EB]">
                        <p class="text-xs text-[#9CA3AF] mb-1">Total Tasks</p>
                        <p class="text-xl font-semibold text-[#111827]">{{ stats?.total_tasks || 0 }}</p>
                    </div>
                </div>

                <!-- Projects Section -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-[#111827]">Projects Overview</h2>
                        <Link 
                            href="/admin/projects/create" 
                            class="px-4 py-2 bg-[#5B21B6] text-white rounded-lg hover:bg-[#6D28D9] text-sm font-medium"
                        >
                            Create Project
                        </Link>
                    </div>

                    <!-- Project Cards Grid -->
                    <div v-if="projects && projects.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div v-for="project in projects" :key="project.id" 
                            class="bg-white rounded-lg border border-[#E5E7EB] p-4 hover:shadow-sm transition-shadow">
                            
                            <!-- Project Header -->
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-[#111827] text-sm mb-1">{{ project.name }}</h3>
                                    <p class="text-xs text-[#6B7280] line-clamp-2">{{ project.description || 'No description' }}</p>
                                </div>
                                <span 
                                    :class="{
                                        'bg-[#D1FAE5] text-[#065F46]': project.status === 'active',
                                        'bg-[#FEE2E2] text-[#991B1B]': project.status === 'inactive'
                                    }"
                                    class="px-2 py-1 rounded text-xs font-medium"
                                >
                                    {{ project.status === 'active' ? 'Active' : 'Inactive' }}
                                </span>
                            </div>

                            <!-- Customer -->
                            <div class="mb-3">
                                <p class="text-xs text-[#6B7280] mb-1">Customer</p>
                                <p class="text-sm font-medium text-[#111827]">{{ project.customer }}</p>
                            </div>

                            <!-- Assigned Team -->
                            <div class="mb-4">
                                <p class="text-xs text-[#6B7280] mb-2">Assigned Team</p>
                                <div class="space-y-1">
                                    <!-- Frontend Developer -->
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-[#3B82F6] flex items-center justify-center">
                                            <span class="text-white text-xs font-medium">FE</span>
                                        </div>
                                        <span class="text-xs text-[#6B7280]">
                                            {{ project.frontend_developer || 'Not assigned' }}
                                        </span>
                                    </div>
                                    
                                    <!-- Backend Developer -->
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-[#F97316] flex items-center justify-center">
                                            <span class="text-white text-xs font-medium">BE</span>
                                        </div>
                                        <span class="text-xs text-[#6B7280]">
                                            {{ project.backend_developer || 'Not assigned' }}
                                        </span>
                                    </div>
                                    
                                    <!-- Server Admin -->
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-[#8B5CF6] flex items-center justify-center">
                                            <span class="text-white text-xs font-medium">SA</span>
                                        </div>
                                        <span class="text-xs text-[#6B7280]">
                                            {{ project.server_admin || 'Not assigned' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Project Stats -->
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-xs text-[#6B7280]">Total Tasks</p>
                                    <p class="text-lg font-semibold text-[#111827]">{{ project.total_tasks }}</p>
                                </div>
                                <div v-if="project.total_tasks > 0" class="text-right">
                                    <p class="text-xs text-[#6B7280]">Progress</p>
                                    <p class="text-lg font-semibold text-[#111827]">{{ project.overall_progress }}%</p>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-2">
                                <button 
                                    @click="openEditModal(project)"
                                    class="flex-1 px-3 py-2 bg-[#3B82F6] text-white rounded text-xs font-medium hover:bg-[#2563EB]"
                                >
                                    Edit
                                </button>
                                <button 
                                    @click="archiveProject(project)"
                                    :class="{
                                        'bg-[#F97316] hover:bg-[#EA580C]': project.status === 'active',
                                        'bg-[#10B981] hover:bg-[#059669]': project.status === 'inactive'
                                    }"
                                    class="flex-1 px-3 py-2 text-white rounded text-xs font-medium"
                                >
                                    {{ project.status === 'active' ? 'Archive' : 'Activate' }}
                                </button>
                                <button 
                                    @click="openDeleteModal(project)"
                                    class="px-3 py-2 bg-[#EF4444] text-white rounded text-xs font-medium hover:bg-[#DC2626]"
                                >
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-else class="text-center py-12">
                        <h3 class="text-sm font-medium text-[#111827] mb-2">No projects yet</h3>
                        <p class="text-xs text-[#6B7280] mb-4">Get started by creating your first project</p>
                        <Link 
                            href="/admin/projects/create" 
                            class="px-4 py-2 bg-[#5B21B6] text-white rounded-lg hover:bg-[#6D28D9] text-sm font-medium"
                        >
                            Create Project
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div v-if="showDeleteModal" class="fixed inset-0 flex items-center justify-center z-50 p-4">
            <!-- Backdrop with blur effect -->
            <div class="absolute inset-0 backdrop-blur-sm bg-white/10"></div>
            
            <!-- Modal content (not blurred) -->
            <div class="relative bg-white rounded-lg p-6 w-full max-w-md shadow-2xl border border-[#E5E7EB]">
                <h3 class="text-lg font-semibold text-[#111827] mb-4">Delete Project</h3>
                
                <div class="mb-6">
                    <p class="text-sm text-[#6B7280] mb-3">
                        Are you sure you want to delete <strong>{{ selectedProject?.name }}</strong>?
                    </p>
                    <div class="bg-[#FEF2F2] border border-[#FECACA] rounded-lg p-3">
                        <p class="text-xs text-[#991B1B]">
                            <strong>Warning:</strong> This action cannot be undone. This will permanently delete:
                        </p>
                        <ul class="text-xs text-[#991B1B] mt-2 ml-4 list-disc">
                            <li>The project and all its data</li>
                            <li>All associated tasks ({{ selectedProject?.total_tasks || 0 }} tasks)</li>
                            <li>Customer and developer assignments</li>
                        </ul>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <button 
                        @click="closeDeleteModal"
                        class="px-4 py-2 border border-[#E5E7EB] text-[#111827] rounded-lg hover:bg-[#F9FAFB] text-sm font-medium"
                    >
                        Cancel
                    </button>
                    <button 
                        @click="confirmDelete"
                        class="px-4 py-2 bg-[#EF4444] text-white rounded-lg hover:bg-[#DC2626] text-sm font-medium"
                    >
                        Delete Project
                    </button>
                </div>
            </div>
        </div>

        <!-- Edit Project Modal -->
        <div v-if="showEditModal" class="fixed inset-0 flex items-center justify-center z-50 p-4">
            <!-- Backdrop with blur effect -->
            <div class="absolute inset-0 backdrop-blur-sm bg-white/10"></div>
            
            <!-- Modal content (not blurred) -->
            <div class="relative bg-white rounded-lg p-6 w-full max-w-2xl shadow-2xl border border-[#E5E7EB] max-h-[90vh] overflow-y-auto">
                <h3 class="text-lg font-semibold text-[#111827] mb-6">Edit Project</h3>
                
                <form @submit.prevent="updateProject" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Left Column -->
                        <div class="space-y-4">
                            <!-- Project Name -->
                            <div>
                                <label class="block text-sm font-medium text-[#111827] mb-2">Project Name</label>
                                <input 
                                    v-model="editForm.name"
                                    type="text" 
                                    required
                                    class="w-full px-3 py-2 border border-[#E5E7EB] rounded-lg focus:ring-2 focus:ring-[#5B21B6] focus:border-transparent text-[#111827] bg-white"
                                    placeholder="Enter project name"
                                />
                            </div>

                            <!-- Project Description -->
                            <div>
                                <label class="block text-sm font-medium text-[#111827] mb-2">Description</label>
                                <textarea 
                                    v-model="editForm.description"
                                    rows="3"
                                    class="w-full px-3 py-2 border border-[#E5E7EB] rounded-lg focus:ring-2 focus:ring-[#5B21B6] focus:border-transparent text-[#111827] bg-white"
                                    placeholder="Enter project description"
                                ></textarea>
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium text-[#111827] mb-2">Status</label>
                                <select 
                                    v-model="editForm.status"
                                    class="w-full px-3 py-2 border border-[#E5E7EB] rounded-lg focus:ring-2 focus:ring-[#5B21B6] focus:border-transparent text-[#111827] bg-white"
                                >
                                    <option value="active">Active</option>
                                    <option value="archived">Archived</option>
                                </select>
                            </div>

                            <!-- Customer -->
                            <div>
                                <label class="block text-sm font-medium text-[#111827] mb-2">Customer</label>
                                <select 
                                    v-model="editForm.customer_id"
                                    required
                                    class="w-full px-3 py-2 border border-[#E5E7EB] rounded-lg focus:ring-2 focus:ring-[#5B21B6] focus:border-transparent text-[#111827] bg-white"
                                >
                                    <option value="">Select Customer</option>
                                    <option v-for="customer in customers" :key="customer.id" :value="customer.id">
                                        {{ customer.name }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-4">
                            <!-- Frontend Developer -->
                            <div>
                                <label class="block text-sm font-medium text-[#111827] mb-2">Frontend Developer</label>
                                <select 
                                    v-model="editForm.frontend_developer_id"
                                    class="w-full px-3 py-2 border border-[#E5E7EB] rounded-lg focus:ring-2 focus:ring-[#5B21B6] focus:border-transparent text-[#111827] bg-white"
                                >
                                    <option value="">Select Frontend Developer</option>
                                    <option v-for="dev in frontendDevelopers" :key="dev.id" :value="dev.id">
                                        {{ dev.name }}
                                    </option>
                                </select>
                            </div>

                            <!-- Backend Developer -->
                            <div>
                                <label class="block text-sm font-medium text-[#111827] mb-2">Backend Developer</label>
                                <select 
                                    v-model="editForm.backend_developer_id"
                                    class="w-full px-3 py-2 border border-[#E5E7EB] rounded-lg focus:ring-2 focus:ring-[#5B21B6] focus:border-transparent text-[#111827] bg-white"
                                >
                                    <option value="">Select Backend Developer</option>
                                    <option v-for="dev in backendDevelopers" :key="dev.id" :value="dev.id">
                                        {{ dev.name }}
                                    </option>
                                </select>
                            </div>

                            <!-- Server Administrator -->
                            <div>
                                <label class="block text-sm font-medium text-[#111827] mb-2">Server Administrator</label>
                                <select 
                                    v-model="editForm.server_admin_id"
                                    class="w-full px-3 py-2 border border-[#E5E7EB] rounded-lg focus:ring-2 focus:ring-[#5B21B6] focus:border-transparent text-[#111827] bg-white"
                                >
                                    <option value="">Select Server Administrator</option>
                                    <option v-for="admin in serverAdministrators" :key="admin.id" :value="admin.id">
                                        {{ admin.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-[#E5E7EB]">
                        <button 
                            type="button"
                            @click="closeEditModal"
                            class="px-4 py-2 border border-[#E5E7EB] text-[#111827] rounded-lg hover:bg-[#F9FAFB] text-sm font-medium"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit"
                            class="px-4 py-2 bg-[#5B21B6] text-white rounded-lg hover:bg-[#6D28D9] text-sm font-medium"
                        >
                            Update Project
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>