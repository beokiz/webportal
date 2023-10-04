<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import {computed} from 'vue';
import {usePage} from '@inertiajs/vue3';

const errors = computed(() => usePage().props.errors);

const hasErrors = computed(() => Object.keys(errors.value).length > 0);
</script>

<template>
    <div v-if="hasErrors">
        <div class="tw-font-medium tw-text-red-600">Whoops! Etwas ist schief gelaufen.</div>

        <ul class="tw-mt-3 tw-list-disc tw-list-inside tw-text-sm tw-text-red-600">
            <li v-for="(error, key) in errors" :key="key">{{ error }}</li>
        </ul>
    </div>
</template>
