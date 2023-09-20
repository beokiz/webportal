<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section>
        <header>
            <h2 class="tw-text-lg tw-font-medium tw-text-gray-900">Profile Information</h2>

            <p class="tw-mt-1 tw-text-sm tw-text-gray-600">
                Update your account's profile information and email address.
            </p>
        </header>

        <form @submit.prevent="form.patch(route('profile.update'))" class="tw-mt-6 tw-space-y-6">
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    type="text"
                    class="tw-mt-1 tw-block tw-w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="tw-mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="tw-mt-1 tw-block tw-w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="tw-mt-2" :message="form.errors.email" />
            </div>

            <div v-if="props.mustVerifyEmail && user.email_verified_at === null">
                <p class="tw-text-sm tw-mt-2 tw-text-gray-800">
                    Your email address is unverified.
                    <Link
                        :href="route('auth.verification.send')"
                        method="post"
                        as="button"
                        class="tw-underline tw-text-sm tw-text-gray-600 hover:tw-text-gray-900 tw-rounded-md focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-offset-2 focus:tw-ring-indigo-500"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <div
                    v-show="props.status === 'verification-link-sent'"
                    class="tw-mt-2 tw-font-medium tw-text-sm tw-text-green-600"
                >
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <div class="tw-flex tw-items-center tw-gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition enter-from-class="tw-opacity-0" leave-to-class="tw-opacity-0" class="tw-transition tw-ease-in-out">
                    <p v-if="form.recentlySuccessful" class="tw-text-sm tw-text-gray-600">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
