<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import { computed, ref, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, usePage, router, Link } from '@inertiajs/vue3';
import { formatDateTime } from '@/Composables/common.js';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    imprintSupportHtml: String,
});

/*
 * Inertia events handling
 */
// For page reloading on pagination or searching
Inertia.on('success', (event) => {
    let newProps = event.detail.page.props;
    let pageType = event.detail.page.component;

    if (pageType === 'Other/ImprintAndSupport' && newProps) {
        //
    }
});

/*
 * Main data
 */
const currentUser = usePage().props.auth.user ?? {};  // Global info about user
</script>

<template>
    <Head title="Impressum und Support" />

    <AuthenticatedLayout :errors="errors">
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">Impressum und Support</h2>
        </template>

        <div class="tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8">
            <div v-html="imprintSupportHtml"></div>
        </div>
    </AuthenticatedLayout>
</template>
