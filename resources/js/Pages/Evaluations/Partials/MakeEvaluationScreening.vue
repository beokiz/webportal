<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import {isRef, onBeforeMount, ref, watch} from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ages, prepareInitialRatingData } from '@/Composables/common';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import EvaluationDomainsList from '@/Components/EvaluationDomainsList.vue';

const props = defineProps({
    domains: Array,
    dazDependent: {
        type: Boolean,
        default: false,
    },
    errors: Object,
});

/*
 * Inertia events handling
 */
// For page reloading on pagination or searching
Inertia.on('success', (event) => {
    let newProps = event.detail.page.props;
    let pageType = event.detail.page.component;

    if (pageType === 'Evaluations/Partials/MakeEvaluationScreening' && newProps) {
        //
    }
});


/*
 * Main data
 */
const currentUser = usePage().props.auth.user ?? {};  // Global info about user

const errors = ref(props.errors || {});
const loader = ref(false);

const preparedDomains = ref([]);

const evaluationResultState = ref(false);
const evaluationResultData = ref(null);
const evaluationResultDomainName = ref(null);

// onMounted
onBeforeMount(() => {
    // Prepare evaluation data
    setInitialRatingData();
});

const updateRatingData = (newRatings) => {
    console.log('qqqqq')
    screeningForm.ratings = newRatings;
};

const updateDomainsData = (evaluationDomains) => {
    console.log('ascas')
    preparedDomains.value = isRef(evaluationDomains)
        ? evaluationDomains.value
        : evaluationDomains;
};

const setInitialRatingData = () => {
    screeningForm.ratings = prepareInitialRatingData(props.domains);
};

const close = () => {
    errors.value = {};

    evaluationResultState.value = false;
};

const clear = () => {
    screeningForm.reset();
    screeningForm.clearErrors();
    errors.value = {};

    screeningForm.ratings = prepareInitialRatingData(props.domains);
};

const screeningForm = useForm({
    age: null,
    is_daz: false,
    ratings: [],
});

watch(
    () => screeningForm.age, // use a getter like this
    (age) => {
        screeningForm.ratings = prepareInitialRatingData(props.domains);
    }
);

const screeningEvaluation = async () => {
    screeningForm.processing = true;
    loader.value = true;

    screeningForm.post(route('screening.make'), {
        onSuccess: (page) => {
            // Clear errors & reset form data
            screeningForm.clearErrors();
            errors.value = {};

            // Open Evaluation pop-up & set Evaluation data
            evaluationResultState.value = true;
            evaluationResultData.value = page.props.data.evaluation;
            evaluationResultDomainName.value = page.props.data.domain.name;
        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            screeningForm.processing = false;
            loader.value = false;
        },
    });
};
</script>

<template>
    <Head title="Screening-prüfung"/>

    <AuthenticatedLayout :errors="errors">
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">Screening-prüfung</h2>
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
                    <v-col cols="12" sm="3">
                        <v-select
                            v-model="screeningForm.age"
                            :items="ages"
                            :error-messages="errors.age"
                            item-title="age_name"
                            item-value="age_number"
                            label="Altersgruppe"
                        ></v-select>
                    </v-col>

                    <v-col v-if="dazDependent" cols="12" sm="3">
                        <v-checkbox
                            v-model="screeningForm.is_daz"
                            label="Deutsch ist nicht Muttersprache"
                        ></v-checkbox>
                    </v-col>
                </v-row>

                <v-row class="manage-evaluation-domains">
                    <EvaluationDomainsList
                        v-if="!!screeningForm.age"
                        :ratings="screeningForm.ratings"
                        :age="screeningForm.age"
                        :domains="domains"
                        :errors="errors"
                        @updateRatingData="updateRatingData"
                        @updateDomainsData="updateDomainsData"/>
                </v-row>
            </v-container>

            <v-container>
                <template v-if="loader">
                    <div class="tw-flex justify-center items-center tw-bg-white">
                        <v-progress-circular indeterminate :size="40"></v-progress-circular>
                    </div>
                </template>

                <v-row v-else>
                    <v-col cols="12" sm="6">
                        <v-hover v-slot:default="{ isHovering, props }">
                            <v-btn @click="clear" v-bind="props" :color="isHovering ? 'primary' : 'accent'">
                                Zurücksetzen
                            </v-btn>
                        </v-hover>
                    </v-col>

                    <v-col cols="12" sm="6" align="right">
                        <v-hover v-if="!!screeningForm.age && preparedDomains.length > 0" v-slot:default="{ isHovering, props }">
                            <v-btn-primary @click="screeningEvaluation" v-bind="props" :color="isHovering ? 'accent' : 'primary'">
                                Ampel-Bewertung
                            </v-btn-primary>
                        </v-hover>
                    </v-col>
                </v-row>
            </v-container>
        </div>

        <v-dialog v-model="evaluationResultState" width="95vw">
            <v-card height="95vh">
                <v-card-text>
                    <v-container>
                        <v-row class="result-evaluation-domains">
                            <v-col cols="8" offset="2">
                                <div class="tw-text-center">
                                    <h1 class="tw-uppercase text-primary tw-font-black tw-text-xl tw-mb-8">
                                        Ampelergebnis im Bereich {{evaluationResultDomainName}}
                                    </h1>
                                </div>
                            </v-col>

                            <v-col cols="12">
                                <EvaluationDomainsList
                                    :ratings="evaluationResultData"
                                    :age="screeningForm.age"
                                    :domains="domains"
                                    :disabled="true"/>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-hover v-slot:default="{ isHovering, props }">
                        <v-btn-primary @click="close" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Abbrechen</v-btn-primary>
                    </v-hover>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </AuthenticatedLayout>
</template>
