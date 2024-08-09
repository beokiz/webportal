<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
const props = defineProps({
    text: String,
});
</script>

<template>
    <div v-if="text" class="tw-flex tw-items-center tw-justify-space-between tw-text-xs tw-bg-gray-100 tw-rounded-[10px] tw-p-4 tw-w-full sm:tw-w-3/4">
        <v-icon size="36" color="#1E3050" class="tw-mr-2">mdi-information-slab-circle</v-icon>

        <p v-html="text"></p>
    </div>
</template>
