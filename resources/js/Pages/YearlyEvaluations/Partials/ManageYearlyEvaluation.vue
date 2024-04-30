<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import { onMounted, computed, ref, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, useForm, usePage, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import WarningEvaluationTooltip from "@/Components/WarningEvaluationTooltip.vue";

const props = defineProps({
    yearlyEvaluation: Object,
    errors: Object,
    kitas: Array,
    surveyTimePeriods: Array,
});


/*
 * Inertia events handling
 */
// For page reloading on pagination or searching
Inertia.on('success', (event) => {
    let newProps = event.detail.page.props;
    let pageType = event.detail.page.component;

    if (pageType === 'YearlyEvaluations/Partials/ManageYearlyEvaluation' && newProps) {
        editedYearlyEvaluation.value = newProps.yearlyEvaluation;
    }
});


/*
 * Main data
 */
const currentUser = usePage().props.auth.user ?? {};  // Global info about user

const editedYearlyEvaluation = ref(props.yearlyEvaluation);
const errors = ref(props.errors || {});
const loading = ref(false);
const dialogIssues = ref(false);
const canSave = ref(false);
const allowSaveState = ref(false);
const canSaveMainForm = ref(true);

const manageForm = useForm({
    id: editedYearlyEvaluation.value.id,
    year: editedYearlyEvaluation.value.year,
    kita_id: editedYearlyEvaluation.value.kita_id,
    children_2_born_per_year: editedYearlyEvaluation.value.children_2_born_per_year,
    children_2_with_german_lang: editedYearlyEvaluation.value.children_2_with_german_lang,
    evaluations_with_daz_2_total_per_year: editedYearlyEvaluation.value.evaluations_with_daz_2_total_per_year,
    children_2_with_foreign_lang: editedYearlyEvaluation.value.children_2_with_foreign_lang,
    evaluations_without_daz_2_total_per_year: editedYearlyEvaluation.value.evaluations_without_daz_2_total_per_year,
    children_4_born_per_year: editedYearlyEvaluation.value.children_4_born_per_year,
    children_4_with_german_lang: editedYearlyEvaluation.value.children_4_with_german_lang,
    evaluations_with_daz_4_total_per_year: editedYearlyEvaluation.value.evaluations_with_daz_4_total_per_year,
    children_4_with_foreign_lang: editedYearlyEvaluation.value.children_4_with_foreign_lang,
    evaluations_without_daz_4_total_per_year: editedYearlyEvaluation.value.evaluations_without_daz_4_total_per_year,
});

// Computed
const modifiedItems = computed(() => {
    return props.yearlyEvaluation.map(item => {
        const modifiedItem = {...item};
        for (const key in modifiedItem) {
            if (modifiedItem[key] === null || modifiedItem[key] === undefined) {
                modifiedItem[key] = '-';
            }
        }
        return modifiedItem;
    });
});

const childsTotal2Label = computed(() => {
    return getChildsTotalLabel('2.5');
});

const childsTotal4Label = computed(() => {
    return getChildsTotalLabel('4.5');
});

// Watch
watch(dialogIssues, (val) => {
    canSave.value = true;
});

watch(
    () => manageForm.kita_id, // use a getter like this
    (val) => {
        let selectedKita = props.kitas.find(kita => {
            return val === parseInt(kita.id);
        });

        if (selectedKita) {
            manageForm.evaluations_with_daz_2_total_per_year = selectedKita.evaluations_with_daz2_total_per_year_count;
            manageForm.evaluations_with_daz_4_total_per_year = selectedKita.evaluations_with_daz4_total_per_year_count;
            manageForm.evaluations_without_daz_2_total_per_year = selectedKita.evaluations_without_daz2_total_per_year_count;
            manageForm.evaluations_without_daz_4_total_per_year = selectedKita.evaluations_without_daz4_total_per_year_count;
        }
    }
);

watch(() => manageForm.children_2_born_per_year, () => {
    validateChildrensAmount('2.5');
});

watch(() => manageForm.children_2_with_german_lang, () => {
    validateChildrensAmount('2.5');
});

watch(() => manageForm.children_2_with_foreign_lang, (newValue, oldValue) => {
    validateChildrensAmount('2.5');
});

watch(() => manageForm.children_4_born_per_year, (newValue, oldValue) => {
    validateChildrensAmount('4.5');
});

watch(() => manageForm.children_4_with_german_lang, (newValue, oldValue) => {
    validateChildrensAmount('4.5');
});

watch(() => manageForm.children_4_with_foreign_lang, (newValue, oldValue) => {
    validateChildrensAmount('4.5');
});

// Methods
const closeDialogIssues = () => {
    dialogIssues.value = false;
};

const allowSave = () => {
    canSave.value = true;
    closeDialogIssues()
};

const manageYearlyEvaluation = async () => {
    if( (checkYears('2_german') || checkYears('2_foreign') || checkYears('4_german') || checkYears('4_foreign')) && !canSave.value ) {
        dialogIssues.value = true
    } else {
        await updateYearlyEvaluation()
    }
};

const checkYears = (type) => {
    if(type === '2_german'){
        return parseInt(manageForm.evaluations_with_daz_2_total_per_year) !== parseInt(manageForm.children_2_with_german_lang)
    } else if(type === '2_foreign') {
        return parseInt(manageForm.evaluations_without_daz_2_total_per_year) !== parseInt(manageForm.children_2_with_foreign_lang)
    } else if (type === '4_german'){
        return parseInt(manageForm.evaluations_with_daz_4_total_per_year) !== parseInt(manageForm.children_4_with_german_lang)
    } else if (type === '4_foreign'){
        return parseInt(manageForm.evaluations_without_daz_4_total_per_year) !== parseInt(manageForm.children_4_with_foreign_lang)
    }
};

const updateYearlyEvaluation = async () => {
    manageForm.processing = true;

    let formOptions = {
        preserveState: true,
        onSuccess: (page) => {
            manageForm.reset();
            manageForm.clearErrors();
            errors.value = {};
        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            closeDialogIssues();
            manageForm.processing = false;
        },
    };

    manageForm.put(route('yearly_evaluations.update', {id: manageForm.id}), formOptions);
};

const getChildsTotalLabel = (age) => {
    if (age === '2.5' || age === '4.5') {
        let surveyTimePeriodForSelectedYear = props.surveyTimePeriods.find(obj => {
            return obj.year === parseInt(manageForm.year) && obj.age === '4.5';
        });

        if (surveyTimePeriodForSelectedYear) {
            let surveyStartDateObj = new Date(surveyTimePeriodForSelectedYear.survey_start_date);
            let surveyEndDateObj = new Date(surveyTimePeriodForSelectedYear.survey_end_date);

            let surveyStartDateStr = surveyStartDateObj.getFullYear() + '-' + ("0" + (surveyStartDateObj.getMonth() + 1)).slice(-2) + '-' + ("0" + surveyStartDateObj.getDate()).slice(-2);
            let surveyEndDateStr = surveyEndDateObj.getFullYear() + '-' + ("0" + (surveyEndDateObj.getMonth() + 1)).slice(-2) + '-' + ("0" + surveyEndDateObj.getDate()).slice(-2);

            return `Gesamtzahl der im Zeitraum ${surveyStartDateStr} - ${surveyEndDateStr} geborenen Kinder`;
        }
    }

    return "Gesamtzahl der Kinder";
};

const validateChildrensAmount = (age) => {
    if (age === '2.5' || age === '4.5') {
        let block2Valid = false;
        let block4Valid = false;

        if (
            (manageForm.children_2_with_german_lang !== null && manageForm.children_2_with_german_lang !== undefined) &&
            (manageForm.children_2_with_foreign_lang !== null && manageForm.children_2_with_foreign_lang !== undefined)
        ) {
            if (parseInt(manageForm.children_2_with_german_lang) + parseInt(manageForm.children_2_with_foreign_lang) !== parseInt(manageForm.children_2_born_per_year)) {
                errors.value.children_2_born_per_year = 'Die ausgewählte "Anzahl der im ausgewählten Jahr geborenen Kinder" ist ungültig.';

                block2Valid = false;
            } else {
                delete errors.value.children_2_born_per_year;

                block2Valid = true;
            }
        }

        if (
            (manageForm.children_4_with_german_lang !== null && manageForm.children_4_with_german_lang !== undefined) &&
            (manageForm.children_4_with_foreign_lang !== null && manageForm.children_4_with_foreign_lang !== undefined)
        ) {
            if (parseInt(manageForm.children_4_with_german_lang) + parseInt(manageForm.children_4_with_foreign_lang) !== parseInt(manageForm.children_4_born_per_year)) {
                errors.value.children_4_born_per_year = 'Die ausgewählte "Anzahl der im ausgewählten Jahr geborenen Kinder" ist ungültig.';

                block4Valid = false;
            } else {
                delete errors.value.children_4_born_per_year;

                block4Valid = true;
            }
        }

        canSaveMainForm.value = block2Valid && block4Valid;
    }
};
</script>

<template>
    <Head :title="`Verwalte Jährliche Rückmeldung ${yearlyEvaluation.year}`"/>

    <AuthenticatedLayout :errors="errors">
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">Jährliche Rückmeldung {{ yearlyEvaluation.year }}</h2>
        </template>

        <div class="tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8">
            <v-container>

                <v-row>
                    <v-col cols="12" sm="12">
                        <h3>Rückmeldung betrifft</h3>
                    </v-col>
                    <v-col cols="12" sm="4">
                        <v-text-field v-model="manageForm.year" :error-messages="errors.year"
                                      label="Jahr der Rückmeldung*" required></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="4">
                        <v-select
                            v-model="manageForm.kita_id"
                            :items="kitas"
                            :error-messages="errors.kita_id"
                            item-title="name"
                            item-value="id"
                            label="Kita*"
                        ></v-select>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="12" sm="12">
                        <h3>Angaben zur Rückmeldung</h3>
                    </v-col>
                    <v-col cols="12" sm="6">
                        <v-card class="tw-p-6">
                            <h5>Kinder bis 2,5 Jahre</h5>
                            <v-text-field v-model="manageForm.children_2_born_per_year" :error-messages="errors.children_2_born_per_year"
                                          :label="childsTotal2Label" type="number" required></v-text-field>

                            <v-row>
                                <v-col cols="12" sm="7">
                                    <v-text-field v-model="manageForm.children_2_with_german_lang" :error-messages="errors.children_2_with_german_lang"
                                                  label="Kinder mit deutscher Herkunftssprache*" type="number" required></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="5">
                                    <v-row>
                                        <WarningEvaluationTooltip v-if="checkYears('2_german')"/>
                                        <v-col cols="12" sm="10">
                                            <v-text-field v-model="manageForm.evaluations_with_daz_2_total_per_year" :error-messages="errors.evaluations_with_daz_2_total_per_year"
                                                          label="Bisher eingereichte Einschätzungen" type="number" disabled required>
                                            </v-text-field>
                                        </v-col>
                                    </v-row>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12" sm="7">
                                    <v-text-field v-model="manageForm.children_2_with_foreign_lang" :error-messages="errors.children_2_with_foreign_lang"
                                                  label="Kinder mit nicht deutscher Herkunftssprache*" type="number" required></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="5">
                                    <v-row>
                                        <WarningEvaluationTooltip v-if="checkYears('2_foreign')"/>
                                        <v-col cols="12" sm="10">
                                            <v-text-field v-model="manageForm.evaluations_without_daz_2_total_per_year" :error-messages="errors.evaluations_without_daz_2_total_per_year"
                                                          label="Bisher eingereichte Einschätzungen" type="number" disabled required></v-text-field>
                                        </v-col>
                                    </v-row>
                                </v-col>
                            </v-row>
                        </v-card>
                    </v-col>
                    <v-col cols="12" sm="6">
                        <v-card class="tw-p-6">
                            <h5>Kinder bis 4,5 Jahre</h5>
                            <v-text-field v-model="manageForm.children_4_born_per_year" :error-messages="errors.children_4_born_per_year"
                                          :label="childsTotal4Label" type="number" required></v-text-field>


                            <v-row>
                                <v-col cols="12" sm="7">
                                    <v-text-field v-model="manageForm.children_4_with_german_lang" :error-messages="errors.children_4_with_german_lang"
                                                  label="Kinder mit deutscher Herkunftssprache*" type="number" required></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="5">
                                    <v-row>
                                        <WarningEvaluationTooltip v-if="checkYears('4_german')"/>
                                        <v-col cols="12" sm="10">
                                            <v-text-field v-model="manageForm.evaluations_with_daz_4_total_per_year" :error-messages="errors.evaluations_with_daz_4_total_per_year"
                                                          label="Bisher eingereichte Einschätzungen" type="number" disabled required></v-text-field>
                                        </v-col>
                                    </v-row>
                                </v-col>
                            </v-row>

                            <v-row>
                                <v-col cols="12" sm="7">
                                    <v-text-field v-model="manageForm.children_4_with_foreign_lang" :error-messages="errors.children_4_with_foreign_lang"
                                                  label="Kinder mit nicht deutscher Herkunftssprache*" type="number" required></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="5">
                                    <v-row>
                                        <WarningEvaluationTooltip v-if="checkYears('4_foreign')"/>
                                        <v-col cols="12" sm="10">
                                            <v-text-field v-model="manageForm.evaluations_without_daz_4_total_per_year" :error-messages="errors.evaluations_without_daz_4_total_per_year"
                                                          label="Bisher eingereichte Einschätzungen" type="number" disabled required></v-text-field>
                                        </v-col>
                                    </v-row>
                                </v-col>
                            </v-row>
                        </v-card>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12" sm="12" align="right">
                        <v-hover v-slot:default="{ isHovering, props }">
                            <Link :href="route('yearly_evaluations.index')">
                                <v-btn class="mr-2" variant="text" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Zurück</v-btn>
                            </Link>
                        </v-hover>
                        <v-hover v-slot:default="{ isHovering, props }">
                            <v-btn-primary @click="manageYearlyEvaluation" v-bind="props"
                                           :color="isHovering ? 'accent' : 'primary'"
                                           :disabled="!canSaveMainForm"
                            >
                                Speichern
                            </v-btn-primary>
                        </v-hover>
                    </v-col>

                </v-row>
            </v-container>

            <v-dialog v-model="dialogIssues" width="80vw">
                <v-card height="50vh">
                    <v-card-text>
                        <v-container>
                            <v-row>
                                <v-col cols="12">
                                    <h4>Wir haben eine Abweichung zwischen der Anzahl der von Ihnen gemeldeten Kinder und der Anzahl über die BeoKiz-Ampel erfassten Kindern festgestellt:</h4>


                                    <div class="tw-flex tw-items-center tw-mt-2">
                                        <v-icon v-bind="props" icon="mdi-alert" color="orange"></v-icon>
                                        <ul style="list-style-type: disc; margin-left: 25px;">
                                            <li v-if="checkYears('2_german')">
                                                Die Anzahl der Kinder im Alter bis 2,5 Jahre welche Deutsch als Fremdsprache haben Sie mit
                                                {{ manageForm.children_2_with_german_lang }} angegeben, die Anzahl der  über die BeoKiz-Ampel erfassten Kindern beträgt
                                                {{manageForm.evaluations_with_daz_2_total_per_year}}
                                            </li>
                                            <li v-if="checkYears('2_foreign')">
                                                Die Anzahl der Kinder im Alter bis 2,5 Jahre welche Deutsch als Muttersprache haben Sie mit
                                                {{ manageForm.children_2_with_foreign_lang }} angegeben, die Anzahl der  über die BeoKiz-Ampel erfassten Kindern beträgt
                                                {{ manageForm.evaluations_without_daz_2_total_per_year }}
                                            </li>
                                            <li v-if="checkYears('4_german')">
                                                Die Anzahl der Kinder im Alter bis 4,5 Jahre welche Deutsch als Fremdsprache haben Sie mit
                                                {{ manageForm.children_4_with_german_lang }} angegeben, die Anzahl der  über die BeoKiz-Ampel erfassten Kindern beträgt
                                                {{ manageForm.evaluations_with_daz_2_total_per_year }}
                                            </li>
                                            <li v-if="checkYears('4_foreign')">
                                                Die Anzahl der Kinder im Alter bis 4,5 Jahre welche Deutsch als Muttersprache haben Sie mit
                                                {{ manageForm.children_4_with_foreign_lang }} angegeben, die Anzahl der  über die BeoKiz-Ampel erfassten Kindern beträgt
                                                {{ manageForm.evaluations_without_daz_4_total_per_year }}
                                            </li>
                                        </ul>
                                    </div>

                                    <v-checkbox
                                        v-model="allowSaveState"
                                        label="Ich habe verstanden, dass es Unstimmigkeiten bei dem Abgelich der gemelden Zahlen gibt, Statusmeldung dennoch absenden."
                                    ></v-checkbox>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-card-text>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-hover v-slot:default="{ isHovering, props }">
                            <v-btn @click="closeDialogIssues" :color="isHovering ? 'accent' : 'primary'">Abbrechen</v-btn>
                        </v-hover>
                        <v-hover v-slot:default="{ isHovering, props }">
                            <v-btn-primary :disabled="!allowSaveState" @click="manageYearlyEvaluation" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Speichern</v-btn-primary>
                        </v-hover>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </div>
    </AuthenticatedLayout>
</template>
