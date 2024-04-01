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
    roles: Array,
    users: Array,
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
const rawSurvayEnd = ref(prepareDate(props.surveyTimePeriod.survey_end_date));
const surveyStart = ref(new Date(props.surveyTimePeriod.survey_start_date).toString());
const survayEnd = ref(new Date(props.surveyTimePeriod.survey_end_date).toString());

// Computed
const modifiedItems = computed(() => {
    return props.surveyTimePeriod.map(item => {
        const modifiedItem = {...item};
        for (const key in modifiedItem) {
            if (modifiedItem[key] === null || modifiedItem[key] === undefined) {
                modifiedItem[key] = '-';
            }
        }
        return modifiedItem;
    });
});

// Watch
watch(surveyStart, (val) => {
    rawSurveyStart.value = prepareDate(val);
});

watch(survayEnd, (val) => {
    // let test = val.setUTCHours(12, 0, 0)
    console.log(val.setUTCHours(12, 0, 0))
    rawSurvayEnd.value = prepareDate(val);
});

// Methods
const manageForm = useForm({
    id: editedSurveyTimePeriod.value.id,
    year: editedSurveyTimePeriod.value.year,
    age: editedSurveyTimePeriod.value.age,
    survey_start_date: null,
    survey_end_date: null,
});

const manageSurveyTimePeriod = async () => {
    manageForm.processing = true;
    manageForm.survey_start_date = new Date(surveyStart.value)
    manageForm.survey_end_date = new Date(survayEnd.value)

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
    <Head :title="`Verwalte Einrichtung ${surveyTimePeriod.year}`"/>

    <AuthenticatedLayout :errors="errors">
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">Verwalte Einrichtung {{ surveyTimePeriod.name }}</h2>
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
                    <v-col cols="12" sm="6">
                        <v-text-field v-model="manageForm.year" :error-messages="errors.year"
                                      label="Jahr*" required></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6">
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
                                    ></v-text-field>
                                </template>
                                <v-date-picker @update:modelValue="isMenuOpen = false" v-model="surveyStart"></v-date-picker>
                            </v-menu>
                        </v-locale-provider>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="12" sm="6">
                        <v-select
                            v-model="manageForm.age"
                            :items="ages"
                            :error-messages="errors.age"
                            item-title="age_name"
                            item-value="age_number"
                            label="Altersgruppe (Jahre)*"
                        ></v-select>
                    </v-col>
                    <v-col cols="12" sm="6">
                        <v-locale-provider locale="de">
                            <v-menu v-model="isMenu2Open"
                                    :return-value.sync="survayEnd"
                                    :close-on-content-click="false">
                                <template v-slot:activator="{ props }">
                                    <v-text-field
                                        label="Erhebungsende*"
                                        class="tw-cursor-pointer"
                                        :model-value="rawSurvayEnd"
                                        prepend-icon="mdi-calendar"
                                        readonly
                                        v-bind="props"
                                    ></v-text-field>
                                </template>
                                <v-date-picker @update:modelValue="isMenu2Open = false" v-model="survayEnd"></v-date-picker>
                            </v-menu>
                        </v-locale-provider>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12" sm="6" align="right">
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
