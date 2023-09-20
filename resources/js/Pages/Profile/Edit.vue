<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">Profile</h2>
        </template>

        <div class="tw-py-12">
            <div class="tw-max-w-7xl tw-mx-auto sm:tw-px-6 lg:tw-px-8 tw-space-y-6">
                <div class="p-4 sm:tw-p-8 tw-bg-white tw-shadow sm:tw-rounded-lg">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="tw-max-w-xl"
                    />
                </div>

                <div class="tw-p-4 sm:tw-p-8 tw-bg-white tw-shadow sm:tw-rounded-lg">
                    <UpdatePasswordForm class="tw-max-w-xl" />
                </div>

                <div class="tw-p-4 sm:tw-p-8 tw-bg-white tw-shadow sm:tw-rounded-lg">
                    <DeleteUserForm class="tw-max-w-xl" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
