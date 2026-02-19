<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="min-h-screen bg-[#F9FAFB] flex">
        <!-- Left Side - Branding -->
        <div class="hidden lg:flex lg:w-1/2 bg-[#7C3AED] p-12 flex-col justify-between">
            <div>
                <Link :href="home()" class="flex items-center gap-3 text-white">
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                        <AppLogoIcon class="size-8 fill-current text-white" />
                    </div>
                    <span class="text-2xl font-bold">TASKet</span>
                </Link>
            </div>
            
            <div class="space-y-6 text-white">
                <h2 class="text-4xl font-bold leading-tight">
                    Manage Client Tasks with Confidence
                </h2>
                <p class="text-lg text-white/90">
                    Structured updates, deadlines, and proof-based progress tracking.
                </p>
                
                <!-- Feature highlights -->
                <div class="space-y-4 mt-8">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-[#6D28D9] rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="text-white/90">Automatic task assignment</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-[#6D28D9] rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <span class="text-white/90">Real-time progress tracking</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-[#6D28D9] rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                            </svg>
                        </div>
                        <span class="text-white/90">Multiple project support</span>
                    </div>
                </div>
            </div>
            
            <div class="text-white/60 text-sm">
                &copy; 2026 TASKet. All rights reserved.
            </div>
        </div>

        <!-- Right Side - Form -->
        <div class="flex-1 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden mb-8 text-center">
                    <Link :href="home()" class="inline-flex items-center gap-3">
                        <div class="w-12 h-12 bg-[#7C3AED] rounded-lg flex items-center justify-center">
                            <AppLogoIcon class="size-8 fill-current text-white" />
                        </div>
                        <span class="text-2xl font-bold text-[#1E293B]">TASKet</span>
                    </Link>
                </div>

                <div class="bg-white rounded-2xl shadow-xl p-8 border border-[#CBD5E1]">
                    <div class="mb-8 text-center">
                        <h1 class="text-3xl font-bold text-[#1E293B] mb-2">{{ title }}</h1>
                        <p class="text-[#1E293B]">{{ description }}</p>
                    </div>
                    
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>
