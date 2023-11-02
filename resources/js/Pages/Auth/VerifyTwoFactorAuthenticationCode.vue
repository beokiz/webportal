<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';

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
        <Head title="Zwei-Faktor-Authentifizierung"/>

        <div class="tw-mb-4 tw-text-sm tw-text-gray-600">
            Sie haben eine E-Mail erhalten, die einen 2FA-Verifizierungscode enthält. Wenn Sie sie nicht erhalten haben, drücken Sie
            <Link :href="route('2fa.resend')" method="post" as="button">hier</Link>.
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
                <v-btn-primary type="submit">Verifizieren</v-btn-primary>
            </div>
        </form>
    </GuestLayout>
</template>
