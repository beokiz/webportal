<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    first_name: '',
    last_name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const submit = () => {
    form.post(route('auth.register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <form @submit.prevent="submit">
            <div>
                <v-text-field autofocus
                              v-model="form.first_name"
                              :error-messages="form.errors.first_name"
                              label="First Name"
                              required></v-text-field>
            </div>

            <div class="tw-mt-4">
                <v-text-field autofocus
                              v-model="form.last_name"
                              :error-messages="form.errors.last_name"
                              label="Last Name"
                              required></v-text-field>
            </div>

            <div class="tw-mt-4">

                <v-text-field autocomplete="username"
                              v-model="form.email"
                              :error-messages="form.errors.email"
                              label="Email"
                              required></v-text-field>
            </div>

            <div class="tw-mt-4">

                <v-text-field type="password"
                              autocomplete="new-password"
                              v-model="form.password"
                              :error-messages="form.errors.password"
                              label="Password"
                              required></v-text-field>
            </div>

            <div class="tw-mt-4">

                <v-text-field type="password"
                              autocomplete="new-password"
                              v-model="form.password_confirmation"
                              :error-messages="form.errors.password_confirmation"
                              label="Confirm Password"
                              required></v-text-field>
            </div>

            <div class="tw-flex tw-items-center tw-justify-end tw-mt-4">
                <Link
                    :href="route('auth.login')"
                    class="tw-underline tw-text-sm tw-text-gray-600 hover:tw-text-gray-900 tw-rounded-md focus:tw-outline-none"
                >
                    Already registered?
                </Link>

                <v-btn-primary type="submit" class="tw-ml-4">Register</v-btn-primary>
            </div>
        </form>
    </GuestLayout>
</template>
