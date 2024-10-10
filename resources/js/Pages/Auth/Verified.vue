<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { formatDate } from '@/Composables/common.js';

const props = defineProps({
    //
});
</script>

<template>
    <GuestLayout>
        <Head title="E-Mail bestätigt"/>

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
                        Vielen Dank!
                    </h2>

                    <p class="tw-mb-4">
                        Ihre E-Mailadresse ist hiermit bestätigt. Sie können das Fenster nun schließen.
                    </p>
                </v-col>
            </v-row>
        </v-container>
    </GuestLayout>
</template>
