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
    surveyTimePeriod: Object,
    errors: Object,
});


/*
 * Inertia events handling
 */
// For page reloading on pagination or searching
Inertia.on('success', (event) => {
    let newProps = event.detail.page.props;
    let pageType = event.detail.page.component;

    if (pageType === 'SurveyTimePeriods/Partials/ManageSurveyTimePeriod' && newProps) {
        editedSurveyTimePeriod.value = newProps.surveyTimePeriod;
    }
});


/*
 * Main data
 */
const currentUser = usePage().props.auth.user ?? {};  // Global info about user

const editedSurveyTimePeriod = ref(props.surveyTimePeriod);
const errors = ref(props.errors || {});
const loading = ref(false);

const isMenuOpen = ref(false);
const isMenu2Open = ref(false);
const rawSurveyStart = ref(prepareDate(props.surveyTimePeriod.survey_start_date));
const rawSurveyEnd = ref(prepareDate(props.surveyTimePeriod.survey_end_date));
const surveyStart = ref(new Date(props.surveyTimePeriod.survey_start_date).toString());
const surveyEnd = ref(new Date(props.surveyTimePeriod.survey_end_date).toString());

// Watch
watch(surveyStart, (val) => {
    rawSurveyStart.value = prepareDate(val);
});

watch(surveyEnd, (val) => {
    rawSurveyEnd.value = prepareDate(val);
});

// Methods
const manageForm = useForm({
    id: editedSurveyTimePeriod.value.id,
    year: editedSurveyTimePeriod.value.year,
    survey_start_date: null,
    survey_end_date: null,
});

const manageSurveyTimePeriod = async () => {
    manageForm.processing = true;

    manageForm.survey_start_date = new Date(surveyStart.value).toLocaleString();
    manageForm.survey_end_date = new Date(surveyEnd.value).toLocaleString();

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

    manageForm.put(route('survey_time_periods.update', {id: manageForm.id}), formOptions);
};
</script>

<template>
    <Head :title="`Verwalte Rückmeldezeitraum ${surveyTimePeriod.year}`"/>

    <AuthenticatedLayout :errors="errors">
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">Verwalte Rückmeldezeitraum {{ surveyTimePeriod.year }}</h2>
        </template>

        <div class="tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8">
            <v-container>
                <v-row>
                    <v-col cols="12">
                        <h3>Eigenschaften</h3>
                    </v-col>
                </v-row>
            </v-container>

            <v-container>
                <v-row>
                    <v-col cols="12" sm="4">
                        <v-text-field v-model="manageForm.year" :error-messages="errors.year"
                                      label="Jahr*" required></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="4">
                        <v-locale-provider locale="de">
                            <v-menu v-model="isMenuOpen"
                                    :return-value.sync="surveyStart"
                                    :close-on-content-click="false">
                                <template v-slot:activator="{ props }">
                                    <v-text-field
                                        label="Erhebungsbeginn*"
                                        class="tw-cursor-pointer"
                                        :model-value="rawSurveyStart"
                                        prepend-icon="mdi-calendar"
                                        readonly
                                        v-bind="props"
                                        :error-messages="errors.survey_start_date"
                                    ></v-text-field>
                                </template>
                                <v-date-picker @update:modelValue="isMenuOpen = false" v-model="surveyStart"></v-date-picker>
                            </v-menu>
                        </v-locale-provider>
                    </v-col>

                    <v-col cols="12" sm="4">
                        <v-locale-provider locale="de">
                            <v-menu v-model="isMenu2Open"
                                    :return-value.sync="surveyEnd"
                                    :close-on-content-click="false">
                                <template v-slot:activator="{ props }">
                                    <v-text-field
                                        label="Erhebungsende*"
                                        class="tw-cursor-pointer"
                                        :model-value="rawSurveyEnd"
                                        prepend-icon="mdi-calendar"
                                        readonly
                                        v-bind="props"
                                        :error-messages="errors.survey_end_date"
                                    ></v-text-field>
                                </template>
                              <v-date-picker @update:modelValue="isMenu2Open = false" v-model="surveyEnd"></v-date-picker>
                            </v-menu>
                        </v-locale-provider>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12" sm="12" align="right">
                        <v-hover v-slot:default="{ isHovering, props }">
                            <Link :href="route('survey_time_periods.index')">
                                <v-btn class="mr-2" variant="text" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Zurück</v-btn>
                            </Link>
                        </v-hover>
                        <v-hover v-slot:default="{ isHovering, props }">
                            <v-btn-primary @click="manageSurveyTimePeriod" v-bind="props"
                                           :color="isHovering ? 'accent' : 'primary'">Speichern
                            </v-btn-primary>
                        </v-hover>
                    </v-col>
                </v-row>
            </v-container>
        </div>
    </AuthenticatedLayout>
</template>
