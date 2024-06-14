<!--
  - GorKa Team
  - Copyright (c) 2024  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import {ref, onMounted, watch} from 'vue';

const props = defineProps({
    modelValue: {
        type: String,
    },
    label: {
        type: String,
        default: null,
    },
    minHeight: {
        type: Number,
        default: 200,
    },
    maxHeight: {
        type: Number,
        default: 465,
    },
    maxWidth: {
        type: Number,
        default: 900,
    },
});

const content = ref(props.modelValue);

const emit = defineEmits(['update:modelValue']);

watch(content, (val) => {
    emit('update:modelValue', val);
});
</script>

<template>
    <div class="vuetify-pro-tiptap-wrap">
        <VuetifyTiptap
            v-model="content"
            :label="label"
            :min-height="minHeight"
            :max-height="maxHeight"
            :max-width="maxWidth" />
    </div>
</template>
