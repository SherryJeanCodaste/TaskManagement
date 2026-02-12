<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import PasswordController from '@/actions/App/Http/Controllers/Settings/PasswordController';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { edit } from '@/routes/user-password';
import { type BreadcrumbItem } from '@/types';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Password settings',
        href: edit().url,
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Password settings" />

        <h1 class="sr-only">Password Settings</h1>

        <SettingsLayout>
            <div class="space-y-8">
                <div>
                    <h2 class="text-lg font-semibold text-[#111827] mb-1">Update Password</h2>
                    <p class="text-sm text-[#9CA3AF]">Ensure your account is using a long, random password to stay secure</p>
                </div>

                <Form
                    v-bind="PasswordController.update.form()"
                    :options="{
                        preserveScroll: true,
                    }"
                    reset-on-success
                    :reset-on-error="[
                        'password',
                        'password_confirmation',
                        'current_password',
                    ]"
                    class="space-y-6"
                    v-slot="{ errors, processing, recentlySuccessful }"
                >
                    <div class="space-y-2">
                        <Label for="current_password" class="text-sm font-medium text-[#111827]">Current Password</Label>
                        <Input
                            id="current_password"
                            name="current_password"
                            type="password"
                            class="w-full"
                            autocomplete="current-password"
                            placeholder="Current password"
                        />
                        <InputError class="text-xs" :message="errors.current_password" />
                    </div>

                    <div class="space-y-2">
                        <Label for="password" class="text-sm font-medium text-[#111827]">New Password</Label>
                        <Input
                            id="password"
                            name="password"
                            type="password"
                            class="w-full"
                            autocomplete="new-password"
                            placeholder="New password"
                        />
                        <InputError class="text-xs" :message="errors.password" />
                    </div>

                    <div class="space-y-2">
                        <Label for="password_confirmation" class="text-sm font-medium text-[#111827]">Confirm Password</Label>
                        <Input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            class="w-full"
                            autocomplete="new-password"
                            placeholder="Confirm password"
                        />
                        <InputError class="text-xs" :message="errors.password_confirmation" />
                    </div>

                    <div class="flex items-center gap-4 pt-2">
                        <Button
                            :disabled="processing"
                            data-test="update-password-button"
                            class="bg-[#3B82F6] hover:bg-[#2563EB] text-white"
                        >
                            Save Password
                        </Button>

                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p
                                v-show="recentlySuccessful"
                                class="text-sm text-[#10B981] font-medium"
                            >
                                Saved successfully.
                            </p>
                        </Transition>
                    </div>
                </Form>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
