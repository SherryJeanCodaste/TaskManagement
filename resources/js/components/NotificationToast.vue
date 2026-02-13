<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const showNotification = ref(false);
const notificationMessage = ref('');
const notificationType = ref<'success' | 'error'>('success');

// Watch for flash messages from the backend
watch(() => page.props.flash, (flash) => {
    if (flash?.success) {
        showSuccessNotification(flash.success);
    }
    if (flash?.error) {
        showErrorNotification(flash.error);
    }
}, { immediate: true, deep: true });

function showSuccessNotification(message: string) {
    notificationMessage.value = message;
    notificationType.value = 'success';
    showNotification.value = true;
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        hideNotification();
    }, 5000);
}

function showErrorNotification(message: string) {
    notificationMessage.value = message;
    notificationType.value = 'error';
    showNotification.value = true;
    
    // Auto-hide after 7 seconds (longer for errors)
    setTimeout(() => {
        hideNotification();
    }, 7000);
}

function hideNotification() {
    showNotification.value = false;
}

// Expose functions for manual use
defineExpose({
    showSuccessNotification,
    showErrorNotification,
    hideNotification
});
</script>

<template>
    <!-- Notification Toast -->
    <Transition
        enter-active-class="transition-all duration-300 ease-out"
        enter-from-class="opacity-0 -translate-y-2"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition-all duration-200 ease-in"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 -translate-y-2"
    >
        <div
            v-if="showNotification"
            class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 max-w-md w-full mx-4"
        >
            <div
                :class="{
                    'bg-[#D1FAE5] border-[#10B981] text-[#065F46]': notificationType === 'success',
                    'bg-[#FEE2E2] border-[#EF4444] text-[#991B1B]': notificationType === 'error'
                }"
                class="flex items-center justify-between px-4 py-3 rounded-lg border shadow-lg"
            >
                <!-- Icon -->
                <div class="flex items-center gap-3">
                    <!-- Success Icon -->
                    <svg
                        v-if="notificationType === 'success'"
                        class="w-5 h-5 text-[#10B981] flex-shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    
                    <!-- Error Icon -->
                    <svg
                        v-else
                        class="w-5 h-5 text-[#EF4444] flex-shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    
                    <!-- Message -->
                    <p class="text-sm font-medium">{{ notificationMessage }}</p>
                </div>
                
                <!-- Close Button -->
                <button
                    @click="hideNotification"
                    :class="{
                        'text-[#065F46] hover:text-[#047857]': notificationType === 'success',
                        'text-[#991B1B] hover:text-[#7F1D1D]': notificationType === 'error'
                    }"
                    class="ml-3 flex-shrink-0 transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </Transition>
</template>