<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

interface Project {
    id: number;
    name: string;
    description: string | null;
    customer: { id: number; name: string };
    frontend_developer: { id: number; name: string } | null;
    backend_developer: { id: number; name: string } | null;
    server_admin: { id: number; name: string } | null;
    tasks_count: number;
    status: 'active' | 'archived';
    created_at: string;
}

interface Props {
    projects: Project[];
    filters: {
        status: string;
        search: string;
    };
}

const props = defineProps<Props>();

// Debug: Log the projects data
console.log('Projects data:', props.projects);
console.log('Projects length:', props.projects.length);

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Projects', href: '/admin/projects' },
];

const searchQuery = ref(props.filters.search);
const statusFilter = ref(props.filters.status);

const stats = computed(() => ({
    total: props.projects.length,
    active: props.projects.filter(p => p.status === 'active').length,
    archived: props.projects.filter(p => p.status === 'archived').length,
    totalTasks: props.projects.reduce((sum, p) => sum + p.tasks_count, 0),
}));

function deleteProject(project: Project) {
    if (confirm('Are you sure you want to delete this project?')) {
        router.delete(`/admin/projects/${project.id}`);
    }
}

function archiveProject(project: Project) {
    router.post(`/admin/projects/${project.id}/archive`);
}

function activateProject(project: Project) {
    router.post(`/admin/projects/${project.id}/activate`);
}

function handleSearch() {
    router.get('/admin/projects', { search: searchQuery.value, status: statusFilter.value }, {
        preserveState: true,
        preserveScroll: true,
    });
}

function handleStatusFilter() {
    router.get('/admin/projects', { search: searchQuery.value, status: statusFilter.value }, {
        preserveState: true,
        preserveScroll: true,
    });
}
</script>

<template>
    <Head title="Projects" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-8 bg-[#F9FAFB]">
            <div class="w-full max-w-7xl mx-auto space-y-6">
                <!-- Header -->
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-[#111827]">Projects</h1>
                        <p class="text-sm text-[#6B7280] mt-1">Manage projects and assign developers</p>
                    </div>
                    <Link 
                        href="/admin/projects/create" 
                        class="bg-[#5B21B6] hover:bg-[#6D28D9] text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create Project
                    </Link>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-white rounded-lg p-5 border border-[#E5E7EB]">
                        <p class="text-xs text-[#6B7280] mb-1">Total Projects</p>
                        <p class="text-3xl font-bold text-[#111827]">{{ stats.total }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-5 border border-[#E5E7EB]">
                        <p class="text-xs text-[#6B7280] mb-1">Active Projects</p>
                        <p class="text-3xl font-bold text-[#3B82F6]">{{ stats.active }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-5 border border-[#E5E7EB]">
                        <p class="text-xs text-[#6B7280] mb-1">Archived</p>
                        <p class="text-3xl font-bold text-[#9CA3AF]">{{ stats.archived }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-5 border border-[#E5E7EB]">
                        <p class="text-xs text-[#6B7280] mb-1">Total Tasks</p>
                        <p class="text-3xl font-bold text-[#F97316]">{{ stats.totalTasks }}</p>
                    </div>
                </div>

                <!-- Projects Grid -->
                <div v-if="projects.length === 0" class="bg-white rounded-lg border border-[#E5E7EB] p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-[#9CA3AF] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-[#111827] mb-2">No projects found</h3>
                    <p class="text-sm text-[#6B7280] mb-4">Get started by creating your first project</p>
                    <Link 
                        href="/admin/projects/create" 
                        class="inline-flex items-center gap-2 bg-[#5B21B6] hover:bg-[#6D28D9] text-white px-4 py-2 rounded-lg text-sm font-medium"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create Project
                    </Link>
                </div>

                <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div 
                        v-for="project in projects" 
                        :key="project.id" 
                        class="bg-white rounded-lg border border-[#E5E7EB] p-6 hover:shadow-lg transition-shadow"
                    >
                        <!-- Project Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-[#111827] mb-1">{{ project.name }}</h3>
                                <p class="text-sm text-[#6B7280]" v-if="project.description">
                                    {{ project.description.substring(0, 80) }}{{ project.description.length > 80 ? '...' : '' }}
                                </p>
                            </div>
                            <span 
                                :class="{
                                    'bg-[#D1FAE5] text-[#065F46]': project.status === 'active',
                                    'bg-[#F3F4F6] text-[#374151]': project.status === 'archived'
                                }"
                                class="px-2 py-1 rounded-md text-xs font-medium capitalize ml-2"
                            >
                                {{ project.status }}
                            </span>
                        </div>

                        <!-- Customer -->
                        <div class="mb-4 pb-4 border-b border-[#E5E7EB]">
                            <p class="text-xs text-[#6B7280] mb-1">Customer</p>
                            <p class="text-sm font-medium text-[#111827]">{{ project.customer.name }}</p>
                        </div>

                        <!-- Team Section -->
                        <div class="mb-4">
                            <p class="text-xs text-[#6B7280] mb-3 font-medium">Assigned Team</p>
                            <div class="space-y-2">
                                <!-- Frontend Developer -->
                                <div class="flex items-center gap-2 p-2 bg-[#EFF6FF] rounded-lg">
                                    <div class="w-8 h-8 bg-[#3B82F6] rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-white text-xs font-bold">FE</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs text-[#6B7280]">Frontend</p>
                                        <p class="text-sm font-medium text-[#111827] truncate">
                                            {{ project.frontend_developer?.name || 'Not assigned' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Backend Developer -->
                                <div class="flex items-center gap-2 p-2 bg-[#FFF7ED] rounded-lg">
                                    <div class="w-8 h-8 bg-[#F97316] rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-white text-xs font-bold">BE</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs text-[#6B7280]">Backend</p>
                                        <p class="text-sm font-medium text-[#111827] truncate">
                                            {{ project.backend_developer?.name || 'Not assigned' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Server Admin -->
                                <div class="flex items-center gap-2 p-2 bg-[#F5F3FF] rounded-lg">
                                    <div class="w-8 h-8 bg-[#8B5CF6] rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-white text-xs font-bold">SA</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs text-[#6B7280]">Server Admin</p>
                                        <p class="text-sm font-medium text-[#111827] truncate">
                                            {{ project.server_admin?.name || 'Not assigned' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tasks Count -->
                        <div class="mb-4 pb-4 border-b border-[#E5E7EB]">
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-[#6B7280]">Total Tasks</span>
                                <span class="text-lg font-bold text-[#111827]">{{ project.tasks_count }}</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-2">
                            <button 
                                class="flex-1 px-3 py-2 bg-[#3B82F6] text-white rounded-lg hover:bg-[#2563EB] transition-colors text-sm font-medium"
                            >
                                Edit
                            </button>
                            <button 
                                v-if="project.status === 'active'"
                                @click="archiveProject(project)" 
                                class="flex-1 px-3 py-2 bg-[#F59E0B] text-white rounded-lg hover:bg-[#D97706] transition-colors text-sm font-medium"
                            >
                                Archive
                            </button>
                            <button 
                                v-else
                                @click="activateProject(project)" 
                                class="flex-1 px-3 py-2 bg-[#10B981] text-white rounded-lg hover:bg-[#059669] transition-colors text-sm font-medium"
                            >
                                Activate
                            </button>
                            <button 
                                @click="deleteProject(project)" 
                                class="px-3 py-2 bg-[#EF4444] text-white rounded-lg hover:bg-[#DC2626] transition-colors text-sm font-medium"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
