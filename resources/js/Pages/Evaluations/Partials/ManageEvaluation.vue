<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import { onMounted, onBeforeMount, ref, computed, watch, isRef } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { v4 as uuidv4 } from 'uuid';
import { ages, prepareInitialRatingData } from '@/Composables/common';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import EvaluationDomainsList from '@/Components/EvaluationDomainsList.vue';

const props = defineProps({
    evaluation: Object,
    kitas: Array,
    domains: Array,
    errors: Object,
});

/*
 * Inertia events handling
 */
// For page reloading on pagination or searching
Inertia.on('success', (event) => {
    let newProps = event.detail.page.props;
    let pageType = event.detail.page.component;

    if (pageType === 'Evaluations/Partials/ManageEvaluation' && newProps) {
        //
    }
});


/*
 * Main data
 */
const currentUser = usePage().props.auth.user ?? {};  // Global info about user

const generatedUUID = ref(null);
const errors = ref(props.errors || {});
const loader = ref(false);

const preparedDomains = ref([]);

const evaluationResultState = ref(false);
const evaluationResultData = ref(null);


const periods = ref([
    { name: '1 - 12 Monate', id: 'upToOneYear' },
    { name: '12 - 24 Monate', id: 'upToTwoYears' },
    { name: '24 - 36 Monate', id: 'upToThreeYears' },
])

// Computed
const isEditMode = computed(() => {
    return !!props.evaluation;
});

// onMounted & onBeforeMount
onBeforeMount(() => {
    // Prepare evaluation data
    if (!isEditMode.value) {
        setInitialRatingData();
    }
});

onMounted(() => {
    // Prepare "child duration in kita" options
    prepareChildDurationInKita(props.evaluation?.age);

    // Prepare evaluation data
    if (!isEditMode.value) {
        generateEvaluationUuid();
    }
});

const generateEvaluationUuid = () => {
    generatedUUID.value = uuidv4();
    manageForm.uuid = generatedUUID.value;
};

const prepareChildDurationInKita = (age) => {
    if (age === 4.5){
        periods.value.push({ name: 'Länger als 36 Monate', id: 'moreThanThreeYears' })
    } else {
        const index = periods.value.findIndex(obj => obj.id === 'moreThanThreeYears');
        if (index !== -1) {
            periods.value.splice(index, 1);
        }
    }
};

const setInitialRatingData = () => {
    manageForm.ratings = prepareInitialRatingData(props.domains);
};

const close = () => {
    errors.value = {};

    evaluationResultState.value = false;
};

const clear = () => {
    manageForm.reset();
    manageForm.clearErrors();
    errors.value = {};

    manageForm.ratings = prepareInitialRatingData(props.domains);

    if (!isEditMode.value) {
        generateEvaluationUuid();
    }
};

const manageForm = useForm({
    uuid: isEditMode.value ? props.evaluation.uuid : null,
    user_id: isEditMode.value ? props.evaluation.user_id : currentUser.id,
    kita_id: isEditMode.value ? props.evaluation.kita_id : null,
    age: isEditMode.value ? props.evaluation.age : null,
    child_duration_in_kita: isEditMode.value ? props.evaluation.child_duration_in_kita : null,
    is_daz: isEditMode.value ? props.evaluation.is_daz : false,
    integration_status: isEditMode.value ? props.evaluation.integration_status : false,
    speech_therapy_status: isEditMode.value ? props.evaluation.speech_therapy_status : false,
    ratings: isEditMode.value ? props.evaluation.data : [],
});

watch(
    () => manageForm.age,
    (age) => {
        manageForm.ratings = prepareInitialRatingData(props.domains);

        // Prepare "child duration in kita" options
        prepareChildDurationInKita(age);

        if (age !== 4.5){
            manageForm.child_duration_in_kita = null;
        }
    }
);

const updateRatingData = (newRatings) => {
    manageForm.ratings = newRatings;
};

const updateDomainsData = (evaluationDomains) => {
    preparedDomains.value = isRef(evaluationDomains)
        ? evaluationDomains.value
        : evaluationDomains;
};

const manageEvaluation = async () => {
    manageForm.processing = true;
    loader.value = true;

    let presentedMilestones = [];

    preparedDomains.value.map(domain => {
        domain.subdomains.map(subdomain => {
            subdomain.milestones.map(milestone => {
                presentedMilestones.push(parseInt(milestone.id));

                return milestone;
            });

            return subdomain;
        });

        return domain;
    });

    manageForm.ratings = manageForm.ratings.map(domain => {
        domain.milestones.map(milestone => {
            if (!presentedMilestones.includes(parseInt(milestone.id))) {
                milestone.value = 0;
            }

            return milestone;
        });

        return domain;
    });

    let routeOptions = {
        onSuccess: (page) => {
            // Clear errors & reset form data
            // manageForm.reset();
            manageForm.clearErrors();
            // generateEvaluationUuid();
            errors.value = {};

            // Open Evaluation pop-up & set Evaluation data
            // setInitialRatingData();
            evaluationResultState.value = true;
            evaluationResultData.value = page.props.data;
        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            manageForm.processing = false;
            loader.value = false;
        },
    };

    if (isEditMode.value) {
        manageForm.put(route('evaluations.update', { evaluation: props.evaluation.id }), routeOptions);
    } else {
        manageForm.post(route('evaluations.store'), routeOptions);
    }
};

const saveEvaluation = async () => {
    manageForm.processing = true;
    loader.value = true;

    manageForm.post(route('evaluations.save'), {
        preserveState: false,
        onSuccess: (page) => {
            // Clear errors & reset form data
            manageForm.clearErrors();
            errors.value = {};
        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            manageForm.processing = false;
            loader.value = false;
        },
    });
};

const unfinishedForm = useForm({
    //
});

const unfinishedEvaluation = async (id) => {
    unfinishedForm.processing = true;

    unfinishedForm.post(route('evaluations.unfinished', { id: id }), {
        preserveState: false,
        onSuccess: (page) => {
            // Clear errors & reset form data
            unfinishedForm.clearErrors();
            errors.value = {};
            close();
        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            unfinishedForm.processing = false;
            loader.value = false;
        },
    });
};
</script>

<template>
    <Head title="Auswertung erstellen"/>

    <AuthenticatedLayout :errors="errors">
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">Auswertung erstellen</h2>
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
                        <v-text-field v-model="manageForm.uuid" :error-messages="errors.uuid"
                                      readonly
                                      label="Bezeichner der Einschatzung" required></v-text-field>
                    </v-col>

                    <v-col cols="12" sm="2">
                        <v-select
                            v-model="manageForm.age"
                            :items="ages"
                            :error-messages="errors.age"
                            item-title="age_name"
                            item-value="age_number"
                            label="Altersgruppe"
                        ></v-select>
                    </v-col>

                    <v-col cols="12" sm="2">
                        <v-select
                            v-model="manageForm.kita_id"
                            :items="kitas"
                            :error-messages="errors.kita_id"
                            item-title="name"
                            item-value="id"
                            label="Kita"
                        ></v-select>
                    </v-col>

                    <v-col cols="12" sm="2">
                        <v-select
                            v-model="manageForm.child_duration_in_kita"
                            :items="periods"
                            :error-messages="errors.child_duration_in_kita"
                            item-title="name"
                            item-value="id"
                            label="Zeitraum in der Kita"
                        ></v-select>
                    </v-col>

                    <v-col cols="12" sm="3">
                        <v-checkbox
                            v-model="manageForm.is_daz"
                            label="Deutsch ist nicht Erstsprache des Kindes"
                        ></v-checkbox>
                        <v-checkbox
                            v-model="manageForm.integration_status"
                            label="Integrationsstatus"
                        ></v-checkbox>
                        <v-checkbox
                            v-model="manageForm.speech_therapy_status"
                            label="in logopädische Behandlung"
                        ></v-checkbox>
                    </v-col>
                </v-row>

                <v-row class="manage-evaluation-domains">
                    <EvaluationDomainsList
                        v-if="!!manageForm.age"
                        :ratings="manageForm.ratings"
                        :age="manageForm.age"
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
                        <v-hover v-slot:default="{ isHovering, props }">
                            <v-btn class="mr-2" @click="saveEvaluation" variant="text" v-bind="props" :color="isHovering ? 'accent' : 'primary'">
                                Speichern
                            </v-btn>
                        </v-hover>
                        <v-hover v-if="!!manageForm.age && preparedDomains.length > 0" v-slot:default="{ isHovering, props }">
                            <v-btn-primary @click="manageEvaluation" v-bind="props" :color="isHovering ? 'accent' : 'primary'">
                                Prüfen und Einreichen
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
                            <v-col cols="12">
                                <v-hover v-slot:default="{ isHovering, props }">
                                    <div class="tw-text-right">
                                        <a :href="route('evaluations.pdf', { id: evaluationResultData.item.id })" @click="close" title="Fenster schließen">
                                            <v-icon v-bind="props" size="small" class="tw-me-2" @click="">mdi-close</v-icon>
                                        </a>
                                    </div>
                                </v-hover>
                            </v-col>
                        </v-row>

                        <v-row class="result-evaluation-domains">
                            <v-col cols="8" offset="2">
                                <div class="tw-text-center">
                                    <h1 class="tw-uppercase text-primary tw-font-black tw-text-xl tw-mb-8">
                                        Screening wurde eingereicht
                                    </h1>

                                    <p class="tw-mb-8">
                                        Folgendes Screening wurde eingereicht und kann nur bis 15 Minuten nach Einreichung bearbeitet werden. Danach verschwindet es aus Ihrer Übersicht. Sollten Sie es zurückziehen oder bearbeiten wollen, klicke Sie auf 'Abgabe zurückziehen. Nachfolgend erhalten Sie eine Übersicht des eingereichten Screenings, welches Sie über den Download-Button als PDF herunterladen können.
                                    </p>

                                    <v-hover v-slot:default="{ isHovering, props }">
                                        <v-btn :href="route('evaluations.pdf', { id: evaluationResultData.item.id })" class="tw-px-2 tw-py-3 tw-mb-4 tw-mr-4 tw-normal-case" :color="isHovering ? 'primary' : 'accent'">
                                            Screening als PDF downloaden
                                        </v-btn>
                                    </v-hover>

                                    <v-hover v-slot:default="{ isHovering, props }">
                                        <v-btn @click="unfinishedEvaluation(evaluationResultData.item.id)" class="tw-px-2 tw-py-3 tw-mb-4 tw-normal-case" :color="isHovering ? 'accent' : 'primary'">
                                            Abgabe zurückziehen
                                        </v-btn>
                                    </v-hover>
                                </div>
                            </v-col>

                            <v-col cols="12">
                                <p>
                                    <span class="tw-font-black">Bezeichner des Screenings</span>:
                                    {{`${evaluationResultData.item.kita.formatted_name}_${evaluationResultData.item.uuid}`}}
                                </p>
                            </v-col>

                            <v-col cols="12">
                                <EvaluationDomainsList
                                    :ratings="evaluationResultData.item.data"
                                    :age="manageForm.age"
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
