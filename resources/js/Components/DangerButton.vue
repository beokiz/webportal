<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button
        :type="type"
        class="tw-inline-flex tw-items-center tw-px-4 tw-py-2 tw-bg-red-600 tw-border tw-border-transparent tw-rounded-md tw-font-semibold tw-text-xs !tw-text-white tw-uppercase tw-tracking-widest hover:tw-bg-red-500 active:tw-bg-red-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-red-500 focus:tw-ring-offset-2 tw-transition tw-ease-in-out tw-duration-150"
    >
        <slot />
    </button>
</template>
