<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import {Head, Link} from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

const props = defineProps({
    status: String,
});
</script>

<template>
    <GuestLayout>
        <Head title="E-Mail-Verifizierung"/>

        <v-container>
            <v-row>
                <v-col cols="12" class="tw-flex tw-items-center tw-justify-center tw-mb-8">
                    <Link href="/">
                        <ApplicationLogo class="tw-h-20 tw-fill-current tw-text-gray-500" />
                    </Link>
                </v-col>
            </v-row>
        </v-container>

        <v-container>
            <v-row>
                <v-col cols="12" class="tw-mb-8">
                    <h2 class="tw-font-semibold tw-text-base tw-text-gray-800 tw-leading-tight tw-mb-4">
                        Anmeldung zur BeoKiz-Schulung
                    </h2>

                    <p class="tw-mb-4">
                        Vielen Dank für Ihre Anmeldung!
                    </p>

                    <p class="tw-mb-4">
                        Ihre Registrierung ist fast abgeschlossen. Um die Anmeldung abzuschließen, bestätigen Sie bitte Ihre E-Mail-Adresse. Eine E-Mail wurde an die von Ihnen angegebene Adresse gesendet. Bitte klicken Sie auf den Bestätigungslink in dieser E-Mail.
                    </p>

                    <p class="tw-mb-4">
                        Falls Sie die E-Mail nicht erhalten haben, überprüfen Sie bitte Ihren Spam-Ordner oder versuchen Sie es erneut.
                    </p>

                    <p class="tw-mb-4">
                        Wir freuen uns darauf, Sie bald bei der BeoKiz-Schulung willkommen zu heißen!
                    </p>

                    <p class="tw-mb-4">
                        Mit freundlichen Grüßen, <br/>Ihr BeoKiz-Team
                    </p>
                </v-col>
            </v-row>
        </v-container>
    </GuestLayout>
</template>
