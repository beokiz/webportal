<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import {computed, ref, watch} from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, useForm, usePage, router, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NProgress from 'nprogress';
import { v4 as uuidv4 } from 'uuid';
import { ages } from '@/Composables/common';


const props = defineProps({
    domains: Object,
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
        //
    }
});


/*
 * Main data
 */
const currentUser = usePage().props.auth.user ?? {};  // Global info about user

const errors = ref(props.errors || {});
const fileInProgress = ref(false);
const rawFinishedAfter = ref(null);
const rawFinishedBefore = ref(null);
const finishedAfter = ref();
const finishedBefore = ref();
const age = ref('2.5');
const zipCode = ref(null);
const domain = ref(null);
const isMenuOpen = ref(false);
const isMenu2Open = ref(false);

// Computed
const domainsList = computed(() => {
    let list = [
        {
            id: null,
            name: 'Alle',
        },
    ];

    props.domains.map(item => {
        list.push({
            id: item.id,
            name: item.name,
        });

        return item;
    });

    return list;
});

//Watch
watch(finishedAfter, (val) => {
    rawFinishedAfter.value = prepareDate(val);
});

watch(finishedBefore, (val) => {
    rawFinishedBefore.value = prepareDate(val);
});

// Methods
const prepareDate = (inputDateString) => {
    console.log(inputDateString)

    const inputDate = new Date(inputDateString);

    const months = [
        'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
    ];

    const day = inputDate.getDate();
    const month = months[inputDate.getMonth()];
    const year = inputDate.getFullYear();

    return `${inputDate.toDateString().slice(0, 3)}, ${day}. ${month} ${year}`;
};

const exportForm = useForm({
    //
});

const makeExport = async () => {
    NProgress.start();
    fileInProgress.value = true;

    let preparedFinishedAfter = new Date(finishedAfter.value);
    let preparedFinishedBefore = new Date(finishedBefore.value);

    preparedFinishedAfter.setHours(preparedFinishedAfter.getHours() + 12);
    preparedFinishedBefore.setHours(preparedFinishedBefore.getHours() + 12);

    try {
        const response = await axios.post(route('export.make'), {
            finished_after: preparedFinishedAfter,
            finished_before: preparedFinishedBefore,
            age: age.value,
            zip_code: zipCode.value,
            domains: domain.value,
        }, { responseType: 'blob' });
        console.log(response.headers)
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
    fileInProgress.value = false;
};
</script>

<template>
    <Head title="Screenings exportieren" />

    <AuthenticatedLayout :errors="errors">
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">Screenings exportieren</h2>
        </template>

        <div class="tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8">
            <div class="tw-bg-white tw-flex tw-justify-between tw-px-6 tw-py-6">
                <div class="tw-w-full">
                    <v-row>
                        <v-col cols="12" sm="6">
                            <v-menu v-model="isMenuOpen"
                                    :return-value.sync="finishedAfter"
                                    :close-on-content-click="false">
                                <template v-slot:activator="{ props }">
                                    <v-text-field
                                        label="Abgegeben ab"
                                        class="tw-cursor-pointer"
                                        :model-value="rawFinishedAfter"
                                        prepend-icon="mdi-calendar"
                                        readonly
                                        v-bind="props"
                                        :disabled="fileInProgress"
                                    ></v-text-field>
                                </template>
                                <v-date-picker @update:modelValue="isMenuOpen = false" v-model="finishedAfter"></v-date-picker>
                            </v-menu>
                        </v-col>
                        <v-col cols="12" sm="6">
                            <v-menu v-model="isMenu2Open"
                                    :return-value.sync="finishedBefore"
                                    :close-on-content-click="false">
                                <template v-slot:activator="{ props }">
                                    <v-text-field
                                        label="Abgegeben bis"
                                        class="tw-cursor-pointer"
                                        :model-value="rawFinishedBefore"
                                        prepend-icon="mdi-calendar"
                                        readonly
                                        v-bind="props"
                                        :disabled="fileInProgress"
                                    ></v-text-field>
                                </template>
                                <v-date-picker @update:modelValue="isMenu2Open = false" v-model="finishedBefore"></v-date-picker>
                            </v-menu>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col v-if="$page.props.auth.user.is_monitor_oe || $page.props.auth.user.is_super_admin" cols="12" sm="4">
                            <v-select
                                v-model="domain"
                                :items="domainsList"
                                :error-messages="errors.domain"
                                item-title="name"
                                item-value="id"
                                label="Domäne"
                                :disabled="fileInProgress"
                            ></v-select>
                        </v-col>
                        <v-col cols="12" sm="4">
                            <v-select
                                v-model="age"
                                :items="ages"
                                :error-messages="errors.age"
                                item-title="age_name"
                                item-value="age_number"
                                label="Altersgruppe"
                                :disabled="fileInProgress"
                            ></v-select>
                        </v-col>
                        <v-col cols="12" sm="4">
                            <v-text-field v-model="zipCode"
                                          type="number"
                                          :error-messages="errors.zipCode"
                                          label="Postleitzahl"
                                          :disabled="fileInProgress"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                </div>

                <div class="tw-ml-6">
                    <v-hover v-slot:default="{ isHovering, props }">
                        <v-btn
                            class="tw-mt-2"
                            v-bind="props"
                            :color="isHovering ? 'accent' : 'primary'"
                            @click="makeExport"
                            dark
                            :disabled="fileInProgress"
                        >Exportieren</v-btn>
                    </v-hover>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
