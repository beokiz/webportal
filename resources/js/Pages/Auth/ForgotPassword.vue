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

defineProps({
    status: String,
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        <div class="tw-mb-4 tw-text-sm tw-text-gray-600">
            Passwort vergessen? Kein Problem. Teilen Sie uns einfach Ihre E-Mail-Adresse mit und wir senden Ihnen einen
            Link zum Zurücksetzen des Passworts, mit dem Sie ein neues auswählen können.
        </div>

        <div v-if="status" class="tw-mb-4 tw-font-medium tw-text-sm tw-text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <v-text-field autocomplete="username"
                              autofocus
                              v-model="form.email"
                              :error-messages="form.errors.email"
                              label="Email" required></v-text-field>
            </div>

            <div class="tw-flex tw-items-center tw-justify-end mt-4">
                <v-btn-primary type="submit">Link zum Zurücksetzen des Passworts per E-Mail senden</v-btn-primary>
            </div>
        </form>
    </GuestLayout>
</template>
