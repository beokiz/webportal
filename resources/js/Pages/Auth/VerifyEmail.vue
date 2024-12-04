<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

const props = defineProps({
    status: String,
    user: Object,
});

const userHasKita = computed(() => {
    return props.user?.kitas && props.user?.kitas[0];
});

const isMergedTraining = computed(() => {
    return userHasKita.value && props.user.kitas[0]?.num_pedagogical_staff <= 10;
});

const isUserKitaHasSelfTrainingOperator = computed(() => {
    return userHasKita.value && props.user?.kitas[0]?.operator_id;
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
            <template v-if="userHasKita">
                <template v-if="!isUserKitaHasSelfTrainingOperator">
                    <v-row>
                        <v-col cols="12" class="tw-mb-8">
                            <h2 class="tw-font-semibold tw-text-base tw-text-gray-800 tw-leading-tight tw-mb-4">
                                Anmeldung zur BeoKiz-Schulung
                            </h2>

                            <p class="tw-mb-4">
                                Vielen Dank für Ihr Interesse an der BeoKiz-Schulung.
                            </p>

                            <p class="tw-mb-4">
                                Um Ihre Anmeldung zur BeoKiz-Schulung zu übermitteln, bestätigen Sie bitte Ihre E-Mailadresse. Eine E-Mail wurde an die von Ihnen hinterlegte Adresse verschickt. Dieser Schritt stellt sicher, dass wir Sie unter Ihrer angegebenen E-Mailadresse erreichen können.
                            </p>

                            <p class="tw-mb-4">
                                Falls sie die E-Mail nicht erhalten haben, überprüfen Sie bitte Ihren Spam-Ordner oder versuchen Sie es erneut.
                            </p>

                            <p v-if="!isMergedTraining" class="tw-mb-4">
                                Zur finalen Bestätigung Ihres Schulungstermins wird sich ein Beokiz Multiplikator, oder eine BeoKiz Multiplikatorin mit Ihnen in Verbindung setzen.
                            </p>

                            <p class="tw-mb-4">
                                Wir freuen uns darauf, Sie bald bei der BeoKiz-SchuIung willkommen zu heißen.
                            </p>

                            <p class="tw-mb-4">
                                Mit freundlichen Grüßen, <br/>Ihr BeoKiz-Team
                            </p>
                        </v-col>
                    </v-row>
                </template>
                <template v-else>
                    <v-row>
                        <v-col cols="12" class="tw-mb-8">
                            <h2 class="tw-font-semibold tw-text-base tw-text-gray-800 tw-leading-tight tw-mb-4">
                                Anmeldung zur BeoKiz-Schulung
                            </h2>

                            <p class="tw-mb-4">
                                Wir haben Ihre Anmeldung zur BeoKiz-Schulung registriert. In Kürze wird sich der/die für Ihren Träger zuständige:r und zertifizierte:r BeoKiz-Multiplikator:in mit Ihnen in Verbindung setzen.
                            </p>

                            <p class="tw-mb-4">
                                Sollte dies innerhalb der kommenden 2-3 Wochen nicht geschehen, kontaktieren Sie bitte Ihren Träger.
                            </p>

                            <p class="tw-mb-4">
                                Wir freuen uns, Sie bald bei der BeoKiz-Schulung willkommen zu heißen!
                            </p>

                            <p class="tw-mb-4">
                                Mit freundlichen Grüßen, <br/>Ihr BeoKiz-Team
                            </p>
                        </v-col>
                    </v-row>
                </template>
            </template>
            <template v-else>
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
            </template>
        </v-container>
    </GuestLayout>
</template>
