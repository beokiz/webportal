<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    request: Object,
    status: String,
});

const form = useForm({
    two_factor_code: props.request.two_factor_code,
});

const submit = () => {
    form.post(route('2fa.verify'), {
        onFinish: () => form.reset('two_factor_code'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Two-Factor Authentication" />

        <div class="tw-mb-4 tw-text-sm tw-text-gray-600">
            You have received an email which contains 2FA verification code. If you haven't received it, press
            <Link :href="route('2fa.resend')" method="post" as="button">here</Link>.
        </div>

        <div v-if="status" class="tw-mb-4 tw-font-medium tw-text-sm tw-text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <v-text-field v-model="form.two_factor_code"
                              autofocus
                              :error-messages="form.errors.two_factor_code"
                              label="Code"
                              required></v-text-field>
            </div>

            <div class="tw-flex tw-items-center tw-justify-end mt-4">
                <v-btn-primary type="submit">Verify</v-btn-primary>
            </div>
        </form>
    </GuestLayout>
</template>
