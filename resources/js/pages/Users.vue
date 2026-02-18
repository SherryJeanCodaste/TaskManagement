<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Users', href: '/users' },
];

// Mock data
const employees = [
    { id: 1, name: 'John Doe', role: 'Frontend Developer', email: 'john@example.com', projects: ['E-Commerce Platform', 'CRM System'], status: 'active' },
    { id: 2, name: 'Jane Smith', role: 'Backend Developer', email: 'jane@example.com', projects: ['E-Commerce Platform', 'CRM System'], status: 'active' },
    { id: 3, name: 'Bob Wilson', role: 'Server Admin', email: 'bob@example.com', projects: ['E-Commerce Platform', 'Mobile App', 'CRM System'], status: 'active' },
    { id: 4, name: 'Alice Brown', role: 'Frontend Developer', email: 'alice@example.com', projects: ['Mobile App'], status: 'active' },
    { id: 5, name: 'Charlie Davis', role: 'Backend Developer', email: 'charlie@example.com', projects: ['Mobile App'], status: 'inactive' },
];

const customers = [
    { id: 1, name: 'ABC Corp', email: 'contact@abccorp.com', projects: ['E-Commerce Platform'], tasksCreated: 12, status: 'active' },
    { id: 2, name: 'XYZ Ltd', email: 'info@xyzltd.com', projects: ['Mobile App'], tasksCreated: 8, status: 'active' },
    { id: 3, name: 'Tech Solutions', email: 'hello@techsolutions.com', projects: ['CRM System'], tasksCreated: 15, status: 'active' },
];
</script>

<template>
    <Head title="Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6 bg-[#F9FAFB]">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-[#1E293B]">Users Management</h1>
                    <p class="text-[#1E293B] mt-1">Manage employees and customers</p>
                </div>
                <div class="flex gap-3">
                    <button class="flex items-center gap-2 px-6 py-3 bg-[#06B6D4] text-white rounded-lg hover:bg-[#0891B2] transition-colors font-semibold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Employee
                    </button>
                    <button class="flex items-center gap-2 px-6 py-3 bg-[#22C55E] text-white rounded-lg hover:bg-[#16A34A] transition-colors font-semibold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Customer
                    </button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-xl p-4 border border-[#CBD5E1] shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-[#1E293B] font-medium">Total Employees</p>
                            <p class="text-2xl font-bold text-[#06B6D4] mt-1">{{ employees.length }}</p>
                            <p class="text-xs text-[#1E293B]/60 mt-1">{{ employees.filter(e => e.status === 'active').length }} active</p>
                        </div>
                        <div class="w-10 h-10 bg-[#06B6D4]/10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#06B6D4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-4 border border-[#CBD5E1] shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-[#1E293B] font-medium">Total Customers</p>
                            <p class="text-2xl font-bold text-[#22C55E] mt-1">{{ customers.length }}</p>
                            <p class="text-xs text-[#1E293B]/60 mt-1">{{ customers.filter(c => c.status === 'active').length }} active</p>
                        </div>
                        <div class="w-10 h-10 bg-[#22C55E]/10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#22C55E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-4 border border-[#CBD5E1] shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-[#1E293B] font-medium">Tasks Created</p>
                            <p class="text-2xl font-bold text-[#F97316] mt-1">{{ customers.reduce((sum, c) => sum + c.tasksCreated, 0) }}</p>
                            <p class="text-xs text-[#1E293B]/60 mt-1">By customers</p>
                        </div>
                        <div class="w-10 h-10 bg-[#F97316]/10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#F97316]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employees Table -->
            <div class="bg-white rounded-xl border border-[#CBD5E1] shadow-sm overflow-hidden">
                <div class="p-6 border-b border-[#CBD5E1] flex items-center justify-between">
                    <h2 class="text-xl font-bold text-[#1E293B]">Employees</h2>
                    <input 
                        type="search" 
                        placeholder="Search employees..." 
                        class="px-4 py-2 border border-[#CBD5E1] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#06B6D4] focus:border-transparent"
                    />
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-[#F9FAFB] border-b border-[#CBD5E1]">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Assigned Projects</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#CBD5E1]">
                            <tr v-for="employee in employees" :key="employee.id" class="hover:bg-[#F9FAFB] transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-[#5B21B6] rounded-full flex items-center justify-center text-white font-semibold">
                                            {{ employee.name.split(' ').map(n => n[0]).join('') }}
                                        </div>
                                        <div class="font-semibold text-[#1E293B]">{{ employee.name }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 bg-[#06B6D4]/10 text-[#06B6D4] rounded-full text-sm font-semibold">
                                        {{ employee.role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-[#1E293B]">{{ employee.email }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        <span v-for="project in employee.projects" :key="project" class="px-2 py-1 bg-[#F9FAFB] text-[#1E293B] rounded text-xs">
                                            {{ project }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span 
                                        :class="{
                                            'bg-[#22C55E]/10 text-[#22C55E]': employee.status === 'active',
                                            'bg-[#EF4444]/10 text-[#EF4444]': employee.status === 'inactive'
                                        }"
                                        class="px-3 py-1 rounded-full text-sm font-semibold capitalize"
                                    >
                                        {{ employee.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex gap-2">
                                        <button class="text-[#06B6D4] hover:text-[#0891B2] font-semibold text-sm">Edit</button>
                                        <button class="text-[#EF4444] hover:text-[#DC2626] font-semibold text-sm">Deactivate</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Customers Table -->
            <div class="bg-white rounded-xl border border-[#CBD5E1] shadow-sm overflow-hidden">
                <div class="p-6 border-b border-[#CBD5E1] flex items-center justify-between">
                    <h2 class="text-xl font-bold text-[#1E293B]">Customers</h2>
                    <input 
                        type="search" 
                        placeholder="Search customers..." 
                        class="px-4 py-2 border border-[#CBD5E1] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#06B6D4] focus:border-transparent"
                    />
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-[#F9FAFB] border-b border-[#CBD5E1]">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Assigned Projects</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Tasks Created</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-[#1E293B] uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#CBD5E1]">
                            <tr v-for="customer in customers" :key="customer.id" class="hover:bg-[#F9FAFB] transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-[#22C55E] rounded-full flex items-center justify-center text-white font-semibold">
                                            {{ customer.name.split(' ').map(n => n[0]).join('') }}
                                        </div>
                                        <div class="font-semibold text-[#1E293B]">{{ customer.name }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-[#1E293B]">{{ customer.email }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        <span v-for="project in customer.projects" :key="project" class="px-2 py-1 bg-[#F9FAFB] text-[#1E293B] rounded text-xs">
                                            {{ project }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 bg-[#F97316]/10 text-[#F97316] rounded-full text-sm font-semibold">
                                        {{ customer.tasksCreated }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 bg-[#22C55E]/10 text-[#22C55E] rounded-full text-sm font-semibold capitalize">
                                        {{ customer.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex gap-2">
                                        <button class="text-[#06B6D4] hover:text-[#0891B2] font-semibold text-sm">Edit</button>
                                        <button class="text-[#EF4444] hover:text-[#DC2626] font-semibold text-sm">Deactivate</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
