<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import NotificationToast from '@/components/NotificationToast.vue';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="min-h-screen bg-[#F9FAFB] flex">
        <!-- Left Side - Branding -->
        <div class="hidden lg:flex lg:w-1/2 bg-[#5B21B6] p-12 flex-col justify-between">
            <div>
                <Link :href="home()" class="flex items-center gap-3 text-white">
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                        <AppLogoIcon class="size-8 fill-current text-white" />
                    </div>
                    <span class="text-2xl font-bold">TaskHive</span>
                </Link>
            </div>
            
            <div class="space-y-6 text-white">
                <h2 class="text-4xl font-bold leading-tight">
                    Simplify Your Task Management
                </h2>
                <p class="text-lg text-white/90">
                    Track tasks, assign automatically, and stay organized with TaskHive.
                </p>
                
                <!-- Feature highlights -->
                <div class="space-y-4 mt-8">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-[#06B6D4] rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="text-white/90">Automatic task assignment</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-[#06B6D4] rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="text-white/90">Real-time progress tracking</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-[#06B6D4] rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="text-white/90">Multiple project support</span>
                    </div>
                </div>
            </div>
            
            <div class="text-white/60 text-sm">
                &copy; 2026 TaskHive. All rights reserved.
            </div>
        </div>

        <!-- Right Side - Form -->
        <div class="flex-1 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden mb-8 text-center">
                    <Link :href="home()" class="inline-flex items-center gap-3">
                        <div class="w-12 h-12 bg-[#5B21B6] rounded-lg flex items-center justify-center">
                            <AppLogoIcon class="size-8 fill-current text-white" />
                        </div>
                        <span class="text-2xl font-bold text-[#1E293B]">TaskHive</span>
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
        
        <!-- Global Notification Toast -->
        <NotificationToast />
    </div>
</template>
