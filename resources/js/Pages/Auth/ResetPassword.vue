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
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    email: String,
    token: String,
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Reset Password" />

        <form @submit.prevent="submit">
            <div>
                <v-text-field autocomplete="username"
                              autofocus
                              v-model="form.email"
                              :error-messages="form.errors.email"
                              label="Email" required></v-text-field>
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
                <v-btn-primary type="submit">Reset Password</v-btn-primary>
            </div>
        </form>
    </GuestLayout>
</template>
