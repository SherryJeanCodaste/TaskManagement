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
    <Head title="Welcome to TASKet" />
    
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-purple-50">
        <!-- Header / Navbar -->
        <header class="bg-white/80 backdrop-blur-md border-b border-purple-100 sticky top-0 z-50">
            <nav class="container mx-auto px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <!-- Enhanced basket icon with gradient -->
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-purple-600 to-purple-700 rounded-xl blur opacity-75"></div>
                        <div class="relative bg-gradient-to-r from-purple-600 to-purple-700 p-2 rounded-xl">
                            <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M5 9L4 19C4 19.5523 4.44772 20 5 20H19C19.5523 20 20 19.5523 20 19L19 9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M7 9L9 4C9.26522 3.40973 9.85395 3 10.5 3H10.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M17 9L15 4C14.7348 3.40973 14.1461 3 13.5 3H13.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M10.5 3H13.5" stroke-width="2" stroke-linecap="round"/>
                                <path d="M5 9H19" stroke-width="2" stroke-linecap="round"/>
                                <path d="M9 13L11 15L15 11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                    <h1 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-purple-800 bg-clip-text text-transparent">TASKet</h1>
                </div>
                <div class="flex items-center gap-3">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="dashboard()"
                        class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-xl font-medium hover:from-purple-700 hover:to-purple-800 transition-all duration-200 shadow-lg hover:shadow-xl"
                    >
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link
                            :href="login()"
                            class="px-6 py-2.5 text-slate-700 hover:text-purple-600 transition-colors font-medium rounded-lg hover:bg-purple-50"
                        >
                            Login
                        </Link>
                        <Link
                            v-if="canRegister"
                            :href="register()"
                            class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-xl font-medium hover:from-purple-700 hover:to-purple-800 transition-all duration-200 shadow-lg hover:shadow-xl"
                        >
                            Sign Up
                        </Link>
                    </template>
                </div>
            </nav>
        </header>

        <!-- Hero Section -->
        <section class="container mx-auto px-6 py-16 lg:py-24">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Left: Main Content -->
                <div class="space-y-8">
                    <div class="space-y-4">
                        <div class="inline-flex items-center gap-2 px-4 py-2 bg-purple-100 text-purple-700 rounded-full text-sm font-medium">
                            <div class="w-2 h-2 bg-purple-500 rounded-full animate-pulse"></div>
                            Task Management Made Simple
                        </div>
                        <h2 class="text-4xl lg:text-6xl font-bold text-slate-900 leading-tight">
                            Streamline Your
                            <span class="bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                                Workflow
                            </span>
                        </h2>
                        <p class="text-xl text-slate-600 leading-relaxed max-w-lg">
                            Connect customers and developers seamlessly. Automate task assignment, track progress in real-time, and deliver results faster.
                        </p>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-4">
                        <Link
                            v-if="!$page.props.auth.user"
                            :href="register()"
                            class="group px-8 py-4 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-xl font-semibold text-lg hover:from-purple-700 hover:to-purple-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center gap-2"
                        >
                            Get Started Free
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </Link>
                        <Link
                            v-else
                            :href="dashboard()"
                            class="group px-8 py-4 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-xl font-semibold text-lg hover:from-purple-700 hover:to-purple-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center gap-2"
                        >
                            Go to Dashboard
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </Link>
                        <Link
                            v-if="!$page.props.auth.user"
                            :href="login()"
                            class="px-8 py-4 border-2 border-purple-200 text-purple-700 rounded-xl font-semibold text-lg hover:bg-purple-50 hover:border-purple-300 transition-all duration-200 flex items-center justify-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Login
                        </Link>
                    </div>

                    <!-- Stats -->
                    <div class="flex gap-8 pt-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-slate-900">100%</div>
                            <div class="text-sm text-slate-600">Automated</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-slate-900">Real-time</div>
                            <div class="text-sm text-slate-600">Updates</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-slate-900">Simple</div>
                            <div class="text-sm text-slate-600">Setup</div>
                        </div>
                    </div>
                </div>

                <!-- Right: Enhanced Illustration -->
                <div class="relative">
                    <!-- Background decoration -->
                    <div class="absolute inset-0 bg-gradient-to-r from-purple-100 to-pink-100 rounded-3xl transform rotate-3 opacity-50"></div>
                    <div class="relative bg-white rounded-2xl shadow-2xl p-8 border border-purple-100">
                        <div class="space-y-6">
                            <!-- Animated task flow -->
                            <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl border border-purple-200 transform hover:scale-105 transition-transform duration-200">
                                <div class="w-12 h-12 bg-gradient-to-r from-purple-600 to-purple-700 rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                                    C
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-slate-900">Customer creates task</p>
                                    <p class="text-sm text-slate-500">Define requirements & assign</p>
                                </div>
                                <div class="w-3 h-3 bg-purple-400 rounded-full animate-pulse"></div>
                            </div>
                            
                            <div class="flex justify-center">
                                <div class="w-8 h-8 bg-gradient-to-b from-cyan-400 to-cyan-600 rounded-full flex items-center justify-center animate-bounce">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                    </svg>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-orange-50 to-orange-100 rounded-xl border border-orange-200 transform hover:scale-105 transition-transform duration-200">
                                <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-orange-600 rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                                    D
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-slate-900">Developer receives task</p>
                                    <p class="text-sm text-slate-500">Automatic assignment</p>
                                </div>
                                <div class="w-3 h-3 bg-orange-400 rounded-full animate-pulse"></div>
                            </div>

                            <div class="flex justify-center">
                                <div class="w-8 h-8 bg-gradient-to-b from-cyan-400 to-cyan-600 rounded-full flex items-center justify-center animate-bounce" style="animation-delay: 0.5s;">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                    </svg>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-xl border border-green-200 transform hover:scale-105 transition-transform duration-200">
                                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center text-white shadow-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-slate-900">Task completed</p>
                                    <p class="text-sm text-slate-500">Track progress instantly</p>
                                </div>
                                <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Features Section -->
        <section class="py-20 bg-white/50 backdrop-blur-sm">
            <div class="container mx-auto px-6">
                <div class="text-center mb-16">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-100 to-pink-100 text-purple-700 rounded-full text-sm font-medium mb-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Powerful Features
                    </div>
                    <h3 class="text-3xl lg:text-4xl font-bold text-slate-900 mb-4">
                        Why Choose TASKet?
                    </h3>
                    <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                        Built for modern teams who value efficiency and simplicity
                    </p>
                </div>
                
                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="group bg-white rounded-2xl p-8 border border-purple-100 hover:border-purple-200 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                        <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-slate-900 mb-3">Smart Assignment</h4>
                        <p class="text-slate-600 leading-relaxed">Intelligent task distribution based on developer availability and expertise. No manual work required.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="group bg-white rounded-2xl p-8 border border-purple-100 hover:border-purple-200 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                        <div class="w-16 h-16 bg-gradient-to-r from-cyan-500 to-cyan-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-slate-900 mb-3">Real-time Tracking</h4>
                        <p class="text-slate-600 leading-relaxed">Monitor progress instantly with live updates and visual indicators. Stay informed without micromanaging.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="group bg-white rounded-2xl p-8 border border-purple-100 hover:border-purple-200 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                        <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-slate-900 mb-3">Multi-Project Support</h4>
                        <p class="text-slate-600 leading-relaxed">Seamlessly manage multiple projects and teams from a single dashboard. Scale without complexity.</p>
                    </div>
                </div>

                <!-- Additional features row -->
                <div class="grid md:grid-cols-2 gap-8 mt-12">
                    <div class="flex items-start gap-4 p-6 bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl border border-purple-100">
                        <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <div>
                            <h5 class="font-semibold text-slate-900 mb-2">Secure & Private</h5>
                            <p class="text-slate-600 text-sm">Enterprise-grade security with role-based access control and data encryption.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-4 p-6 bg-gradient-to-r from-cyan-50 to-blue-50 rounded-2xl border border-cyan-100">
                        <div class="w-12 h-12 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 0h10m-10 0a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2M9 12l2 2 4-4"></path>
                            </svg>
                        </div>
                        <div>
                            <h5 class="font-semibold text-slate-900 mb-2">Easy Setup</h5>
                            <p class="text-slate-600 text-sm">Get started in minutes with our intuitive interface and guided onboarding process.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 bg-gradient-to-r from-purple-600 to-purple-800">
            <div class="container mx-auto px-6 text-center">
                <div class="max-w-3xl mx-auto">
                    <h3 class="text-3xl lg:text-4xl font-bold text-white mb-6">
                        Ready to Transform Your Workflow?
                    </h3>
                    <p class="text-xl text-purple-100 mb-8 leading-relaxed">
                        Join thousands of teams already using TASKet to streamline their project management and boost productivity.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <Link
                            v-if="!$page.props.auth.user"
                            :href="register()"
                            class="group px-8 py-4 bg-white text-purple-700 rounded-xl font-semibold text-lg hover:bg-purple-50 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center gap-2"
                        >
                            Start Free Trial
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </Link>
                        <button class="px-8 py-4 border-2 border-white text-white rounded-xl font-semibold text-lg hover:bg-white hover:text-purple-700 transition-all duration-200 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Watch Demo
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-slate-900 text-slate-300 py-16">
            <div class="container mx-auto px-6">
                <div class="grid md:grid-cols-4 gap-8 mb-12">
                    <div class="md:col-span-2">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="bg-gradient-to-r from-purple-600 to-purple-700 p-2 rounded-xl">
                                <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M5 9L4 19C4 19.5523 4.44772 20 5 20H19C19.5523 20 20 19.5523 20 19L19 9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M7 9L9 4C9.26522 3.40973 9.85395 3 10.5 3H10.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M17 9L15 4C14.7348 3.40973 14.1461 3 13.5 3H13.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M10.5 3H13.5" stroke-width="2" stroke-linecap="round"/>
                                    <path d="M5 9H19" stroke-width="2" stroke-linecap="round"/>
                                    <path d="M9 13L11 15L15 11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <h5 class="text-white text-2xl font-bold">TASKet</h5>
                        </div>
                        <p class="text-slate-400 mb-6 max-w-md">
                            Simplifying task management for modern teams. Connect, automate, and deliver with confidence.
                        </p>
                        <div class="flex gap-4">
                            <a href="#" class="w-10 h-10 bg-slate-800 rounded-lg flex items-center justify-center hover:bg-purple-600 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                </svg>
                            </a>
                            <a href="#" class="w-10 h-10 bg-slate-800 rounded-lg flex items-center justify-center hover:bg-purple-600 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                                </svg>
                            </a>
                            <a href="#" class="w-10 h-10 bg-slate-800 rounded-lg flex items-center justify-center hover:bg-purple-600 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    
                    <div>
                        <h6 class="text-white font-semibold mb-4">Product</h6>
                        <ul class="space-y-3 text-sm">
                            <li><a href="#" class="hover:text-purple-400 transition-colors">Features</a></li>
                            <li><a href="#" class="hover:text-purple-400 transition-colors">Pricing</a></li>
                            <li><a href="#" class="hover:text-purple-400 transition-colors">Integrations</a></li>
                            <li><a href="#" class="hover:text-purple-400 transition-colors">API</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h6 class="text-white font-semibold mb-4">Support</h6>
                        <ul class="space-y-3 text-sm">
                            <li><a href="#" class="hover:text-purple-400 transition-colors">Help Center</a></li>
                            <li><a href="#" class="hover:text-purple-400 transition-colors">Contact Us</a></li>
                            <li><a href="#" class="hover:text-purple-400 transition-colors">Status</a></li>
                            <li><a href="#" class="hover:text-purple-400 transition-colors">Community</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-sm text-slate-400">
                        &copy; 2026 TASKet. All rights reserved.
                    </p>
                    <div class="flex gap-6 text-sm">
                        <a href="#" class="text-slate-400 hover:text-purple-400 transition-colors">Privacy Policy</a>
                        <a href="#" class="text-slate-400 hover:text-purple-400 transition-colors">Terms of Service</a>
                        <a href="#" class="text-slate-400 hover:text-purple-400 transition-colors">Cookie Policy</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>