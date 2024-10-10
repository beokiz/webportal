<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import { computed, ref, watch, nextTick } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { formatDate, prepareDate } from '@/Composables/common.js';
import InfoMessage from "@/Components/InfoMessage.vue";

const props = defineProps({
    availableTrainings: Array,
    operators: Array,
    errors: Object,
});

const errors = ref(props.errors || {});


/*
 * Registration
 */
const registrationForm = useForm({
    kita: {
        number: null,
        name: null,
        district: null,
        street: null,
        house_number: null,
        zip_code: null,
        city: null,
        operator_id: null,
        other_operator: null,
        additional_info: null,
        participant_count: null,
        type: null,
        trainings: [],
        training_id: null,
    },
    user: {
        first_name: null,
        last_name: null,
        email: null,
        phone_number: null,
    },
    privacy_policy: false,
});

const submitRegistration = async () => {
    registrationForm.processing = true;

    if (isNewKitaWasLarge.value) {
        registrationForm.kita.training_id = null;
        registrationForm.kita.trainings = trainingSuggestions.value;
    } else {
        registrationForm.kita.trainings = [];
    }

    registrationForm.post(route('auth.register_submit'), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (page) => {
            showSuccessMessage.value = true;
        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            registrationForm.processing = false;
        },
    });
};

/*
 * Common
 */
const minTrainingDate = ref('2025-01-01');
const maxTrainingDate = ref('2027-07-31');
const initialDisplayDate = ref(new Date(minTrainingDate.value));
const maxDisplayDate = ref(new Date(maxTrainingDate.value));

const showTrainingDisclaimer = ref(false);

const smallKitasOperators = computed(() => {
    return props.operators.filter(operator => operator?.self_training === false || operator?.self_training === undefined);
});

const largeKitasOperators = computed(() => {
    return props.operators.filter(operator => operator?.self_training === true || operator?.self_training === undefined);
});

const selfTrainingOperators = computed(() => {
    return props.operators.filter(operator => operator?.self_training === true || operator?.self_training === undefined);
});

const availableKitasByParticipantCount = computed(() => {
    return props.availableTrainings.filter(kita => {
        return parseInt(kita?.available_participant_count) >= parseInt(registrationForm.kita.participant_count);
    });
});

const isNewKitaWasLarge = computed(() => {
    return registrationForm.kita.type === 'large';
});

const hasNoSuggestionsErrors = computed(() => {
    return trainingSuggestions.value.every(suggestion =>
        !suggestion.first_day_error &&
        !suggestion.second_day_error &&
        suggestion.first_date !== null &&
        suggestion.second_date !== null
    );
});

const hasSelectedTraining = computed(() => {
    return !!registrationForm.kita.training_id;
});

const showTrainingScreen = computed(() => {
    return isNewKitaWasLarge.value
        ? registrationForm?.kita?.participant_count
        : registrationForm?.kita?.participant_count && showTrainingDisclaimer.value === false;
});

const showKitaScreen = computed(() => {
    if (!registrationForm?.kita?.operator_id) {
        if (isNewKitaWasLarge.value) {
            // If Kita is large, check for offers and errors
            return trainingSuggestions.value.length > 0 && hasNoSuggestionsErrors.value;
        } else {
            // If Kita is small, check that at least one option in v-radio-group is selected
            return hasSelectedTraining.value;
        }
    } else {
       return true;
    }
});

const showSuccessMessage = ref(false);

watch(() => registrationForm.kita.participant_count, (val) => {
    if (val && val <= 10) {
        showTrainingDisclaimer.value = availableKitasByParticipantCount.value.length <= 0;

        if (val <= 0) {
            registrationForm.kita.participant_count = 1;
        }

        clearRegistrationForm('small');
    } else {
        showTrainingDisclaimer.value = false;

        clearRegistrationForm('large');
    }
});

watch(() => registrationForm.kita.operator_id, (val) => {
    registrationForm.kita.other_operator = null;
    registrationForm.kita.training_id = null;

    trainingSuggestions.value = [{ first_date: null, second_date: null, isFirstDateFieldOpened: false, isSecondDateFieldOpened: false, first_day_error: '', second_day_error: '' }];
});

watch(() => registrationForm.kita.other_operator, (val) => {
    if (!val) {
        trainingSuggestions.value = [{ first_date: null, second_date: null, isFirstDateFieldOpened: false, isSecondDateFieldOpened: false, first_day_error: '', second_day_error: '' }];
    }
});


const clearRegistrationForm = (type) => {
    registrationForm.kita.type = type;
    registrationForm.kita.operator_id = null;
    registrationForm.kita.other_operator = null;
    registrationForm.kita.training_id = null;
    registrationForm.kita.trainings = [];
};

/*
 * Training suggestions (for large kitas)
 */
const maxTrainings = 3;

const trainingSuggestions = ref([{ first_date: null, second_date: null, isFirstDateFieldOpened: false, isSecondDateFieldOpened: false, first_day_error: '', second_day_error: '' }]);

const addTrainingSuggestion = () => {
    if (trainingSuggestions.value.length < maxTrainings) {
        trainingSuggestions.value.push({ first_date: null, second_date: null, isFirstDateFieldOpened: false, isSecondDateFieldOpened: false, first_day_error: '', second_day_error: '' });
    }
};

const removeTrainingSuggestion = (index) => {
    if (trainingSuggestions.value.length > 1) {
        trainingSuggestions.value.splice(index, 1);
    }
};

const previousSuggestions = ref([]);  // Store the previous state of the dates

watch(trainingSuggestions, (newVal) => {
    newVal.forEach((suggestion, index) => {
        const oldSuggestion = previousSuggestions.value[index] || {};

        // Check if the dates have changed
        if (suggestion.first_date !== oldSuggestion.first_date || suggestion.second_date !== oldSuggestion.second_date) {
            validateTrainingSuggestionDates(index);  // Validate dates when changed
        }
    });

    // Store the previous state of the suggestions
    previousSuggestions.value = newVal.map(suggestion => ({
        first_date: suggestion.first_date ? formatDate(suggestion.first_date, 'de-DE') : null,
        second_date: suggestion.second_date ? formatDate(suggestion.second_date, 'de-DE') : null,
    }));
}, { deep: true });


const validateTrainingSuggestionDates = (index) => {
    const suggestion = trainingSuggestions.value[index];
    const today = new Date();

    today.setHours(0, 0, 0, 0);

    let firstDayError = '';
    let secondDayError = '';

    // Validation: Ensure the first date is not in the past
    if (suggestion.first_date) {
        const firstDate = new Date(suggestion.first_date);

        if (suggestion.first_date && suggestion.first_date instanceof Date && suggestion.first_date.getHours() !== 12) {
            trainingSuggestions.value[index].first_date = new Date(firstDate.setHours(12, 0, 0, 0));
        }

        if (firstDate < today) {
            firstDayError = 'Erster Schulungstag darf nicht in der Vergangenheit liegen.';  // First training day cannot be in the past
        }
    }

    // Validation: Ensure the second date is not in the past
    if (suggestion.second_date) {
        const secondDate = new Date(suggestion.second_date);

        if (suggestion.second_date && suggestion.second_date instanceof Date && suggestion.second_date.getHours() !== 12) {
            trainingSuggestions.value[index].second_date = new Date(secondDate.setHours(12, 0, 0, 0));
        }

        if (secondDate < today) {
            secondDayError = 'Zweiter Schulungstag darf nicht in der Vergangenheit liegen.';  // Second training day cannot be in the past
        }
    }

    // Validation: Ensure the second date is not less than or equal to the first date
    if (suggestion.first_date && suggestion.second_date) {
        const firstDate = new Date(suggestion.first_date);
        const secondDate = new Date(suggestion.second_date);

        if (secondDate <= firstDate) {
            firstDayError = 'Der zweite Schulungstag darf nicht am gleichen Tag oder vor dem ersten Schulungstag liegen.';  // The second training day cannot be on the same day or before the first
            secondDayError = 'Der zweite Schulungstag darf nicht am gleichen Tag oder vor dem ersten Schulungstag liegen.';  // The second training day cannot be on the same day or before the first
        } else {
            const differenceInDays = Math.abs((secondDate - firstDate) / (1000 * 60 * 60 * 24));

            // Validation: Ensure the two dates are within 7 days of each other
            if (differenceInDays > 7) {
                firstDayError = 'Der erste und der zweite Schulungstag müssen innerhalb von 7 Tagen liegen.';  // The first and second training days must be within 7 days of each other
                secondDayError = 'Der zweite und der erste Schulungstag müssen innerhalb von 7 Tagen liegen.';  // The second and first training days must be within 7 days of each other
            }
        }
    }

    // Update error messages after validation is complete
    nextTick(() => {
        suggestion.first_day_error = firstDayError;
        suggestion.second_day_error = secondDayError;
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Registrieren"/>

        <v-container>
            <v-row>
                <v-col cols="12" class="tw-flex tw-items-center tw-justify-center tw-mb-8">
                    <Link href="/">
                        <ApplicationLogo class="tw-h-20 tw-fill-current tw-text-gray-500" />
                    </Link>
                </v-col>
            </v-row>
        </v-container>

        <v-fade-transition>
            <template v-if="!showSuccessMessage">
                <v-container>
                    <v-row>
                        <v-col cols="12" class="tw-mb-8">
                            <h2 class="tw-font-semibold tw-text-base tw-text-gray-800 tw-leading-tight tw-mb-4">
                                Anmeldung zur BeoKiz-Schulung
                            </h2>

                            <p class="tw-mb-4">
                                Sehr geehrte BeoKiz-Interessenten,
                            </p>

                            <p class="tw-mb-4">
                                die BeoKiz-Schulungen finden als Team-Schulung statt. Das heißt, alle pädagogischen Mitarbeitenden (Azubis, Quereinsteiger und Kitaleitungen) nehmen gemeinsam teil. In dem folgenden Formular können Sie Ihre Daten hinterlegen und Termine für die Schulungen Ihrer pädagogischen Fachkräfte auswählen bzw. Terminvorschläge für Ihre Schulungen an uns übermitteln.
                            </p>

                            <p class="tw-mb-4">
                                Aus organisatorischen Gründen ist es zunächst relevant, wie viele pädagogische Fachkräfte, inklusive Kitaleitung, an Ihrer Einrichtung beschäftigt sind. Bitte wählen Sie aus, wie viele pädagogische Fachkräfte zu Ihrem Team zählen:
                            </p>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="12" sm="5" class="tw-mb-8">
                            <v-text-field v-model="registrationForm.kita.participant_count"
                                          type="number"
                                          :min="1"
                                          :error-messages="errors['kita.participant_count']"
                                          label="Größe unseres pädagogischen Teams"
                                          required
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" sm="7" class="tw-mb-8">
                            <v-fade-transition>
                                <template v-if="showTrainingDisclaimer">
                                    <InfoMessage :text="`Leider gibt es aktuell keine verfügbarern Tremin für ein Team mit der Größe von ${registrationForm.kita.participant_count} Mitarbeitenden. <br/> Voraussichtlich ab nächsten August, werden neune Termin bekannt gegeben!`"/>
                                </template>
                            </v-fade-transition>
                        </v-col>
                    </v-row>
                </v-container>
            </template>
        </v-fade-transition>

        <v-fade-transition>
            <template v-if="!showSuccessMessage && showTrainingScreen">
                <v-container>
                    <v-row class="tw-mb-8">
                        <v-col cols="12">
                            <template v-if="isNewKitaWasLarge">
                                <p class="tw-mb-4">
                                    Die BeoKiz-Schulungen finden für Einrichtungen ab <b>11</b> pädagogischen Fachkräften als Schulung in ihrer Einrichtung statt.
                                </p>
                            </template>
                            <template v-else>
                                <p class="tw-mb-4">
                                    Die BeoKiz-Schulungen finden für Einrichtungen mit <b>bis zu 10</b> pädagogischen Fachkräften an bereits terminierten Zeiträumen in der KiTeAro Akademie (Stromstrasse 38, 10551 Berlin) statt und werden gemeinsam mit Fachkräften aus anderen Einrichtungen durchgeführt.
                                </p>
                            </template>

                            <p class="tw-mb-4">
                                Die Schulungen werden von zertifizierten und vom Land Berlin anerkannten BeoKiz-Multiplikator:innen durchgeführt. Einige Träger haben bereits ihre eigenen Fachberatungen als BeoKiz-Mulitiplikator:innen ausbilden lassen. Bitte geben Sie aus diesem Grund an, welchem Träger ihre Einrichtung angehört.
                            </p>
                        </v-col>
                    </v-row>

                    <v-row class="tw-mb-8">
                        <v-col cols="12" sm="4">
                            <v-select
                                v-model="registrationForm.kita.operator_id"
                                :items="selfTrainingOperators"
                                :error-messages="errors['kita.operator_id']"
                                item-title="name"
                                item-value="id"
                                label="Träger"
                            ></v-select>
                        </v-col>

                        <v-col cols="12" sm="4">
                            <template v-if="!registrationForm.kita.operator_id">
                                <v-text-field v-model="registrationForm.kita.other_operator"
                                              :error-messages="errors['kita.other_operator']"
                                              label="Sonstiger Träger"
                                ></v-text-field>
                            </template>
                        </v-col>

                        <v-col cols="12" sm="4">

                        </v-col>

                        <v-col cols="12" sm="4">

                        </v-col>
                    </v-row>

                    <v-fade-transition>
                        <v-row v-if="registrationForm.kita.other_operator">
                            <v-col cols="12">
                                <template v-if="!isNewKitaWasLarge">
                                    <h2 class="tw-font-semibold tw-text-base tw-text-gray-800 tw-leading-tight tw-mb-4">
                                        Noch verfügbare Schulungszeiträume sind folgende:
                                    </h2>
                                </template>
                            </v-col>

                            <v-col cols="12" md="6">
                                <template v-if="isNewKitaWasLarge">
                                    <h3 class="registration-training-heading tw-font-semibold tw-text-lg tw-text-gray-800 tw-leading-tight tw-mb-4">
                                        {{ `Terminvorschläge für Schulung (ab ${formatDate(initialDisplayDate, 'de-DE')} bis ${formatDate(maxDisplayDate, 'de-DE')})` }}
                                    </h3>

                                    <v-row v-for="(suggestion, index) in trainingSuggestions" :key="index" class="tw-mb-4">
                                        <v-col cols="10" sm="5">
                                            <v-locale-provider locale="de">
                                                <v-menu v-model="suggestion.isFirstDateFieldOpened"
                                                        :return-value.sync="suggestion.first_date"
                                                        :close-on-content-click="false">
                                                    <template v-slot:activator="{ props }">
                                                        <v-text-field
                                                            label="Erster Schulungstag*"
                                                            class="tw-cursor-pointer"
                                                            :model-value="suggestion.first_date ? formatDate(suggestion.first_date, 'de-DE') : null"
                                                            :error-messages="suggestion.first_day_error"
                                                            prepend-icon="mdi-calendar"
                                                            readonly
                                                            v-bind="props"
                                                      ></v-text-field>
                                                    </template>
                                                    <v-date-picker @update:modelValue="suggestion.isFirstDateFieldOpened = false;"
                                                                   v-model="suggestion.first_date"
                                                                   :min="minTrainingDate"
                                                                   :max="maxTrainingDate"
                                                                   :display-date="suggestion.first_date ? suggestion.first_date : initialDisplayDate"
                                                    ></v-date-picker>
                                                </v-menu>
                                            </v-locale-provider>
                                        </v-col>

                                        <v-col cols="10" sm="5">
                                            <v-locale-provider locale="de">
                                                <v-menu v-model="suggestion.isSecondDateFieldOpened"
                                                        :return-value.sync="suggestion.second_date"
                                                        :close-on-content-click="false">
                                                    <template v-slot:activator="{ props }">
                                                        <v-text-field
                                                            label="Zweiter Schulungstag*"
                                                            class="tw-cursor-pointer"
                                                            :model-value="suggestion.second_date ? formatDate(suggestion.second_date, 'de-DE') : null"
                                                            :error-messages="suggestion.second_day_error"
                                                            prepend-icon="mdi-calendar"
                                                            readonly
                                                            v-bind="props"
                                                        ></v-text-field>
                                                    </template>
                                                    <v-date-picker @update:modelValue="suggestion.isSecondDateFieldOpened = false;"
                                                                   v-model="suggestion.second_date"
                                                                   :min="minTrainingDate"
                                                                   :max="maxTrainingDate"
                                                                   :display-date="suggestion.second_date ? suggestion.second_date : initialDisplayDate"
                                                    ></v-date-picker>
                                                </v-menu>
                                            </v-locale-provider>
                                        </v-col>

                                        <v-col cols="2" class="tw-text-right">
                                            <v-btn icon @click="removeTrainingSuggestion(index)" v-if="trainingSuggestions.length > 1">
                                                <v-icon>mdi-delete</v-icon>
                                            </v-btn>
                                        </v-col>
                                    </v-row>

                                    <v-btn @click="addTrainingSuggestion" v-if="trainingSuggestions.length < maxTrainings">
                                        <v-icon v-bind="props" size="large" class="tw-me-2">mdi-plus-circle-outline</v-icon>
                                        Weiteren Terminvorschlag hinzufügen
                                    </v-btn>
                                </template>
                                <template v-else>
                                    <v-radio-group v-model="registrationForm.kita.training_id">
                                        <v-radio v-for="training in availableKitasByParticipantCount" :key="training.id"
                                                 :label="`${formatDate(training.first_date, 'de-DE')} und ${formatDate(training.second_date, 'de-DE')}`"
                                                 :value="training?.id"
                                        ></v-radio>
                                    </v-radio-group>
                                </template>
                            </v-col>

                            <v-col cols="12" md="6">
                                <InfoMessage :text="isNewKitaWasLarge ? 'Wählen Sie bitte nachfolgend zwei direkt aufeinanderfolgende Schulungstermine aus. Wenn für Sie zwei aufeinanderfolgende Termine nicht möglich sind, wählen Sie bitte zwei Tage aus, die weniger als 7 Tage auseinander liegen. Es handelt sich dabei um Terminvorschläge, die Sie einreichen. Eine Bestätigung der Schulungstermine findet erst statt, wenn sich ein BeoKiz-Multiplikator für Ihren Terminvorschlag gefunden hat und dieser Kontakt mit Ihnen aufgenommen hat.' : 'Bitte wählen Sie ein Schulungszeitraum aus, an welchen Tagen eine Durchführung mit Ihrem gesamten pädagogischen Team möglich ist. Schulungsort ist voraussichtlich in der Nähe des KiTeAro Akademie - Stromstr. 38 - 10551 Berlin.'"/>
                            </v-col>
                        </v-row>
                    </v-fade-transition>
                </v-container>
            </template>
        </v-fade-transition>

        <v-fade-transition>
            <template v-if="!showSuccessMessage && showKitaScreen">
                <v-container>
                    <v-row class="tw-mb-8">
                        <v-col cols="12">
                            <h2 class="tw-font-semibold tw-text-base tw-text-gray-800 tw-leading-tight">
                                Ihre Einrichtung
                            </h2>
                        </v-col>

                        <v-col cols="12" sm="6">
                            <v-text-field v-model="registrationForm.kita.number"
                                          :error-messages="errors['kita.number']"
                                          label="Kitanummer / 8-stellige Einrichtungsnummer *"
                                          type="number"
                                          required
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" sm="6">

                        </v-col>

                        <v-col cols="12" sm="6">
                            <v-text-field v-model="registrationForm.kita.name"
                                          :error-messages="errors['kita.name']"
                                          label="Einrichtungsname *"
                                          required
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" sm="6">
                            <v-text-field v-model="registrationForm.kita.district"
                                          :error-messages="errors['kita.district']"
                                          label="Bezirk"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" sm="6">
                            <v-text-field v-model="registrationForm.kita.street"
                                          :error-messages="errors['kita.street']"
                                          label="Straße *"
                                          required
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" sm="6">
                            <v-text-field v-model="registrationForm.kita.house_number"
                                          :error-messages="errors['kita.house_number']"
                                          label="Hausnummer *"
                                          required
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" sm="6">
                            <v-text-field v-model="registrationForm.kita.zip_code"
                                          :error-messages="errors['kita.zip_code']"
                                          label="Postleitzahl"
                                          type="number *"
                                          required
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" sm="6">
                            <v-text-field v-model="registrationForm.kita.city"
                                          :error-messages="errors['kita.city']"
                                          label="Stadt *"
                                          required
                            ></v-text-field>
                        </v-col>
                    </v-row>

                    <v-row class="tw-mb-8">
                        <v-col cols="12">
                            <h2 class="tw-font-semibold tw-text-base tw-text-gray-800 tw-leading-tight">
                                Ansprechpartner in Ihrer Einrichtung für die BeoKiz-Schulung
                            </h2>
                        </v-col>

                        <v-col cols="12" sm="6">
                            <v-text-field v-model="registrationForm.user.first_name"
                                          :error-messages="errors['user.first_name']"
                                          label="Vorname *"
                                          required
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" sm="6">
                            <v-text-field v-model="registrationForm.user.last_name"
                                          :error-messages="errors['user.last_name']"
                                          label="Nachname *"
                                          required
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" sm="6">
                            <v-text-field v-model="registrationForm.user.email"
                                          :error-messages="errors['user.email']"
                                          label="Email *"
                                          required
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" sm="6">
                            <v-text-field v-model="registrationForm.user.phone_number"
                                          :error-messages="errors['user.phone_number']"
                                          label="Telefonnummer"
                            ></v-text-field>
                        </v-col>
                    </v-row>

                    <v-row class="tw-mb-8">
                        <v-col cols="12">
                            <h2 class="tw-font-semibold tw-text-base tw-text-gray-800 tw-leading-tight">
                                Sonstiges
                            </h2>
                        </v-col>

                        <v-col cols="12" sm="6">
                              <v-textarea v-model="registrationForm.kita.additional_info"
                                          :error-messages="errors['kita.additional_info']"
                                          label="Anmerkungen"
                                          rows="3"
                              >
                              </v-textarea>
                          </v-col>

                          <v-col cols="12" sm="6">
                              <InfoMessage text="Für die Durchführung der BeoKiz-Schulung stehen, von der Senatsverwaltung für Bildung, Jugend und Familie anerkannte, Multiplikator:innen zur Verfügung. <br/> Wir freuen uns auf Sie!"/>
                          </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="12">
                            <v-checkbox
                                v-model="registrationForm.privacy_policy"
                                :value="true"
                            >
                                <template v-slot:label>
                                    <span>
                                        Ich erkläre mich mit der Verarbeitung der eingegebenen Daten sowie der
                                        <a href="https://kitearo.de/Datenschutzerklaerung/" target="_blank" class="tw-font-bold">Datenschutzerklärung</a>
                                        einverstanden. *
                                    </span>
                                </template>
                            </v-checkbox>
                        </v-col>
                    </v-row>
                </v-container>
            </template>
        </v-fade-transition>

        <v-fade-transition>
            <template v-if="!showSuccessMessage">
                <v-container>
                    <v-row>
                        <v-col cols="12" sm="6">
                            <v-hover v-slot:default="{ isHovering, props }">
                                <Link :href="route('auth.login')">
                                    <v-btn class="mr-2" variant="text" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Zurück</v-btn>
                                </Link>
                            </v-hover>
                        </v-col>

                        <v-col cols="12" sm="6" align="right">
                            <v-hover v-slot:default="{ isHovering, props }">
                                <v-btn-primary v-bind="props" :color="isHovering ? 'accent' : 'primary'"
                                               :disabled="!registrationForm.privacy_policy"
                                               @click="submitRegistration">
                                    Absenden
                                </v-btn-primary>
                            </v-hover>
                        </v-col>
                    </v-row>
                </v-container>
            </template>
        </v-fade-transition>

        <v-fade-transition>
            <template v-if="showSuccessMessage">
                <v-container>
                    <v-row>
                        <v-col cols="12" class="tw-mb-8">
                            <h2 class="tw-font-semibold tw-text-base tw-text-gray-800 tw-leading-tight tw-mb-4">
                                Anmeldung zur BeoKiz-Schulung
                            </h2>

                            <p class="tw-mb-4">
                                Vielen Dank für Ihre Anmeldung!
                            </p>

                            <p class="tw-mb-4">
                                Ihre Registrierung ist fast abgeschlossen. Um die Anmeldung abzuschließen, bestätigen Sie bitte Ihre E-Mail-Adresse. Eine E-Mail wurde an die von Ihnen angegebene Adresse gesendet. Bitte klicken Sie auf den Bestätigungslink in dieser E-Mail.
                            </p>

                            <p class="tw-mb-4">
                                Falls Sie die E-Mail nicht erhalten haben, überprüfen Sie bitte Ihren Spam-Ordner oder versuchen Sie es erneut.
                            </p>

                            <p class="tw-mb-4">
                                Wir freuen uns darauf, Sie bald bei der BeoKiz-Schulung willkommen zu heißen!
                            </p>

                            <p class="tw-mb-4">
                                Mit freundlichen Grüßen, <br/>Ihr BeoKiz-Team
                            </p>
                        </v-col>
                    </v-row>
                </v-container>
            </template>
        </v-fade-transition>

    </GuestLayout>
</template>
