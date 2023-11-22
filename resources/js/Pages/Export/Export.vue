<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import { computed, ref, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, useForm, usePage, router, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NProgress from 'nprogress';
import { v4 as uuidv4 } from 'uuid';


const props = defineProps({
    token: String,
    errors: Object,
});

/*
 * Inertia events handling
 */
// For page reloading on pagination or searching
Inertia.on('success', (event) => {
    let newProps = event.detail.page.props;
    let pageType = event.detail.page.component;

    if (pageType === 'Export/Export' && newProps) {
        // searchFilter.value = newProps.filters.search ?? null;
    }
});

/*
 * Main data
 */
const currentUser = usePage().props.auth.user ?? {};  // Global info about user

const errors = ref(props.errors || {});
// const searchFilter = ref(props.filters.search ?? null);


// Methods
const exportForm = useForm({
    //
});

const makeExport = async () => {
    NProgress.start();

    try {
        const response = await axios.post(route('export.make'), {}, { responseType: 'blob' });

        const contentDisposition = response.headers['content-disposition'];
        const filenameIndex = contentDisposition.indexOf('filename=');

        let sanitizedFilename;

        if (filenameIndex !== -1) {
            const filename = contentDisposition.slice(filenameIndex + 9);
            sanitizedFilename = filename.replace(/['"]/g, '');
        } else {
            sanitizedFilename = uuidv4();
        }

        const blob = new Blob([response.data], { type: response.headers['content-type'] });
        const link = document.createElement('a');
        link.href = window.URL.createObjectURL(blob);
        link.download = sanitizedFilename;

        link.click();
    } catch (error) {
        console.error(error);
    }

    NProgress.done();
};
</script>

<template>
    <Head title="Übersicht Screenings" />

    <AuthenticatedLayout :errors="errors">
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">Übersicht Screenings</h2>
        </template>

        <div class="tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8">
            <div class="tw-bg-white tw-flex tw-justify-between tw-px-6 tw-py-6">
<!--                <div class="tw-w-full">-->
<!--                    <v-row>-->
<!--                        <v-col cols="12" sm="5">-->
<!--&lt;!&ndash;                            <v-text-field v-model="searchFilter" label="Name"></v-text-field>&ndash;&gt;-->
<!--                        </v-col>-->
<!--                    </v-row>-->
<!--                </div>-->

                <div class="tw-ml-6">
                    <v-hover v-slot:default="{ isHovering, props }">
                        <v-btn
                            class="tw-mt-2"
                            v-bind="props"
                            :color="isHovering ? 'accent' : 'primary'"
                            @click="makeExport"
                            dark
                        >Suche</v-btn>
                    </v-hover>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
