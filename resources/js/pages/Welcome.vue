<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { dashboard, login, register } from '@/routes';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);
</script>

<template>
    <Head title="Welcome to TaskHive" />
    
    <div class="min-h-screen bg-white">
        <!-- Header / Navbar -->
        <header class="border-b border-[#E5E7EB] bg-white">
            <nav class="container mx-auto px-6 py-4 flex items-center justify-between max-w-6xl">
                <div class="flex items-center">
                    <img src="/logo.png" alt="TaskHive Logo" class="h-12 object-contain" />
                </div>
                <div class="flex items-center gap-3">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="dashboard()"
                        class="px-4 py-2 bg-[#5B21B6] text-white rounded-lg font-medium hover:bg-[#6D28D9] transition-colors text-sm"
                    >
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link
                            :href="login()"
                            class="px-4 py-2 text-[#111827] hover:text-[#6B7280] transition-colors font-medium text-sm"
                        >
                            Login
                        </Link>
                        <Link
                            v-if="canRegister"
                            :href="register()"
                            class="px-4 py-2 bg-[#5B21B6] text-white rounded-lg font-medium hover:bg-[#6D28D9] transition-colors text-sm"
                        >
                            Sign Up
                        </Link>
                    </template>
                </div>
            </nav>
        </header>

        <!-- Hero Section -->
        <section class="container mx-auto px-6 py-20 max-w-6xl">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <!-- Left: Content -->
                <div class="space-y-6">
                    <h2 class="text-5xl font-bold text-[#111827] leading-tight">
                        Simple Task Management
                    </h2>
                    <p class="text-xl text-[#6B7280]">
                        Track tasks, manage progress, and collaborate seamlessly. Built for teams that value simplicity.
                    </p>
                    <div class="flex gap-3 pt-4">
                        <Link
                            v-if="!$page.props.auth.user"
                            :href="register()"
                            class="px-6 py-3 bg-[#5B21B6] text-white rounded-lg font-semibold hover:bg-[#6D28D9] transition-colors"
                        >
                            Get Started
                        </Link>
                        <Link
                            v-else
                            :href="dashboard()"
                            class="px-6 py-3 bg-[#5B21B6] text-white rounded-lg font-semibold hover:bg-[#6D28D9] transition-colors"
                        >
                            Go to Dashboard
                        </Link>
                        <Link
                            v-if="!$page.props.auth.user"
                            :href="login()"
                            class="px-6 py-3 border border-[#E5E7EB] text-[#111827] rounded-lg font-semibold hover:bg-[#F9FAFB] transition-colors"
                        >
                            Login
                        </Link>
                    </div>
                </div>

                <!-- Right: Task Flow Illustration -->
                <div>
                    <div class="bg-white rounded-lg border border-[#E5E7EB] p-8">
                        <div class="space-y-6">
                            <!-- Step 1 -->
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-[#DBEAFE] rounded-lg flex items-center justify-center flex-shrink-0">
                                    <span class="text-[#3B82F6] font-bold text-lg">1</span>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-[#111827]">Create Task</p>
                                    <p class="text-sm text-[#6B7280]">Customer defines requirements and submits</p>
                                </div>
                            </div>
                            
                            <div class="flex justify-center">
                                <svg class="w-6 h-6 text-[#E5E7EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                </svg>
                            </div>

                            <!-- Step 2 -->
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-[#FEF3C7] rounded-lg flex items-center justify-center flex-shrink-0">
                                    <span class="text-[#F59E0B] font-bold text-lg">2</span>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-[#111827]">Assign to Developer</p>
                                    <p class="text-sm text-[#6B7280]">Task automatically assigned to available developer</p>
                                </div>
                            </div>

                            <div class="flex justify-center">
                                <svg class="w-6 h-6 text-[#E5E7EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                </svg>
                            </div>

                            <!-- Step 3 -->
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-[#FFEDD5] rounded-lg flex items-center justify-center flex-shrink-0">
                                    <span class="text-[#F97316] font-bold text-lg">3</span>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-[#111827]">Work in Progress</p>
                                    <p class="text-sm text-[#6B7280]">Developer completes the task</p>
                                </div>
                            </div>

                            <div class="flex justify-center">
                                <svg class="w-6 h-6 text-[#E5E7EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                </svg>
                            </div>

                            <!-- Step 4 -->
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-[#D1FAE5] rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-[#10B981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-[#111827]">Task Completed</p>
                                    <p class="text-sm text-[#6B7280]">Customer reviews and approves</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="bg-[#F9FAFB] py-20 border-t border-[#E5E7EB]">
            <div class="container mx-auto px-6 max-w-6xl">
                <h3 class="text-3xl font-bold text-center text-[#111827] mb-12">
                    Everything you need
                </h3>
                <div class="grid md:grid-cols-3 gap-6">
                    <!-- Feature 1 -->
                    <div class="bg-white rounded-lg p-6 border border-[#E5E7EB]">
                        <div class="w-12 h-12 bg-[#5B21B6] rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-[#111827] mb-2">Auto Assignment</h4>
                        <p class="text-sm text-[#6B7280]">Tasks are automatically assigned to the right developer</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="bg-white rounded-lg p-6 border border-[#E5E7EB]">
                        <div class="w-12 h-12 bg-[#5B21B6] rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-[#111827] mb-2">Progress Tracking</h4>
                        <p class="text-sm text-[#6B7280]">Real-time status updates with visual indicators</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="bg-white rounded-lg p-6 border border-[#E5E7EB]">
                        <div class="w-12 h-12 bg-[#5B21B6] rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-[#111827] mb-2">Notifications</h4>
                        <p class="text-sm text-[#6B7280]">Stay informed with instant task updates</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Status Colors Section -->
        <section class="py-20">
            <div class="container mx-auto px-6 max-w-6xl">
                <h3 class="text-2xl font-bold text-center text-[#111827] mb-8">
                    Task Status Colors
                </h3>
                <div class="flex flex-wrap justify-center gap-6">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-[#3B82F6] rounded-full"></span>
                        <span class="text-sm text-[#6B7280]">Pending</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-[#F59E0B] rounded-full"></span>
                        <span class="text-sm text-[#6B7280]">Assigned</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-[#F97316] rounded-full"></span>
                        <span class="text-sm text-[#6B7280]">In Progress</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-[#8B5CF6] rounded-full"></span>
                        <span class="text-sm text-[#6B7280]">Review</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-[#10B981] rounded-full"></span>
                        <span class="text-sm text-[#6B7280]">Completed</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-[#F9FAFB] border-t border-[#E5E7EB] py-12">
            <div class="container mx-auto px-6 max-w-6xl">
                <div class="grid md:grid-cols-3 gap-8">
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-6 h-6 bg-[#5B21B6] rounded flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <h5 class="text-[#111827] text-lg font-bold">TaskHive</h5>
                        </div>
                        <p class="text-sm text-[#6B7280]">Simple task management for modern teams</p>
                    </div>
                    <div>
                        <h6 class="text-[#111827] font-semibold mb-3 text-sm">Quick Links</h6>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#" class="text-[#6B7280] hover:text-[#111827] transition-colors">About</a></li>
                            <li><a href="#" class="text-[#6B7280] hover:text-[#111827] transition-colors">Contact</a></li>
                            <li><a href="#" class="text-[#6B7280] hover:text-[#111827] transition-colors">Help</a></li>
                        </ul>
                    </div>
                    <div>
                        <h6 class="text-[#111827] font-semibold mb-3 text-sm">Resources</h6>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#" class="text-[#6B7280] hover:text-[#111827] transition-colors">Documentation</a></li>
                            <li><a href="#" class="text-[#6B7280] hover:text-[#111827] transition-colors">Support</a></li>
                            <li><a href="#" class="text-[#6B7280] hover:text-[#111827] transition-colors">Privacy</a></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-[#E5E7EB] mt-8 pt-8 text-center">
                    <p class="text-sm text-[#6B7280]">&copy; 2026 TaskHive. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</template>
