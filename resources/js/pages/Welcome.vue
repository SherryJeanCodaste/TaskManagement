<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { dashboard, login, register } from '@/routes';
import { ref, onMounted } from 'vue';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);

const isVisible = ref(false);
const floatingCards = ref([
    { id: 1, x: 20, y: 10, delay: 0, rotation: 5 },
    { id: 2, x: 80, y: 20, delay: 0.5, rotation: -3 },
    { id: 3, x: 15, y: 70, delay: 1, rotation: 8 },
    { id: 4, x: 85, y: 80, delay: 1.5, rotation: -5 }
]);

onMounted(() => {
    setTimeout(() => {
        isVisible.value = true;
    }, 100);
    
    // Animate floating cards
    setInterval(() => {
        floatingCards.value.forEach(card => {
            card.y += Math.sin(Date.now() * 0.001 + card.delay) * 0.3;
            card.rotation += Math.cos(Date.now() * 0.0005 + card.delay) * 0.1;
        });
    }, 50);
});
</script>

<template>
    <Head title="Manage Client Tasks with Confidence - TASKet" />
    
    <div class="min-h-screen bg-gradient-to-br from-purple-50 via-white to-pink-50 relative overflow-hidden">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-purple-400/20 to-pink-400/20 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-br from-blue-400/20 to-purple-400/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
            
            <!-- Floating UI Cards -->
            <div 
                v-for="card in floatingCards" 
                :key="card.id"
                class="absolute w-16 h-16 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl shadow-lg"
                :style="{
                    left: card.x + '%',
                    top: card.y + '%',
                    transform: `rotate(${card.rotation}deg)`,
                    transition: 'all 0.1s ease-out'
                }"
            >
                <div class="w-full h-full bg-gradient-to-br from-purple-500/20 to-pink-500/20 rounded-xl flex items-center justify-center">
                    <div class="w-2 h-2 bg-purple-400 rounded-full animate-pulse"></div>
                </div>
            </div>
        </div>

        <!-- Header / Navbar -->
        <header class="relative z-50 bg-white/70 backdrop-blur-xl border-b border-white/20 sticky top-0">
            <nav class="container mx-auto px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
                <div class="flex items-center gap-2 sm:gap-3">
                    <!-- Enhanced Logo with Solid Purple -->
                    <div class="relative group">
                        <div class="absolute inset-0 bg-[#7C3AED] rounded-xl sm:rounded-2xl blur opacity-75 group-hover:opacity-100 transition-opacity"></div>
                        <div class="relative bg-[#7C3AED] p-2 sm:p-3 rounded-xl sm:rounded-2xl transform group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 lg:w-7 lg:h-7 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M5 9L4 19C4 19.5523 4.44772 20 5 20H19C19.5523 20 20 19.5523 20 19L19 9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M7 9L9 4C9.26522 3.40973 9.85395 3 10.5 3H10.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M17 9L15 4C14.7348 3.40973 14.1461 3 13.5 3H13.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M10.5 3H13.5" stroke-width="2" stroke-linecap="round"/>
                                <path d="M5 9H19" stroke-width="2" stroke-linecap="round"/>
                                <path d="M9 13L11 15L15 11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                    <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-[#7C3AED]">
                        TASKet
                    </h1>
                </div>
                <div class="flex items-center gap-2 sm:gap-3 lg:gap-4">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="dashboard()"
                        class="group px-4 sm:px-6 py-2 sm:py-3 bg-[#7C3AED] text-white rounded-lg sm:rounded-xl font-semibold text-sm sm:text-base hover:bg-[#6D28D9] transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                    >
                        <span class="flex items-center gap-1 sm:gap-2">
                            <span class="hidden sm:inline">Dashboard</span>
                            <span class="sm:hidden">Go</span>
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </span>
                    </Link>
                    <template v-else>
                        <Link
                            :href="login()"
                            class="px-3 sm:px-4 lg:px-6 py-2 sm:py-3 text-slate-700 hover:text-purple-600 transition-colors font-semibold rounded-lg sm:rounded-xl hover:bg-white/50 text-sm sm:text-base"
                        >
                            Login
                        </Link>
                        <Link
                            v-if="canRegister"
                            :href="register()"
                            class="group px-4 sm:px-6 py-2 sm:py-3 bg-[#7C3AED] text-white rounded-lg sm:rounded-xl font-semibold text-sm sm:text-base hover:bg-[#6D28D9] transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                        >
                            <span class="flex items-center gap-1 sm:gap-2">
                                <span class="hidden sm:inline">Get Started</span>
                                <span class="sm:hidden">Start</span>
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </span>
                        </Link>
                    </template>
                </div>
            </nav>
        </header>

        <!-- Hero Section with Gradient Background -->
        <section class="relative py-12 sm:py-16 lg:py-24 overflow-hidden">
            <!-- Gradient Background -->
            <div class="absolute inset-0 bg-gradient-to-br from-purple-100/50 via-white to-pink-100/50"></div>
            
            <div class="container mx-auto px-4 sm:px-6 relative z-10">
                <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                    <!-- Left: Main Content -->
                    <div class="space-y-6 lg:space-y-8" :class="{ 'animate-fade-in-up': isVisible }">
                        <div class="space-y-4 lg:space-y-6">
                            <!-- Badge -->
                            <div class="inline-flex items-center gap-2 sm:gap-3 px-4 sm:px-6 py-2 sm:py-3 bg-white/60 backdrop-blur-sm border border-purple-200/50 text-[#7C3AED] rounded-full font-medium shadow-lg text-sm sm:text-base">
                                <div class="w-2 h-2 bg-[#7C3AED] rounded-full animate-pulse"></div>
                                <span class="hidden sm:inline">Structured updates, deadlines, and proof-based progress</span>
                                <span class="sm:hidden">Structured updates & progress</span>
                            </div>
                            
                            <!-- Main Headline -->
                            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-bold text-slate-900 leading-tight">
                                Manage Client Tasks with
                                <span class="text-[#7C3AED]">
                                    Confidence
                                </span>
                            </h1>
                            
                            <!-- Subtitle -->
                            <p class="text-base sm:text-lg lg:text-xl xl:text-2xl text-slate-600 leading-relaxed max-w-2xl">
                                Connect customers and developers seamlessly. Automate task assignment, track progress in real-time, and deliver results with complete transparency.
                            </p>
                        </div>
                        
                        <!-- CTA Buttons -->
                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                            <Link
                                v-if="!$page.props.auth.user"
                                :href="register()"
                                class="group px-6 sm:px-8 py-3 sm:py-4 bg-[#7C3AED] text-white rounded-xl font-bold text-base sm:text-lg hover:bg-[#6D28D9] transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1 flex items-center justify-center gap-2 sm:gap-3"
                            >
                                Get Started
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </Link>
                            <Link
                                v-else
                                :href="dashboard()"
                                class="group px-6 sm:px-8 py-3 sm:py-4 bg-[#7C3AED] text-white rounded-xl font-bold text-base sm:text-lg hover:bg-[#6D28D9] transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1 flex items-center justify-center gap-2 sm:gap-3"
                            >
                                Go to Dashboard
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </Link>
                            <button class="group px-6 sm:px-8 py-3 sm:py-4 border-2 border-[#7C3AED] text-[#7C3AED] rounded-xl font-bold text-base sm:text-lg hover:bg-[#7C3AED]/10 hover:border-[#6D28D9] transition-all duration-300 flex items-center justify-center gap-2 sm:gap-3">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                View Demo
                            </button>
                        </div>

                        <!-- Stats -->
                        <div class="flex gap-4 sm:gap-6 lg:gap-8 pt-4 lg:pt-6">
                            <div class="text-center">
                                <div class="text-xl sm:text-2xl lg:text-3xl font-bold text-[#7C3AED]">100%</div>
                                <div class="text-xs sm:text-sm text-slate-600 font-medium">Automated</div>
                            </div>
                            <div class="text-center">
                                <div class="text-xl sm:text-2xl lg:text-3xl font-bold text-[#7C3AED]">Real-time</div>
                                <div class="text-xs sm:text-sm text-slate-600 font-medium">Updates</div>
                            </div>
                            <div class="text-center">
                                <div class="text-xl sm:text-2xl lg:text-3xl font-bold text-[#7C3AED]">Simple</div>
                                <div class="text-xs sm:text-sm text-slate-600 font-medium">Setup</div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: 3D Illustration with Floating UI Elements -->
                    <div class="relative mt-8 lg:mt-0" :class="{ 'animate-fade-in-right': isVisible }">
                        <!-- Main Dashboard Preview -->
                        <div class="relative bg-white/70 backdrop-blur-xl rounded-2xl lg:rounded-3xl shadow-2xl p-4 sm:p-6 lg:p-8 border border-white/20 transform hover:scale-105 transition-transform duration-500">
                            <!-- Floating Progress Bars -->
                            <div class="absolute -top-2 sm:-top-4 -left-2 sm:-left-4 bg-white/80 backdrop-blur-sm rounded-lg sm:rounded-xl p-2 sm:p-3 shadow-lg border border-purple-100 animate-bounce">
                                <div class="flex items-center gap-1 sm:gap-2">
                                    <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-green-500 rounded-full"></div>
                                    <span class="text-xs font-semibold text-slate-700">85% Complete</span>
                                </div>
                            </div>
                            
                            <!-- Floating Notification -->
                            <div class="absolute -top-2 sm:-top-4 -right-2 sm:-right-4 bg-white/80 backdrop-blur-sm rounded-lg sm:rounded-xl p-2 sm:p-3 shadow-lg border border-purple-100 animate-pulse">
                                <div class="flex items-center gap-1 sm:gap-2">
                                    <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-purple-500 rounded-full"></div>
                                    <span class="text-xs font-semibold text-slate-700">New Task</span>
                                </div>
                            </div>

                            <!-- Task Flow Visualization -->
                            <div class="space-y-3 sm:space-y-4 lg:space-y-6">
                                <!-- Customer Task Creation -->
                                <div class="flex items-center gap-2 sm:gap-3 lg:gap-4 p-2 sm:p-3 lg:p-4 bg-gradient-to-r from-[#7C3AED]/10 to-[#7C3AED]/20 rounded-xl lg:rounded-2xl border border-[#7C3AED]/30 transform hover:scale-105 transition-transform duration-300">
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-[#7C3AED] rounded-full flex items-center justify-center text-white font-bold shadow-lg text-sm sm:text-base">
                                        C
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-bold text-slate-900 text-xs sm:text-sm lg:text-base truncate">Customer creates task</p>
                                        <p class="text-xs sm:text-sm text-slate-600 truncate">Define requirements & assign</p>
                                    </div>
                                    <div class="w-2 h-2 sm:w-3 sm:h-3 bg-[#7C3AED] rounded-full animate-pulse flex-shrink-0"></div>
                                </div>
                                
                                <!-- Animated Arrow -->
                                <div class="flex justify-center">
                                    <div class="w-6 h-6 sm:w-8 sm:h-8 bg-[#7C3AED] rounded-full flex items-center justify-center animate-bounce shadow-lg">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                        </svg>
                                    </div>
                                </div>

                                <!-- Developer Assignment -->
                                <div class="flex items-center gap-2 sm:gap-3 lg:gap-4 p-2 sm:p-3 lg:p-4 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl lg:rounded-2xl border border-blue-200/50 transform hover:scale-105 transition-transform duration-300">
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full flex items-center justify-center text-white font-bold shadow-lg text-sm sm:text-base">
                                        D
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-bold text-slate-900 text-xs sm:text-sm lg:text-base truncate">Developer receives task</p>
                                        <p class="text-xs sm:text-sm text-slate-600 truncate">Automatic assignment</p>
                                    </div>
                                    <div class="w-2 h-2 sm:w-3 sm:h-3 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full animate-pulse flex-shrink-0"></div>
                                </div>

                                <!-- Animated Arrow -->
                                <div class="flex justify-center">
                                    <div class="w-6 h-6 sm:w-8 sm:h-8 bg-[#7C3AED] rounded-full flex items-center justify-center animate-bounce shadow-lg" style="animation-delay: 0.5s;">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                        </svg>
                                    </div>
                                </div>

                                <!-- Task Completion -->
                                <div class="flex items-center gap-2 sm:gap-3 lg:gap-4 p-2 sm:p-3 lg:p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl lg:rounded-2xl border border-green-200/50 transform hover:scale-105 transition-transform duration-300">
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-green-500 rounded-full flex items-center justify-center text-white shadow-lg">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-bold text-slate-900 text-xs sm:text-sm lg:text-base truncate">Task completed</p>
                                        <p class="text-xs sm:text-sm text-slate-600 truncate">Track progress instantly</p>
                                    </div>
                                    <div class="w-2 h-2 sm:w-3 sm:h-3 bg-green-500 rounded-full animate-pulse flex-shrink-0"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Features Section with Glassmorphism Cards -->
        <section class="py-16 sm:py-20 lg:py-24 relative">
            <!-- Background -->
            <div class="absolute inset-0 bg-gradient-to-br from-white via-purple-50/30 to-pink-50/30"></div>
            
            <div class="container mx-auto px-4 sm:px-6 relative z-10">
                <div class="text-center mb-12 sm:mb-16 lg:mb-20">
                    <div class="inline-flex items-center gap-2 sm:gap-3 px-4 sm:px-6 py-2 sm:py-3 bg-white/60 backdrop-blur-sm border border-[#7C3AED]/30 text-[#7C3AED] rounded-full font-medium mb-4 sm:mb-6 shadow-lg text-sm sm:text-base">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Why TASKet is Unique
                    </div>
                    <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-bold text-slate-900 mb-4 sm:mb-6">
                        Designed for
                        <span class="text-[#7C3AED]">
                            Startups, Agencies & IT Teams
                        </span>
                    </h2>
                    <p class="text-base sm:text-lg lg:text-xl text-slate-600 max-w-3xl mx-auto leading-relaxed">
                        Built for modern teams who value efficiency, transparency, and results
                    </p>
                </div>
                
                <!-- Glassmorphism Feature Cards -->
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8 mb-12 lg:mb-16">
                    <!-- Feature 1: Client Transparency -->
                    <div class="group relative">
                        <!-- Glow Effect -->
                        <div class="absolute inset-0 bg-[#7C3AED]/20 rounded-2xl lg:rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <!-- Card -->
                        <div class="relative bg-white/70 backdrop-blur-xl rounded-2xl lg:rounded-3xl p-6 lg:p-8 border border-white/20 shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 bg-[#7C3AED] rounded-xl lg:rounded-2xl flex items-center justify-center mb-4 sm:mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                <svg class="w-6 h-6 sm:w-7 sm:h-7 lg:w-8 lg:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg sm:text-xl font-bold text-slate-900 mb-3 sm:mb-4">Client Transparency</h3>
                            <p class="text-sm sm:text-base text-slate-600 leading-relaxed">Real-time visibility into project progress with proof-based updates and milestone tracking.</p>
                        </div>
                    </div>

                    <!-- Feature 2: Progress Proof -->
                    <div class="group relative">
                        <!-- Glow Effect -->
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 to-cyan-600/20 rounded-2xl lg:rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <!-- Card -->
                        <div class="relative bg-white/70 backdrop-blur-xl rounded-2xl lg:rounded-3xl p-6 lg:p-8 border border-white/20 shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl lg:rounded-2xl flex items-center justify-center mb-4 sm:mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                <svg class="w-6 h-6 sm:w-7 sm:h-7 lg:w-8 lg:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg sm:text-xl font-bold text-slate-900 mb-3 sm:mb-4">Progress Proof</h3>
                            <p class="text-sm sm:text-base text-slate-600 leading-relaxed">Evidence-based task completion with screenshots, code commits, and detailed progress reports.</p>
                        </div>
                    </div>

                    <!-- Feature 3: Structured Updates -->
                    <div class="group relative sm:col-span-2 lg:col-span-1">
                        <!-- Glow Effect -->
                        <div class="absolute inset-0 bg-gradient-to-r from-green-600/20 to-emerald-600/20 rounded-2xl lg:rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <!-- Card -->
                        <div class="relative bg-white/70 backdrop-blur-xl rounded-2xl lg:rounded-3xl p-6 lg:p-8 border border-white/20 shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl lg:rounded-2xl flex items-center justify-center mb-4 sm:mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                <svg class="w-6 h-6 sm:w-7 sm:h-7 lg:w-8 lg:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg sm:text-xl font-bold text-slate-900 mb-3 sm:mb-4">Structured Updates</h3>
                            <p class="text-sm sm:text-base text-slate-600 leading-relaxed">Organized communication flow with automated notifications and deadline management.</p>
                        </div>
                    </div>
                </div>

                <!-- Additional Features Row -->
                <div class="grid sm:grid-cols-2 gap-6 lg:gap-8">
                    <div class="flex items-start gap-4 sm:gap-6 p-6 lg:p-8 bg-white/60 backdrop-blur-xl rounded-2xl lg:rounded-3xl border border-white/20 shadow-lg hover:shadow-xl transition-all duration-300">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-[#7C3AED] rounded-xl lg:rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-base sm:text-lg font-bold text-slate-900 mb-2 sm:mb-3">Enterprise Security</h4>
                            <p class="text-sm sm:text-base text-slate-600">Bank-level encryption with role-based access control and comprehensive audit trails.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-4 sm:gap-6 p-6 lg:p-8 bg-white/60 backdrop-blur-xl rounded-2xl lg:rounded-3xl border border-white/20 shadow-lg hover:shadow-xl transition-all duration-300">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl lg:rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-base sm:text-lg font-bold text-slate-900 mb-2 sm:mb-3">Lightning Fast</h4>
                            <p class="text-sm sm:text-base text-slate-600">Optimized performance with instant updates and seamless user experience across all devices.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- How It Works - Modern Timeline -->
        <section class="py-24 bg-gradient-to-br from-slate-50 to-purple-50/30 relative overflow-hidden">
            <!-- Background Elements -->
            <div class="absolute top-20 left-10 w-32 h-32 bg-gradient-to-br from-purple-400/10 to-pink-400/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-20 right-10 w-40 h-40 bg-gradient-to-br from-blue-400/10 to-purple-400/10 rounded-full blur-2xl"></div>
            
            <div class="container mx-auto px-6 relative z-10">
                <div class="text-center mb-20">
                    <div class="inline-flex items-center gap-3 px-6 py-3 bg-white/80 backdrop-blur-sm border border-purple-200/50 text-purple-700 rounded-full font-medium mb-6 shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        How It Works
                    </div>
                    <h2 class="text-4xl lg:text-5xl font-bold text-slate-900 mb-6">
                        Simple
                        <span class="bg-gradient-to-r from-purple-600 via-purple-700 to-pink-600 bg-clip-text text-transparent">
                            3-Step Process
                        </span>
                    </h2>
                    <p class="text-xl text-slate-600 max-w-2xl mx-auto">
                        From task creation to completion in three seamless steps
                    </p>
                </div>

                <!-- Horizontal Timeline -->
                <div class="relative">
                    <!-- Progress Line -->
                    <div class="absolute top-1/2 left-0 right-0 h-1 bg-gradient-to-r from-purple-200 via-purple-300 to-pink-300 rounded-full transform -translate-y-1/2"></div>
                    <div class="absolute top-1/2 left-0 w-2/3 h-1 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full transform -translate-y-1/2 animate-pulse"></div>

                    <div class="grid md:grid-cols-3 gap-8 relative z-10">
                        <!-- Step 1 -->
                        <div class="text-center group">
                            <div class="relative mb-8">
                                <div class="w-20 h-20 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center mx-auto shadow-xl group-hover:scale-110 transition-transform duration-300">
                                    <span class="text-2xl font-bold text-white">1</span>
                                </div>
                                <div class="absolute inset-0 bg-gradient-to-r from-purple-500/20 to-pink-500/20 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>
                            <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-white/50 shadow-lg group-hover:shadow-xl transition-all duration-300">
                                <h3 class="text-xl font-bold text-slate-900 mb-3">Create Task</h3>
                                <p class="text-slate-600">Define your requirements, set deadlines, and specify the type of developer needed.</p>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div class="text-center group">
                            <div class="relative mb-8">
                                <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full flex items-center justify-center mx-auto shadow-xl group-hover:scale-110 transition-transform duration-300">
                                    <span class="text-2xl font-bold text-white">2</span>
                                </div>
                                <div class="absolute inset-0 bg-gradient-to-r from-blue-500/20 to-cyan-500/20 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>
                            <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-white/50 shadow-lg group-hover:shadow-xl transition-all duration-300">
                                <h3 class="text-xl font-bold text-slate-900 mb-3">Auto Assignment</h3>
                                <p class="text-slate-600">Our smart system automatically assigns tasks to the most suitable available developer.</p>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="text-center group">
                            <div class="relative mb-8">
                                <div class="w-20 h-20 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center mx-auto shadow-xl group-hover:scale-110 transition-transform duration-300">
                                    <span class="text-2xl font-bold text-white">3</span>
                                </div>
                                <div class="absolute inset-0 bg-gradient-to-r from-green-500/20 to-emerald-500/20 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>
                            <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-white/50 shadow-lg group-hover:shadow-xl transition-all duration-300">
                                <h3 class="text-xl font-bold text-slate-900 mb-3">Track Progress</h3>
                                <p class="text-slate-600">Monitor real-time progress with proof-based updates and milestone notifications.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Screenshot Section with Overlapping Screens -->
        <section class="py-24 relative overflow-hidden">
            <!-- Background -->
            <div class="absolute inset-0 bg-gradient-to-br from-white via-purple-50/20 to-pink-50/20"></div>
            
            <div class="container mx-auto px-6 relative z-10">
                <div class="text-center mb-20">
                    <div class="inline-flex items-center gap-3 px-6 py-3 bg-white/80 backdrop-blur-sm border border-purple-200/50 text-purple-700 rounded-full font-medium mb-6 shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Dashboard Preview
                    </div>
                    <h2 class="text-4xl lg:text-5xl font-bold text-slate-900 mb-6">
                        Beautiful
                        <span class="bg-gradient-to-r from-purple-600 via-purple-700 to-pink-600 bg-clip-text text-transparent">
                            Interface
                        </span>
                    </h2>
                    <p class="text-xl text-slate-600 max-w-2xl mx-auto">
                        Intuitive design that makes task management a pleasure, not a chore
                    </p>
                </div>

                <!-- Overlapping Dashboard Screens -->
                <div class="relative max-w-6xl mx-auto">
                    <!-- Main Dashboard Screen -->
                    <div class="relative bg-white rounded-3xl shadow-2xl border border-purple-100 overflow-hidden transform hover:scale-105 transition-transform duration-500">
                        <!-- Purple Glow -->
                        <div class="absolute inset-0 bg-gradient-to-r from-purple-500/10 to-pink-500/10 blur-3xl"></div>
                        
                        <!-- Screen Content -->
                        <div class="relative p-8">
                            <!-- Header -->
                            <div class="flex items-center justify-between mb-8">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg"></div>
                                    <span class="text-xl font-bold text-slate-900">TASKet Dashboard</span>
                                </div>
                                <div class="flex gap-2">
                                    <div class="w-3 h-3 bg-red-400 rounded-full"></div>
                                    <div class="w-3 h-3 bg-yellow-400 rounded-full"></div>
                                    <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                                </div>
                            </div>

                            <!-- Stats Cards -->
                            <div class="grid grid-cols-4 gap-4 mb-8">
                                <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-4 border border-purple-100">
                                    <div class="text-2xl font-bold text-purple-600">24</div>
                                    <div class="text-sm text-slate-600">Total Tasks</div>
                                </div>
                                <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl p-4 border border-blue-100">
                                    <div class="text-2xl font-bold text-blue-600">8</div>
                                    <div class="text-sm text-slate-600">In Progress</div>
                                </div>
                                <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 border border-green-100">
                                    <div class="text-2xl font-bold text-green-600">16</div>
                                    <div class="text-sm text-slate-600">Completed</div>
                                </div>
                                <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-xl p-4 border border-orange-100">
                                    <div class="text-2xl font-bold text-orange-600">67%</div>
                                    <div class="text-sm text-slate-600">Efficiency</div>
                                </div>
                            </div>

                            <!-- Task List Preview -->
                            <div class="space-y-3">
                                <div class="flex items-center gap-4 p-4 bg-white rounded-xl border border-slate-200 shadow-sm">
                                    <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold">F</div>
                                    <div class="flex-1">
                                        <div class="font-semibold text-slate-900">Frontend Dashboard Redesign</div>
                                        <div class="text-sm text-slate-500">Due in 2 days</div>
                                    </div>
                                    <div class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">Completed</div>
                                </div>
                                <div class="flex items-center gap-4 p-4 bg-white rounded-xl border border-slate-200 shadow-sm">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full flex items-center justify-center text-white font-bold">B</div>
                                    <div class="flex-1">
                                        <div class="font-semibold text-slate-900">API Integration</div>
                                        <div class="text-sm text-slate-500">Due tomorrow</div>
                                    </div>
                                    <div class="px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-sm font-medium">In Progress</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Floating Mobile Preview -->
                    <div class="absolute -bottom-8 -right-8 w-64 bg-white rounded-3xl shadow-xl border border-purple-100 p-4 transform rotate-12 hover:rotate-6 transition-transform duration-500">
                        <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl p-4 text-white">
                            <div class="text-sm font-medium mb-2">Mobile Dashboard</div>
                            <div class="text-xs opacity-90">Access anywhere, anytime</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Final CTA Section with Gradient Background -->
        <section class="py-24 bg-gradient-to-r from-purple-600 via-purple-700 to-pink-600 relative overflow-hidden">
            <!-- Animated Background Elements -->
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-40 -left-40 w-80 h-80 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
                <div class="absolute -bottom-40 -right-40 w-80 h-80 bg-white/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
            </div>
            
            <div class="container mx-auto px-6 text-center relative z-10">
                <div class="max-w-4xl mx-auto">
                    <!-- Glowing Badge -->
                    <div class="inline-flex items-center gap-3 px-6 py-3 bg-white/20 backdrop-blur-sm border border-white/30 text-white rounded-full font-medium mb-8 shadow-lg">
                        <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                        Ready to Transform Your Workflow?
                    </div>
                    
                    <h2 class="text-4xl lg:text-6xl font-bold text-white mb-8 leading-tight">
                        Start Managing Tasks Like a
                        <span class="bg-gradient-to-r from-yellow-300 to-orange-300 bg-clip-text text-transparent">
                            Pro Today
                        </span>
                    </h2>
                    
                    <p class="text-xl lg:text-2xl text-purple-100 mb-12 leading-relaxed max-w-3xl mx-auto">
                        Join thousands of teams already using TASKet to streamline their project management, boost productivity, and deliver exceptional results.
                    </p>
                    
                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-6 justify-center mb-12">
                        <Link
                            v-if="!$page.props.auth.user"
                            :href="register()"
                            class="group px-10 py-5 bg-white text-purple-700 rounded-2xl font-bold text-lg hover:bg-purple-50 transition-all duration-300 shadow-2xl hover:shadow-3xl transform hover:-translate-y-1 flex items-center justify-center gap-3"
                        >
                            Start Free Trial
                            <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </Link>
                        <Link
                            v-else
                            :href="dashboard()"
                            class="group px-10 py-5 bg-white text-purple-700 rounded-2xl font-bold text-lg hover:bg-purple-50 transition-all duration-300 shadow-2xl hover:shadow-3xl transform hover:-translate-y-1 flex items-center justify-center gap-3"
                        >
                            Go to Dashboard
                            <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </Link>
                        <button class="group px-10 py-5 border-2 border-white/30 text-white rounded-2xl font-bold text-lg hover:bg-white/10 hover:border-white/50 transition-all duration-300 flex items-center justify-center gap-3 backdrop-blur-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Watch Demo
                        </button>
                    </div>

                    <!-- Trust Indicators -->
                    <div class="flex flex-wrap justify-center items-center gap-8 text-purple-200">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="font-medium">No Credit Card Required</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="font-medium">14-Day Free Trial</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="font-medium">Cancel Anytime</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Premium Footer -->
        <footer class="bg-slate-900 text-slate-300 py-20 relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-5">
                <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.15) 1px, transparent 0); background-size: 20px 20px;"></div>
            </div>
            
            <div class="container mx-auto px-6 relative z-10">
                <div class="grid md:grid-cols-4 gap-12 mb-16">
                    <!-- Brand Section -->
                    <div class="md:col-span-2">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="relative group">
                                <div class="absolute inset-0 bg-gradient-to-r from-purple-600 to-pink-600 rounded-2xl blur opacity-75 group-hover:opacity-100 transition-opacity"></div>
                                <div class="relative bg-gradient-to-r from-purple-600 to-pink-600 p-3 rounded-2xl">
                                    <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path d="M5 9L4 19C4 19.5523 4.44772 20 5 20H19C19.5523 20 20 19.5523 20 19L19 9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M7 9L9 4C9.26522 3.40973 9.85395 3 10.5 3H10.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M17 9L15 4C14.7348 3.40973 14.1461 3 13.5 3H13.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M10.5 3H13.5" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M5 9H19" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M9 13L11 15L15 11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-white text-3xl font-bold">TASKet</h3>
                        </div>
                        <p class="text-slate-400 mb-8 max-w-md text-lg leading-relaxed">
                            Simplifying task management for modern teams. Connect, automate, and deliver with complete confidence and transparency.
                        </p>
                        <div class="flex gap-4">
                            <a href="#" class="w-12 h-12 bg-slate-800 rounded-xl flex items-center justify-center hover:bg-gradient-to-r hover:from-purple-600 hover:to-pink-600 transition-all duration-300 group">
                                <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                </svg>
                            </a>
                            <a href="#" class="w-12 h-12 bg-slate-800 rounded-xl flex items-center justify-center hover:bg-gradient-to-r hover:from-purple-600 hover:to-pink-600 transition-all duration-300 group">
                                <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                            </a>
                            <a href="#" class="w-12 h-12 bg-slate-800 rounded-xl flex items-center justify-center hover:bg-gradient-to-r hover:from-purple-600 hover:to-pink-600 transition-all duration-300 group">
                                <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.097.118.112.221.083.343-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.746-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001.017.001z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Product Links -->
                    <div>
                        <h4 class="text-white font-bold text-lg mb-6">Product</h4>
                        <ul class="space-y-4">
                            <li><a href="#" class="hover:text-purple-400 transition-colors text-slate-400 hover:translate-x-1 transform duration-200 inline-block">Features</a></li>
                            <li><a href="#" class="hover:text-purple-400 transition-colors text-slate-400 hover:translate-x-1 transform duration-200 inline-block">Pricing</a></li>
                            <li><a href="#" class="hover:text-purple-400 transition-colors text-slate-400 hover:translate-x-1 transform duration-200 inline-block">Integrations</a></li>
                            <li><a href="#" class="hover:text-purple-400 transition-colors text-slate-400 hover:translate-x-1 transform duration-200 inline-block">API Docs</a></li>
                            <li><a href="#" class="hover:text-purple-400 transition-colors text-slate-400 hover:translate-x-1 transform duration-200 inline-block">Changelog</a></li>
                        </ul>
                    </div>
                    
                    <!-- Support Links -->
                    <div>
                        <h4 class="text-white font-bold text-lg mb-6">Support</h4>
                        <ul class="space-y-4">
                            <li><a href="#" class="hover:text-purple-400 transition-colors text-slate-400 hover:translate-x-1 transform duration-200 inline-block">Help Center</a></li>
                            <li><a href="#" class="hover:text-purple-400 transition-colors text-slate-400 hover:translate-x-1 transform duration-200 inline-block">Contact Us</a></li>
                            <li><a href="#" class="hover:text-purple-400 transition-colors text-slate-400 hover:translate-x-1 transform duration-200 inline-block">System Status</a></li>
                            <li><a href="#" class="hover:text-purple-400 transition-colors text-slate-400 hover:translate-x-1 transform duration-200 inline-block">Community</a></li>
                            <li><a href="#" class="hover:text-purple-400 transition-colors text-slate-400 hover:translate-x-1 transform duration-200 inline-block">Security</a></li>
                        </ul>
                    </div>
                </div>
                
                <!-- Footer Bottom -->
                <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-6">
                    <p class="text-slate-400">
                        &copy; 2026 TASKet. All rights reserved. Built with ❤️ for modern teams.
                    </p>
                    <div class="flex gap-8 text-sm">
                        <a href="#" class="text-slate-400 hover:text-purple-400 transition-colors">Privacy Policy</a>
                        <a href="#" class="text-slate-400 hover:text-purple-400 transition-colors">Terms of Service</a>
                        <a href="#" class="text-slate-400 hover:text-purple-400 transition-colors">Cookie Policy</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fade-in-right {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes gradient {
    0%, 100% {
        background-size: 200% 200%;
        background-position: left center;
    }
    50% {
        background-size: 200% 200%;
        background-position: right center;
    }
}

.animate-fade-in-up {
    animation: fade-in-up 0.8s ease-out;
}

.animate-fade-in-right {
    animation: fade-in-right 0.8s ease-out 0.2s both;
}

.animate-gradient {
    background-size: 200% 200%;
    animation: gradient 3s ease infinite;
}
</style>