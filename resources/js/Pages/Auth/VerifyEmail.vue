<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: String,
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
    <GuestLayout>
        <Head title="E-Mail-Verifizierung" />

        <div class="tw-mb-4 tw-text-sm tw-text-gray-600">
            Vielen Dank für Ihre Anmeldung! Bevor Sie beginnen können, würden Sie bitte Ihre E-Mail-Adresse bestätigen,
            indem Sie auf den Link klicken, den wir Ihnen gerade per E-Mail geschickt haben? Falls Sie die E-Mail nicht
            erhalten haben, senden wir Ihnen gerne eine weitere.
        </div>

        <div class="tw-mb-4 tw-font-medium tw-text-sm tw-text-green-600" v-if="verificationLinkSent">
            Ein neuer Bestätigungslink wurde an die E-Mail-Adresse gesendet, die Sie bei der Registrierung angegeben haben.
        </div>

        <form @submit.prevent="submit">
            <div class="tw-mt-4 tw-flex tw-items-center tw-justify-between">
                <v-btn-primary type="submit">Bestätigungs-E-Mail erneut senden</v-btn-primary>

                <Link
                    :href="route('auth.logout')"
                    method="post"
                    as="button"
                    class="tw-underline tw-text-sm tw-text-gray-600 hover:tw-text-gray-900 tw-rounded-md focus:tw-outline-none"
                    >Abmelden</Link
                >
            </div>
        </form>
    </GuestLayout>
</template>
