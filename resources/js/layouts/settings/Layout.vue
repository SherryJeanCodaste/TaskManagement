<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import { toUrl } from '@/lib/utils';
import { edit as editAppearance } from '@/routes/appearance';
import { edit as editProfile } from '@/routes/profile';
import { show } from '@/routes/two-factor';
import { edit as editPassword } from '@/routes/user-password';
import { type NavItem } from '@/types';

const sidebarNavItems: NavItem[] = [
    {
        title: 'Profile',
        href: editProfile(),
    },
    {
        title: 'Password',
        href: editPassword(),
    },
    {
        title: 'Two-Factor Auth',
        href: show(),
    },
    {
        title: 'Appearance',
        href: editAppearance(),
    },
];

const { isCurrentUrl } = useCurrentUrl();
</script>

<template>
    <div class="flex h-full flex-1 flex-col p-8 bg-white">
        <div class="w-full max-w-5xl mx-auto space-y-6">
            <div>
                <h1 class="text-2xl font-semibold text-[#111827] mb-1">Settings</h1>
                <p class="text-sm text-[#9CA3AF]">Manage your profile and account settings</p>
            </div>

            <div class="flex gap-8">
                <aside class="w-48">
                    <nav class="space-y-1" aria-label="Settings">
                        <Link
                            v-for="item in sidebarNavItems"
                            :key="toUrl(item.href)"
                            :href="item.href"
                            :class="[
                                'block px-3 py-2 text-sm rounded-lg transition-colors',
                                isCurrentUrl(item.href)
                                    ? 'bg-[#F3F4F6] text-[#111827] font-medium'
                                    : 'text-[#111827] hover:bg-[#F9FAFB]'
                            ]"
                        >
                            {{ item.title }}
                        </Link>
                    </nav>
                </aside>

                <div class="flex-1 max-w-2xl">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>
