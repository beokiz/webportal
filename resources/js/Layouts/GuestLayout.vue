<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    additionalHtml: String,
});

const isRegisterPage = route().current('auth.register');
const isVerifyEmailPage = route().current('verification.notice');
</script>

<template>
    <div class="tw-min-h-screen tw-flex tw-flex-col sm:tw-justify-center tw-items-center tw-pt-6 sm:tw-pt-0 tw-bg-gray-100">
        <div v-if="additionalHtml" class="tw-w-full sm:tw-max-w-[700px] tw-mb-6 tw-px-6 tw-py-4 tw-bg-white tw-shadow-md tw-overflow-hidden sm:tw-rounded-lg">
            <div class="guest-layout-custom-html" v-html="additionalHtml"></div>
        </div>

        <div v-if="!isRegisterPage && !isVerifyEmailPage">
            <Link href="/">
                <ApplicationLogo class="tw-h-20 tw-fill-current tw-text-gray-500" />
            </Link>
        </div>

        <div class="tw-w-full tw-mt-6 tw-px-6 tw-py-4 tw-bg-white tw-shadow-md tw-overflow-hidden sm:tw-rounded-lg"
             :class="{
                 'sm:tw-max-w-md': !isRegisterPage && !isVerifyEmailPage,
                 'sm:tw-max-w-7xl': isRegisterPage || isVerifyEmailPage,
             }"
        >
            <slot />
        </div>
    </div>
</template>
