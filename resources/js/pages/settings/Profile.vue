<script setup lang="ts">
import { Form, Head, Link, usePage, router } from '@inertiajs/vue3';
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import DeleteUser from '@/components/DeleteUser.vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { edit } from '@/routes/profile';
import { send } from '@/routes/verification';
import { type BreadcrumbItem } from '@/types';
import { ref } from 'vue';

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

const profilePicturePreview = ref<string | null>(null);
const profilePictureInput = ref<HTMLInputElement | null>(null);

const selectNewProfilePicture = () => {
    profilePictureInput.value?.click();
};

const updateProfilePicturePreview = () => {
    const file = profilePictureInput.value?.files?.[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            profilePicturePreview.value = e.target?.result as string;
        };
        reader.readAsDataURL(file);
    }
};

const removeProfilePicture = () => {
    router.delete('/settings/profile/picture', {
        preserveScroll: true,
        onSuccess: () => {
            profilePicturePreview.value = null;
        },
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Profile settings" />

        <h1 class="sr-only">Profile Settings</h1>

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <Heading
                    variant="small"
                    title="Profile information"
                    description="Update your name and email address"
                />

                <Form
                    v-bind="ProfileController.update.form()"
                    class="space-y-6"
                    v-slot="{ errors, processing, recentlySuccessful }"
                >
                    <div class="grid gap-2">
                        <Label for="name">Name</Label>
                        <Input
                            id="name"
                            class="mt-1 block w-full"
                            name="name"
                            :default-value="user.name"
                            required
                            autocomplete="name"
                            placeholder="Full name"
                        />
                        <InputError class="mt-2" :message="errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="email">Email address</Label>
                        <Input
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            name="email"
                            :default-value="user.email"
                            required
                            autocomplete="username"
                            placeholder="Email address"
                        />
                        <InputError class="mt-2" :message="errors.email" />
                    </div>

                    <!-- Profile Picture -->
                    <div class="grid gap-2">
                        <Label>Profile Picture</Label>
                        <div class="flex items-center gap-4">
                            <!-- Current Profile Picture -->
                            <div class="relative">
                                <img 
                                    v-if="profilePicturePreview || user.profile_picture" 
                                    :src="profilePicturePreview || `/storage/${user.profile_picture}`" 
                                    alt="Profile" 
                                    class="w-20 h-20 rounded-full object-cover border-2 border-[#CBD5E1]"
                                />
                                <div 
                                    v-else 
                                    class="w-20 h-20 rounded-full bg-[#5B21B6] flex items-center justify-center text-white text-2xl font-bold"
                                >
                                    {{ user.name.charAt(0).toUpperCase() }}
                                </div>
                            </div>
                            
                            <!-- Upload Buttons -->
                            <div class="flex flex-col gap-2">
                                <input 
                                    ref="profilePictureInput"
                                    type="file" 
                                    name="profile_picture"
                                    accept="image/*"
                                    class="hidden"
                                    @change="updateProfilePicturePreview"
                                />
                                <Button 
                                    type="button"
                                    variant="outline"
                                    @click="selectNewProfilePicture"
                                >
                                    Select New Photo
                                </Button>
                                <Button 
                                    v-if="user.profile_picture"
                                    type="button"
                                    variant="outline"
                                    @click="removeProfilePicture"
                                    class="text-[#EF4444] hover:text-[#DC2626]"
                                >
                                    Remove Photo
                                </Button>
                            </div>
                        </div>
                        <p class="text-sm text-[#64748B]">JPG, PNG or GIF. Max size 2MB.</p>
                        <InputError class="mt-2" :message="errors.profile_picture" />
                    </div>

                    <div v-if="mustVerifyEmail && !user.email_verified_at">
                        <p class="-mt-4 text-sm text-muted-foreground">
                            Your email address is unverified.
                            <Link
                                :href="send()"
                                as="button"
                                class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500"
                            >
                                Click here to resend the verification email.
                            </Link>
                        </p>

                        <div
                            v-if="status === 'verification-link-sent'"
                            class="mt-2 text-sm font-medium text-green-600"
                        >
                            A new verification link has been sent to your email
                            address.
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <Button
                            :disabled="processing"
                            data-test="update-profile-button"
                            >Save</Button
                        >

                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p
                                v-show="recentlySuccessful"
                                class="text-sm text-neutral-600"
                            >
                                Saved.
                            </p>
                        </Transition>
                    </div>
                </Form>
            </div>

            <DeleteUser />
        </SettingsLayout>
    </AppLayout>
</template>
