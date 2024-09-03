<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';

defineProps({
    loginFormHtml: String,
    canResetPassword: Boolean,
    canRegister: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('auth.login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout :additionalHtml="loginFormHtml">
        <Head title="Anmeldung"/>

        <div v-if="status" class="tw-mb-4 tw-font-medium tw-text-sm tw-text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <v-text-field autocomplete="username" v-model="form.email" :error-messages="form.errors.email" label="Email" required></v-text-field>
            </div>

            <div class="mt-4">
                <v-text-field type="password" autocomplete="current-password" v-model="form.password" :error-messages="form.errors.password" label="Passwort" required></v-text-field>
            </div>

            <div class="tw-block tw-mt-4">
                <v-checkbox label="Angemeldet bleiben" v-model:checked="form.remember"></v-checkbox>
            </div>

            <div class="tw-flex tw-items-center tw-justify-end mt-4">
                <div>
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="tw-block tw-underline tw-text-sm tw-text-gray-600 hover:tw-text-gray-900 tw-rounded-md focus:tw-outline-none"
                    >
                        Passwort vergessen?
                    </Link>

<!--                    <Link-->
<!--                        v-if="canRegister"-->
<!--                        :href="route('auth.register')"-->
<!--                        class="tw-block tw-underline tw-text-sm tw-text-gray-600 hover:tw-text-gray-900 tw-rounded-md focus:tw-outline-none"-->
<!--                    >-->
<!--                        Noch kein Konto?-->
<!--                    </Link>-->
                </div>

                <v-btn-primary type="submit" class="tw-ml-4">Anmelden</v-btn-primary>
            </div>
        </form>
    </GuestLayout>
</template>
