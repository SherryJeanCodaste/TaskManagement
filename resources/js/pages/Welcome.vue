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
    
    <div class="min-h-screen bg-[#F9FAFB]">
        <!-- Header / Navbar -->
        <header class="bg-[#5B21B6] shadow-lg">
            <nav class="container mx-auto px-6 py-4 flex items-center justify-between">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-white">TaskHive</h1>
                </div>
                <div class="flex items-center gap-4">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="dashboard()"
                        class="px-6 py-2 bg-[#06B6D4] text-white rounded-lg font-medium hover:bg-[#0891B2] transition-colors"
                    >
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link
                            :href="login()"
                            class="px-6 py-2 text-white hover:text-[#06B6D4] transition-colors font-medium"
                        >
                            Login
                        </Link>
                        <Link
                            v-if="canRegister"
                            :href="register()"
                            class="px-6 py-2 bg-[#06B6D4] text-white rounded-lg font-medium hover:bg-[#0891B2] transition-colors"
                        >
                            Sign Up
                        </Link>
                    </template>
                </div>
            </nav>
        </header>

        <!-- Hero Section -->
        <section class="container mx-auto px-6 py-20">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left: Main Content -->
                <div class="space-y-6">
                    <h2 class="text-5xl font-bold text-[#1E293B] leading-tight">
                        Welcome to TaskHive
                    </h2>
                    <p class="text-xl text-[#1E293B] leading-relaxed">
                        Simplify task management for teachers and developers. Track tasks, assign automatically, and stay organized.
                    </p>
                    <div class="flex gap-4">
                        <Link
                            v-if="!$page.props.auth.user"
                            :href="register()"
                            class="px-8 py-4 bg-[#06B6D4] text-white rounded-lg font-semibold text-lg hover:bg-[#0891B2] transition-colors shadow-lg"
                        >
                            Get Started
                        </Link>
                        <Link
                            v-else
                            :href="dashboard()"
                            class="px-8 py-4 bg-[#06B6D4] text-white rounded-lg font-semibold text-lg hover:bg-[#0891B2] transition-colors shadow-lg"
                        >
                            Go to Dashboard
                        </Link>
                        <Link
                            v-if="!$page.props.auth.user"
                            :href="login()"
                            class="px-8 py-4 border-2 border-[#5B21B6] text-[#5B21B6] rounded-lg font-semibold text-lg hover:bg-[#5B21B6] hover:text-white transition-colors"
                        >
                            Login
                        </Link>
                    </div>
                </div>

                <!-- Right: Illustration -->
                <div class="hidden lg:block">
                    <div class="bg-white rounded-2xl shadow-xl p-8 border border-[#CBD5E1]">
                        <div class="space-y-4">
                            <!-- Task Flow Illustration -->
                            <div class="flex items-center gap-4 p-4 bg-[#F9FAFB] rounded-lg border border-[#CBD5E1]">
                                <div class="w-12 h-12 bg-[#5B21B6] rounded-full flex items-center justify-center text-white font-bold">
                                    T
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-[#1E293B]">Teacher creates task</p>
                                    <p class="text-sm text-[#CBD5E1]">Define requirements & assign</p>
                                </div>
                            </div>
                            
                            <div class="flex justify-center">
                                <svg class="w-8 h-8 text-[#06B6D4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                </svg>
                            </div>

                            <div class="flex items-center gap-4 p-4 bg-[#F9FAFB] rounded-lg border border-[#CBD5E1]">
                                <div class="w-12 h-12 bg-[#F97316] rounded-full flex items-center justify-center text-white font-bold">
                                    D
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-[#1E293B]">Developer receives task</p>
                                    <p class="text-sm text-[#CBD5E1]">Automatic assignment</p>
                                </div>
                            </div>

                            <div class="flex justify-center">
                                <svg class="w-8 h-8 text-[#06B6D4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                </svg>
                            </div>

                            <div class="flex items-center gap-4 p-4 bg-[#F9FAFB] rounded-lg border border-[#CBD5E1]">
                                <div class="w-12 h-12 bg-[#22C55E] rounded-full flex items-center justify-center text-white">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-[#1E293B]">Task completed</p>
                                    <p class="text-sm text-[#CBD5E1]">Track progress instantly</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="bg-white py-20">
            <div class="container mx-auto px-6">
                <h3 class="text-3xl font-bold text-center text-[#1E293B] mb-12">
                    Why Choose TaskHive?
                </h3>
                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="bg-[#F9FAFB] rounded-xl p-8 border border-[#CBD5E1] hover:shadow-lg transition-shadow">
                        <div class="w-16 h-16 bg-[#5B21B6] rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-[#1E293B] mb-3">Automatic Task Assignment</h4>
                        <p class="text-[#1E293B]">No need to manually assign tasks. Our smart system handles it for you.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="bg-[#F9FAFB] rounded-xl p-8 border border-[#CBD5E1] hover:shadow-lg transition-shadow">
                        <div class="w-16 h-16 bg-[#06B6D4] rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-[#1E293B] mb-3">Track Progress</h4>
                        <p class="text-[#1E293B]">See status updates instantly with color-coded indicators.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="bg-[#F9FAFB] rounded-xl p-8 border border-[#CBD5E1] hover:shadow-lg transition-shadow">
                        <div class="w-16 h-16 bg-[#22C55E] rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-[#1E293B] mb-3">Multiple Projects</h4>
                        <p class="text-[#1E293B]">Developers can work on multiple projects seamlessly.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-[#1E293B] text-[#CBD5E1] py-12">
            <div class="container mx-auto px-6">
                <div class="grid md:grid-cols-3 gap-8">
                    <div>
                        <h5 class="text-white text-xl font-bold mb-4">TaskHive</h5>
                        <p class="text-sm">Simplifying task management for modern teams.</p>
                    </div>
                    <div>
                        <h6 class="text-white font-semibold mb-4">Quick Links</h6>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#" class="hover:text-[#06B6D4] transition-colors">About</a></li>
                            <li><a href="#" class="hover:text-[#06B6D4] transition-colors">Contact</a></li>
                            <li><a href="#" class="hover:text-[#06B6D4] transition-colors">Help</a></li>
                        </ul>
                    </div>
                    <div>
                        <h6 class="text-white font-semibold mb-4">Status Colors</h6>
                        <ul class="space-y-2 text-sm">
                            <li class="flex items-center gap-2">
                                <span class="w-3 h-3 bg-[#22C55E] rounded-full"></span>
                                Completed
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-3 h-3 bg-[#F97316] rounded-full"></span>
                                Pending
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-3 h-3 bg-[#EF4444] rounded-full"></span>
                                Overdue
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-[#CBD5E1] mt-8 pt-8 text-center text-sm">
                    <p>&copy; 2026 TaskHive. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</template>
