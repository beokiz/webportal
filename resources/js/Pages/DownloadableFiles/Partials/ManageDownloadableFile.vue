<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import { computed, ref, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, useForm, usePage, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { prepareDate, ages } from "@/Composables/common.js";

const props = defineProps({
    downloadableFile: Object,
    errors: Object,
    from: String,
});


/*
 * Inertia events handling
 */
// For page reloading on pagination or searching
Inertia.on('success', (event) => {
    let newProps = event.detail.page.props;
    let pageType = event.detail.page.component;

    if (pageType === 'DownloadableFiles/Partials/ManageDownloadableFile' && newProps) {
        editedDownloadableFile.value = newProps.downloadableFile;
    }
});


/*
 * Main data
 */
const currentUser = usePage().props.auth.user ?? {};  // Global info about user

const editedDownloadableFile = ref(props.downloadableFile);
const errors = ref(props.errors || {});
const loading = ref(false);

// Computed
const backRoute = computed(() => {
    if (props.from) {
        const params = props.from.split(';');

        if (params.length === 3) {
            const routeName = params[0];
            const routeParams = {};

            routeParams[params[1]] = params[2];

            return route(routeName, routeParams)
        }
    }

    return route('settings.index');
});

// Methods
const manageForm = useForm({
    id: editedDownloadableFile.value.id,
    name: editedDownloadableFile.value.name,
});

const manageDownloadableFile = async () => {
    manageForm.processing = true;

    let formOptions = {
        preserveState: false,
        onSuccess: (page) => {

        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            manageForm.processing = false;
        },
    };

    manageForm.put(route('downloadable_files.update', {id: manageForm.id}), formOptions);
};
</script>

<template>
    <Head title="Datei bearbeiten"/>

    <AuthenticatedLayout :errors="errors">
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">Datei bearbeiten</h2>
        </template>

        <div class="tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8">
            <v-container>
                <v-row>
                    <v-col cols="12" sm="6">
                        <v-text-field v-model="manageForm.name"
                                      :error-messages="errors.name"
                                      label="Name*"
                                      required
                        ></v-text-field>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12" sm="12" align="right">
                        <v-hover v-slot:default="{ isHovering, props }">
                            <Link :href="backRoute">
                                <v-btn class="mr-2" variant="text" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Zurück</v-btn>
                            </Link>
                        </v-hover>
                        <v-hover v-slot:default="{ isHovering, props }">
                            <v-btn-primary @click="manageDownloadableFile" v-bind="props"
                                           :color="isHovering ? 'accent' : 'primary'">Speichern
                            </v-btn-primary>
                        </v-hover>
                    </v-col>
                </v-row>
            </v-container>
        </div>
    </AuthenticatedLayout>
</template>
