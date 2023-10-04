<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import { computed, defineEmits } from 'vue';

const props = defineProps({
    errors: Object,
    successes: Object,
});

const hasErrors = computed(() => Object.keys(props.errors).length > 0);
const hasSuccesses = computed(() => Object.keys(props.successes).length > 0);

// TODO: Refactor this code for better performance.
// const messageState = ref(true);
// const closeMessage = () => {
//     if (editedIndex.value > -1) {
//         Object.assign(props.products.value[editedIndex.value], editedItem.value);
//     } else {
//         props.products.value.push(editedItem.value);
//     }
//     close();
// };

const emits = defineEmits(['clearErrorsAndSuccesses']);

const hide = () => {
    emits('clearErrorsAndSuccesses');
};
</script>

<template>
<!--    <div v-if="closeMessageState" class="tw-flex tw-items-center tw-justify-between tw-max-w-7xl tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8">-->
    <div class="tw-flex tw-items-center tw-justify-between tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8">
        <div v-if="hasErrors">
            <div class="tw-font-medium tw-text-red-600">Whoops! Etwas ist schief gelaufen.</div>

            <ul class="tw-mt-3 tw-list-disc tw-list-inside tw-text-sm tw-text-red-600">
                <li v-for="(error, key) in errors" :key="key">{{ error }}</li>
            </ul>
        </div>

        <div v-if="hasSuccesses">
            <div class="tw-font-medium tw-text-green-600">Erfolg!</div>

            <ul class="tw-mt-3 tw-list-disc tw-list-inside tw-text-sm tw-text-green-600">
                <li v-for="(success, key) in successes" :key="key">{{ success }}</li>
            </ul>
        </div>

<!--        <v-hover v-slot:default="{ isHovering, props }">-->
<!--&lt;!&ndash;            <v-btn @click="$emit('closeErrors')" v-bind="props" :color="isHovering ? 'accent' : 'primary'" dark>&ndash;&gt;-->
<!--            <v-btn v-bind="props" :color="isHovering ? 'accent' : 'primary'" dark @click="hide">-->
<!--                Close-->
<!--            </v-btn>-->
<!--        </v-hover>-->
    </div>
</template>
