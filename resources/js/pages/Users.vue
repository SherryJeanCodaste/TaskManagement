<script setup lang="ts">
import { Head, Link, usePage, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

interface Employee {
    id: number;
    name: string;
    email: string;
    role: string;
    developer_type: string;
    projects: string[];
    projects_count: number;
    status: string;
}

interface Customer {
    id: number;
    name: string;
    email: string;
    projects: string[];
    projects_count: number;
    tasks_created: number;
    status: string;
}

interface Stats {
    total_employees: number;
    active_employees: number;
    total_customers: number;
    active_customers: number;
    total_tasks_created: number;
}

interface Props {
    employees: Employee[];
    customers: Customer[];
    stats: Stats;
}

defineProps<Props>();

const page = usePage();
const activeTab = ref('employees');
const showEditModal = ref(false);
const showDeactivateModal = ref(false);
const selectedUser = ref<Employee | Customer | null>(null);

// Edit form
const editForm = useForm({
    name: '',
    email: '',
    developer_type: '',
    password: '',
});

// Functions
function openEditModal(user: Employee | Customer) {
    selectedUser.value = user;
    editForm.name = user.name;
    editForm.email = user.email;
    editForm.developer_type = 'developer_type' in user ? user.developer_type : '';
    editForm.password = '';
    showEditModal.value = true;
}

function closeEditModal() {
    showEditModal.value = false;
    selectedUser.value = null;
    editForm.reset();
}

function submitEdit() {
    if (!selectedUser.value) return;
    
    editForm.put(`/users/${selectedUser.value.id}`, {
        onSuccess: () => {
            closeEditModal();
        },
    });
}

function openDeactivateModal(user: Employee | Customer) {
    selectedUser.value = user;
    showDeactivateModal.value = true;
}

function closeDeactivateModal() {
    showDeactivateModal.value = false;
    selectedUser.value = null;
}

function confirmDeactivate() {
    if (!selectedUser.value) return;
    
    router.post(`/users/${selectedUser.value.id}/deactivate`, {}, {
        onSuccess: () => {
            closeDeactivateModal();
        },
    });
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Users', href: '/users' },
];
</script>

<template>
    <Head title="Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col p-6 bg-white">
            <div class="w-full max-w-7xl mx-auto space-y-6">
                <!-- Header -->
                <div class="mb-6">
                    <h1 class="text-xl font-bold text-[#111827] mb-2">Users Management</h1>
                    <p class="text-sm text-[#6B7280]">Manage employees and customers</p>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-4 gap-4 mb-6">
                    <div class="bg-white rounded-lg p-4 border border-[#E5E7EB]">
                        <p class="text-xs text-[#9CA3AF] mb-1">Total Employees</p>
                        <p class="text-xl font-semibold text-[#111827]">{{ stats.total_employees }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-4 border border-[#E5E7EB]">
                        <p class="text-xs text-[#9CA3AF] mb-1">Active Employees</p>
                        <p class="text-xl font-semibold text-[#111827]">{{ stats.active_employees }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-4 border border-[#E5E7EB]">
                        <p class="text-xs text-[#9CA3AF] mb-1">Total Customers</p>
                        <p class="text-xl font-semibold text-[#111827]">{{ stats.total_customers }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-4 border border-[#E5E7EB]">
                        <p class="text-xs text-[#9CA3AF] mb-1">Tasks Created</p>
                        <p class="text-xl font-semibold text-[#111827]">{{ stats.total_tasks_created }}</p>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="border-b border-[#E5E7EB]">
                    <nav class="flex space-x-8">
                        <button 
                            @click="activeTab = 'employees'"
                            :class="{
                                'border-[#5B21B6] text-[#5B21B6]': activeTab === 'employees',
                                'border-transparent text-[#6B7280] hover:text-[#111827] hover:border-[#D1D5DB]': activeTab !== 'employees'
                            }"
                            class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm"
                        >
                            Employees ({{ stats.total_employees }})
                        </button>
                        <button 
                            @click="activeTab = 'customers'"
                            :class="{
                                'border-[#5B21B6] text-[#5B21B6]': activeTab === 'customers',
                                'border-transparent text-[#6B7280] hover:text-[#111827] hover:border-[#D1D5DB]': activeTab !== 'customers'
                            }"
                            class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm"
                        >
                            Customers ({{ stats.total_customers }})
                        </button>
                    </nav>
                </div>

                <!-- Employees Tab -->
                <div v-show="activeTab === 'employees'" class="bg-white rounded-lg border border-[#E5E7EB] overflow-hidden">
                    <div class="p-4 border-b border-[#E5E7EB] flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-[#111827]">Employees</h2>
                        <div class="text-xs text-[#6B7280]">Newest first</div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-[#F9FAFB] border-b border-[#E5E7EB]">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-[#6B7280] uppercase tracking-wider">Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-[#6B7280] uppercase tracking-wider">Role</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-[#6B7280] uppercase tracking-wider">Email</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-[#6B7280] uppercase tracking-wider">Assigned Projects</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-[#6B7280] uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-[#6B7280] uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#E5E7EB]">
                                <tr v-for="employee in employees" :key="employee.id" class="hover:bg-[#F9FAFB] transition-colors">
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-[#5B21B6] rounded-full flex items-center justify-center text-white font-medium text-xs">
                                                {{ employee.name.split(' ').map(n => n[0]).join('') }}
                                            </div>
                                            <div class="font-medium text-sm text-[#111827]">{{ employee.name }}</div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span 
                                            :class="{
                                                'bg-[#DBEAFE] text-[#1E40AF]': employee.developer_type === 'frontend',
                                                'bg-[#FFEDD5] text-[#9A3412]': employee.developer_type === 'backend',
                                                'bg-[#EDE9FE] text-[#6B21A8]': employee.developer_type === 'server_administrator'
                                            }"
                                            class="px-2 py-1 rounded text-xs font-medium"
                                        >
                                            {{ employee.role }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-[#6B7280]">{{ employee.email }}</td>
                                    <td class="px-4 py-4">
                                        <div class="flex flex-wrap gap-1">
                                            <span v-for="project in employee.projects" :key="project" class="px-2 py-1 bg-[#F3F4F6] text-[#374151] rounded text-xs">
                                                {{ project }}
                                            </span>
                                            <span v-if="employee.projects.length === 0" class="text-xs text-[#9CA3AF]">No projects</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 bg-[#D1FAE5] text-[#065F46] rounded text-xs font-medium">
                                            Active
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="flex gap-2">
                                            <button 
                                                @click="openEditModal(employee)"
                                                class="text-[#3B82F6] hover:text-[#2563EB] font-medium text-xs"
                                            >
                                                Edit
                                            </button>
                                            <button 
                                                @click="openDeactivateModal(employee)"
                                                class="text-[#EF4444] hover:text-[#DC2626] font-medium text-xs"
                                            >
                                                Deactivate
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Customers Tab -->
                <div v-show="activeTab === 'customers'" class="bg-white rounded-lg border border-[#E5E7EB] overflow-hidden">
                    <div class="p-4 border-b border-[#E5E7EB] flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-[#111827]">Customers</h2>
                        <div class="text-xs text-[#6B7280]">Newest first</div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-[#F9FAFB] border-b border-[#E5E7EB]">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-[#6B7280] uppercase tracking-wider">Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-[#6B7280] uppercase tracking-wider">Email</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-[#6B7280] uppercase tracking-wider">Assigned Projects</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-[#6B7280] uppercase tracking-wider">Tasks Created</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-[#6B7280] uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-[#6B7280] uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#E5E7EB]">
                                <tr v-for="customer in customers" :key="customer.id" class="hover:bg-[#F9FAFB] transition-colors">
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-[#10B981] rounded-full flex items-center justify-center text-white font-medium text-xs">
                                                {{ customer.name.split(' ').map(n => n[0]).join('') }}
                                            </div>
                                            <div class="font-medium text-sm text-[#111827]">{{ customer.name }}</div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-[#6B7280]">{{ customer.email }}</td>
                                    <td class="px-4 py-4">
                                        <div class="flex flex-wrap gap-1">
                                            <span v-for="project in customer.projects" :key="project" class="px-2 py-1 bg-[#F3F4F6] text-[#374151] rounded text-xs">
                                                {{ project }}
                                            </span>
                                            <span v-if="customer.projects.length === 0" class="text-xs text-[#9CA3AF]">No projects</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 bg-[#FEF3C7] text-[#92400E] rounded text-xs font-medium">
                                            {{ customer.tasks_created }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 bg-[#D1FAE5] text-[#065F46] rounded text-xs font-medium">
                                            Active
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="flex gap-2">
                                            <button 
                                                @click="openEditModal(customer)"
                                                class="text-[#3B82F6] hover:text-[#2563EB] font-medium text-xs"
                                            >
                                                Edit
                                            </button>
                                            <button 
                                                @click="openDeactivateModal(customer)"
                                                class="text-[#EF4444] hover:text-[#DC2626] font-medium text-xs"
                                            >
                                                Deactivate
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit User Modal -->
        <div v-if="showEditModal" class="fixed inset-0 flex items-center justify-center z-50 p-4">
            <!-- Backdrop with blur effect -->
            <div class="absolute inset-0 backdrop-blur-sm bg-white/10"></div>
            
            <!-- Modal content (not blurred) -->
            <div class="relative bg-white rounded-lg p-6 w-full max-w-md shadow-2xl border border-[#E5E7EB]">
                <h3 class="text-lg font-semibold text-[#111827] mb-4">Edit User</h3>
                
                <form @submit.prevent="submitEdit" class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-[#111827] mb-1">Name</label>
                        <input 
                            v-model="editForm.name"
                            type="text"
                            required
                            class="w-full px-3 py-2 border border-[#E5E7EB] rounded-lg text-sm text-[#111827] bg-white focus:outline-none focus:ring-2 focus:ring-[#5B21B6]"
                        />
                        <div v-if="editForm.errors.name" class="text-red-500 text-xs mt-1">{{ editForm.errors.name }}</div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-[#111827] mb-1">Email</label>
                        <input 
                            v-model="editForm.email"
                            type="email"
                            required
                            class="w-full px-3 py-2 border border-[#E5E7EB] rounded-lg text-sm text-[#111827] bg-white focus:outline-none focus:ring-2 focus:ring-[#5B21B6]"
                        />
                        <div v-if="editForm.errors.email" class="text-red-500 text-xs mt-1">{{ editForm.errors.email }}</div>
                    </div>

                    <!-- Developer Type (only for employees) -->
                    <div v-if="selectedUser && 'developer_type' in selectedUser">
                        <label class="block text-sm font-medium text-[#111827] mb-1">Developer Type</label>
                        <select 
                            v-model="editForm.developer_type"
                            required
                            class="w-full px-3 py-2 border border-[#E5E7EB] rounded-lg text-sm text-[#111827] bg-white focus:outline-none focus:ring-2 focus:ring-[#5B21B6]"
                        >
                            <option value="frontend">Frontend Developer</option>
                            <option value="backend">Backend Developer</option>
                            <option value="server_administrator">Server Administrator</option>
                        </select>
                        <div v-if="editForm.errors.developer_type" class="text-red-500 text-xs mt-1">{{ editForm.errors.developer_type }}</div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-[#111827] mb-1">New Password (optional)</label>
                        <input 
                            v-model="editForm.password"
                            type="password"
                            class="w-full px-3 py-2 border border-[#E5E7EB] rounded-lg text-sm text-[#111827] bg-white focus:outline-none focus:ring-2 focus:ring-[#5B21B6]"
                            placeholder="Leave blank to keep current password"
                        />
                        <div v-if="editForm.errors.password" class="text-red-500 text-xs mt-1">{{ editForm.errors.password }}</div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-3 pt-4">
                        <button 
                            type="button"
                            @click="closeEditModal"
                            class="px-4 py-2 border border-[#E5E7EB] text-[#111827] rounded-lg hover:bg-[#F9FAFB] text-sm font-medium"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit"
                            :disabled="editForm.processing"
                            class="px-4 py-2 bg-[#5B21B6] text-white rounded-lg hover:bg-[#6D28D9] text-sm font-medium disabled:opacity-50"
                        >
                            {{ editForm.processing ? 'Saving...' : 'Save Changes' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Deactivate Confirmation Modal -->
        <div v-if="showDeactivateModal" class="fixed inset-0 flex items-center justify-center z-50 p-4">
            <!-- Backdrop with blur effect -->
            <div class="absolute inset-0 backdrop-blur-sm bg-white/10"></div>
            
            <!-- Modal content (not blurred) -->
            <div class="relative bg-white rounded-lg p-6 w-full max-w-md shadow-2xl border border-[#E5E7EB]">
                <h3 class="text-lg font-semibold text-[#111827] mb-4">Confirm Deactivation</h3>
                
                <p class="text-sm text-[#6B7280] mb-6">
                    Are you sure you want to deactivate <strong>{{ selectedUser?.name }}</strong>? 
                    This action will remove the user from the system.
                </p>

                <div class="flex justify-end gap-3">
                    <button 
                        @click="closeDeactivateModal"
                        class="px-4 py-2 border border-[#E5E7EB] text-[#111827] rounded-lg hover:bg-[#F9FAFB] text-sm font-medium"
                    >
                        Cancel
                    </button>
                    <button 
                        @click="confirmDeactivate"
                        class="px-4 py-2 bg-[#EF4444] text-white rounded-lg hover:bg-[#DC2626] text-sm font-medium"
                    >
                        Deactivate User
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>