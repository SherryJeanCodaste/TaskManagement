<script setup lang="ts">
import { Form, Head, Link, usePage } from '@inertiajs/vue3';
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import DeleteUser from '@/components/DeleteUser.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { edit } from '@/routes/profile';
import { send } from '@/routes/verification';
import { type BreadcrumbItem } from '@/types';

type Props = {
    mustVerifyEmail: boolean;
    status?: string;
};

defineProps<Props>();

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Profile settings',
        href: edit().url,
    },
];

const page = usePage();
const user = page.props.auth.user;
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Profile settings" />

        <h1 class="sr-only">Profile Settings</h1>

        <SettingsLayout>
            <div class="space-y-8">
                <div>
                    <h2 class="text-lg font-semibold text-[#111827] mb-1">Profile Information</h2>
                    <p class="text-sm text-[#9CA3AF]">Update your name and email address</p>
                </div>

                <Form
                    v-bind="ProfileController.update.form()"
                    class="space-y-6"
                    v-slot="{ errors, processing, recentlySuccessful }"
                >
                    <div class="space-y-2">
                        <Label for="name" class="text-sm font-medium text-[#111827]">Name</Label>
                        <Input
                            id="name"
                            class="w-full"
                            name="name"
                            :default-value="user.name"
                            required
                            autocomplete="name"
                            placeholder="Full name"
                        />
                        <InputError class="text-xs" :message="errors.name" />
                    </div>

                    <div class="space-y-2">
                        <Label for="email" class="text-sm font-medium text-[#111827]">Email Address</Label>
                        <Input
                            id="email"
                            type="email"
                            class="w-full"
                            name="email"
                            :default-value="user.email"
                            required
                            autocomplete="username"
                            placeholder="Email address"
                        />
                        <InputError class="text-xs" :message="errors.email" />
                    </div>

                    <div v-if="mustVerifyEmail && !user.email_verified_at" class="bg-[#FEF3C7] border border-[#F59E0B] rounded-lg p-3">
                        <p class="text-sm text-[#78350F]">
                            Your email address is unverified.
                            <Link
                                :href="send()"
                                as="button"
                                class="underline hover:no-underline font-medium text-[#78350F]"
                            >
                                Click here to resend the verification email.
                            </Link>
                        </p>

                        <div
                            v-if="status === 'verification-link-sent'"
                            class="mt-2 text-sm font-medium text-[#166534]"
                        >
                            A new verification link has been sent to your email address.
                        </div>
                    </div>

                    <div class="flex items-center gap-4 pt-2">
                        <Button
                            :disabled="processing"
                            data-test="update-profile-button"
                            class="bg-[#3B82F6] hover:bg-[#2563EB] text-white"
                        >
                            Save Changes
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

                <div class="border-t border-[#E5E7EB] pt-8">
                    <DeleteUser />
                </div>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
