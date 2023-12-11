<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
defineProps(['message']);
</script>

<template>
    <div v-show="message">
        <p class="tw-text-sm tw-text-red-600">
            {{ message }}
        </p>
    </div>
</template>
