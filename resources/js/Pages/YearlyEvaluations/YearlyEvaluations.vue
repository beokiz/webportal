<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import { computed, ref, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, useForm, usePage, router, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ages, prepareDate, formatDate } from '@/Composables/common';
import WarningEvaluationTooltip from "@/Components/WarningEvaluationTooltip.vue";

const props = defineProps({
    items: Array,
    currentPage: Number,
    perPage: Number,
    lastPage: Number,
    total: Number,
    paging: Boolean,
    orderBy: String,
    sort: String,
    filters: Object,
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

    if (pageType === 'YearlyEvaluations/YearlyEvaluations' && newProps) {
        currentPage.value = newProps.currentPage;
        perPage.value = newProps.perPage;
        orderBy.value = newProps.orderBy;
        sort.value = newProps.sort;
        totalItems.value = newProps.total;
        lastPage.value = newProps.lastPage;
        searchFilter.value = newProps.filters.search ?? null;
    }
});

/*
 * Main data
 */
const currentUser = usePage().props.auth.user ?? {};  // Global info about user

const currentPage = ref(props.currentPage); // Track the current page number
const perPage = ref(props.perPage); // Number of products per page
const orderBy = ref(props.orderBy);
const sort = ref(props.sort);
const totalItems = ref(props.total);
const lastPage = ref(props.lastPage);
const searchFilter = ref(props.filters.search ?? null);
const search = ref('');
const errors = ref(props.errors || {});

const loading = ref(false);
const dialog = ref(false);
const dialogIssues = ref(false);
const dialogDeleteEvaluation = ref(false);
const deletingItemName = ref(null);
const allowSaveState = ref(false);
const canSaveMainForm = ref(false);
const canSavePopupForm = ref(false);

const headers = [
    { title: 'Jahr', key: 'year', width: '7%', sortable: false},
    { title: 'Kitas', key: 'kita_name', width: '11%', sortable: false },
    { title: 'gemeldete Kinder  bis 2,5 Jahre', key: 'children_2_born_per_year', width: '18%', sortable: false },
    { title: 'gemeldete Kinder  bis 4,5 Jahre', key: 'children_4_born_per_year', width: '18%', sortable: false },
    { title: 'Evaluationen für Kinder  bis 2,5 Jahre', key: 'evaluations_with_daz_2_total_per_year', width: '18%', sortable: false },
    { title: 'Evaluationen für Kinder  bis 4,5 Jahre', key: 'evaluations_with_daz_4_total_per_year', width: '18%', sortable: false },
    { title: 'Aktion', key: 'actions', width: '10%', sortable: false, align: 'center'},
];


// Computed
const modifiedItems = computed(() => {
    return props.items.map(item => {
        const modifiedItem = { ...item };
        for (const key in modifiedItem) {
            if (modifiedItem[key] === null || modifiedItem[key] === undefined) {
                modifiedItem[key] = '-';
            }
        }
        return modifiedItem;
    });
});

const allFiltersEmpty = computed(() => {
    return searchFilter.value === null;
});

const someFiltersNotEmpty = computed(() => {
    return searchFilter.value !== null;
});

const childsTotal2Label = computed(() => {
    return getChildsTotalLabel('2.5');
});

const childsTotal4Label = computed(() => {
    return getChildsTotalLabel('4.5');
});

const selectedKita = computed(() => {
    let selectedKita = props.kitas.find(kita => {
        return manageForm.kita_id === parseInt(kita.id);
    });

    return selectedKita;
});

// Watch
watch(dialog, (val) => {
    if (!val) {
        close();
    }
});

watch(dialogIssues, (val) => {
    canSavePopupForm.value = true;
});

// Methods
const goToPage = async ({ page, itemsPerPage, sortBy, clearFilters }) => {
    if (clearFilters) {
        searchFilter.value = null;
    }

    if (
        (page === currentPage.value && clearFilters) ||
        allFiltersEmpty ||
        someFiltersNotEmpty
    ) {
        loading.value = true;

        let options = { data: { page: page, per_page: itemsPerPage } };

        if (sortBy && sortBy.length > 0) {
            options.data.order_by = sortBy[0].key;
            options.data.sort = sortBy[0].order;
        }

        // Search filters
        options.data.search = searchFilter.value;

        await router.reload(options);

        currentPage.value = page;
        perPage.value = itemsPerPage;
        loading.value = false;
    }
};

const openDeleteEvaluationDialog = (item) => {
    deletingItemName.value = item.year
    deleteForm.id = item.id;
    dialogDeleteEvaluation.value = true
};

const deleteForm = useForm({
    id: null,
});

const deleteEvaluation = async () => {
    deleteForm.processing = true;

    let formOptions = {
        onSuccess: (page) => {
            close();
        },
        onError: (err) => {
            close();
            errors.value = err;
        },
        onFinish: () => {
            deleteForm.processing = false;
        },
    };

    deleteForm.delete(route('yearly_evaluations.destroy', { id: deleteForm.id }), formOptions);
};


const closeDialogIssues = () => {
    dialogIssues.value = false;
};

const allowSave = () => {
    canSavePopupForm.value = true;
    closeDialogIssues()
};

const close = () => {
    dialog.value = false;
    dialogIssues.value = false;
    dialogDeleteEvaluation.value = false;
    allowSaveState.value = false;
    canSavePopupForm.value = false;
    manageForm.reset();
    manageForm.clearErrors();

    errors.value = {};
};

const clear = () => {
    manageForm.reset();
    manageForm.clearErrors();
};

const manageForm = useForm({
    year: new Date().getFullYear().toString().padStart(4, '0'),
    kita_id: props?.kitas.length > 0 ? props?.kitas[0]?.id : null,
    evaluations_with_daz_2_total_per_year: props?.kitas.length > 0 ? props?.kitas[0]?.evaluations_with_daz2_total_per_year_count : 0,
    evaluations_with_daz_4_total_per_year: props?.kitas.length > 0 ? props?.kitas[0]?.evaluations_with_daz4_total_per_year_count : 0,
    evaluations_without_daz_2_total_per_year: props?.kitas.length > 0 ? props?.kitas[0]?.evaluations_without_daz2_total_per_year_count : 0,
    evaluations_without_daz_4_total_per_year: props?.kitas.length > 0 ? props?.kitas[0]?.evaluations_without_daz4_total_per_year_count : 0,
    children_2_born_per_year: null,
    children_4_born_per_year: null,
    children_2_with_german_lang: null,
    children_4_with_german_lang: null,
    children_2_with_foreign_lang: null,
    children_4_with_foreign_lang: null,
});

watch(() => manageForm.kita_id, (newValue, oldValue) => {
    if (selectedKita.value) {
        manageForm.evaluations_with_daz_2_total_per_year = selectedKita.value.evaluations_with_daz2_total_per_year_count;
        manageForm.evaluations_with_daz_4_total_per_year = selectedKita.value.evaluations_with_daz4_total_per_year_count;
        manageForm.evaluations_without_daz_2_total_per_year = selectedKita.value.evaluations_without_daz2_total_per_year_count;
        manageForm.evaluations_without_daz_4_total_per_year = selectedKita.value.evaluations_without_daz4_total_per_year_count;
    }
});

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

const createYearlyEvaluation = async () => {
    manageForm.processing = true;

    manageForm.post(route('yearly_evaluations.store'), {
        preserveState: true,
        onSuccess: (page) => {
            close();
        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            closeDialogIssues();
            manageForm.processing = false;
        },
    });
};

const checkYears = (type) => {
    if(type === '2_german'){
        return parseInt(manageForm.evaluations_with_daz_2_total_per_year) !== parseInt(manageForm.children_2_with_german_lang ?? 0)
    } else if(type === '2_foreign') {
        return parseInt(manageForm.evaluations_without_daz_2_total_per_year) !== parseInt(manageForm.children_2_with_foreign_lang ?? 0)
    } else if (type === '4_german'){
        return parseInt(manageForm.evaluations_with_daz_4_total_per_year) !== parseInt(manageForm.children_4_with_german_lang ?? 0)
    } else if (type === '4_foreign'){
        return parseInt(manageForm.evaluations_without_daz_4_total_per_year) !== parseInt(manageForm.children_4_with_foreign_lang ?? 0)
    }
};

const manageYearlyEvaluation = async () => {
    if( (checkYears('2_german') || checkYears('2_foreign') || checkYears('4_german') || checkYears('4_foreign')) && !canSavePopupForm.value ) {
        dialogIssues.value = true
    } else {
        await createYearlyEvaluation()
    }
};

const getChildsTotalLabel = (age) => {
    if (age === '2.5' || age === '4.5') {
        const currentYear = parseInt(manageForm.year);

        let startYear, endYear;

        if (age === '4.5') {
            startYear = currentYear - 6;
            endYear = currentYear - 5;
        } else {
            startYear = currentYear - 4;
            endYear = currentYear - 3;
        }

        return `Gesamtzahl der im Zeitraum vom 01.10.${startYear} bis 30.09.${endYear} geborenen Kinder`;
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

const getWarningText = (age, childs_amount, evaluations_amount) => {
    return `Die Anzahl der Kinder im Alter bis ${age} Jahre welche Deutsch als Fremdsprache haben Sie mit ${childs_amount} angegeben, die Anzahl der  über die BeoKiz-Ampel erfassten Kindern beträgt ${evaluations_amount}`;
}
</script>

<template>
    <Head title="Jährliche Rückmeldung" />

    <AuthenticatedLayout :errors="errors">
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">Jährliche Rückmeldung</h2>

            <div class="tw-flex tw-items-center tw-justify-end">
                <v-hover v-slot:default="{ isHovering, props }">
                    <v-btn v-bind="props" :color="isHovering ? 'accent' : 'primary'" dark>
                        ANLEGEN

                        <v-dialog v-model="dialog" activator="parent" width="80vw">
                            <v-card height="80vh">
                                <v-card-title>
                                    <span class="tw-text-h5">Jährliche Rückmeldung erstellen</span>
                                </v-card-title>

                                <v-card-text>
                                    <v-container>
                                        <v-row>
                                            <v-col cols="12" sm="12">
                                                <h3>Rückmeldung betrifft</h3>
                                            </v-col>
                                            <v-col cols="12" sm="4">
                                                <v-text-field v-model="manageForm.year"
                                                              :error-messages="errors.year"
                                                              disabled
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
                                                    <v-text-field v-model="manageForm.children_2_born_per_year"
                                                                  :error-messages="errors.children_2_born_per_year"
                                                                  :label="childsTotal2Label" type="number" required></v-text-field>

                                                    <v-row>
                                                        <v-col cols="12" sm="7">
                                                            <v-text-field v-model="manageForm.children_2_with_german_lang"
                                                                          :error-messages="errors.children_2_with_german_lang"
                                                                          label="Kinder mit deutscher Herkunftssprache*" type="number" required></v-text-field>
                                                        </v-col>
                                                        <v-col cols="12" sm="5">
                                                            <v-row>
                                                                <WarningEvaluationTooltip v-if="checkYears('2_german')"/>
                                                                <v-col cols="12" sm="10">
                                                                    <v-text-field v-model="manageForm.evaluations_with_daz_2_total_per_year"
                                                                                  :error-messages="errors.evaluations_with_daz_2_total_per_year"
                                                                                  label="Bisher eingereichte Einschätzungen" type="number" disabled required>
                                                                    </v-text-field>
                                                                </v-col>
                                                            </v-row>
                                                        </v-col>
                                                    </v-row>
                                                    <v-row>
                                                        <v-col cols="12" sm="7">
                                                            <v-text-field v-model="manageForm.children_2_with_foreign_lang"
                                                                          :error-messages="errors.children_2_with_foreign_lang"
                                                                          label="Kinder mit nicht deutscher Herkunftssprache*" type="number" required></v-text-field>
                                                        </v-col>
                                                        <v-col cols="12" sm="5">
                                                            <v-row>
                                                                <WarningEvaluationTooltip v-if="checkYears('2_foreign')"/>
                                                                <v-col cols="12" sm="10">
                                                                    <v-text-field v-model="manageForm.evaluations_without_daz_2_total_per_year"
                                                                                  :error-messages="errors.evaluations_without_daz_2_total_per_year"
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
                                                    <v-text-field v-model="manageForm.children_4_born_per_year"
                                                                  :error-messages="errors.children_4_born_per_year"
                                                                  :label="childsTotal4Label" type="number" required></v-text-field>


                                                    <v-row>
                                                        <v-col cols="12" sm="7">
                                                            <v-text-field v-model="manageForm.children_4_with_german_lang"
                                                                          :error-messages="errors.children_4_with_german_lang"
                                                                          label="Kinder mit deutscher Herkunftssprache*" type="number" required></v-text-field>
                                                        </v-col>
                                                        <v-col cols="12" sm="5">
                                                            <v-row>
                                                                <WarningEvaluationTooltip v-if="checkYears('4_german')"/>
                                                                <v-col cols="12" sm="10">
                                                                    <v-text-field v-model="manageForm.evaluations_with_daz_4_total_per_year"
                                                                                  :error-messages="errors.evaluations_with_daz_4_total_per_year"
                                                                                  label="Bisher eingereichte Einschätzungen" type="number" disabled required></v-text-field>
                                                                </v-col>
                                                            </v-row>
                                                        </v-col>
                                                    </v-row>

                                                    <v-row>
                                                        <v-col cols="12" sm="7">
                                                            <v-text-field v-model="manageForm.children_4_with_foreign_lang"
                                                                          :error-messages="errors.children_4_with_foreign_lang"
                                                                          label="Kinder mit nicht deutscher Herkunftssprache*" type="number" required></v-text-field>
                                                        </v-col>
                                                        <v-col cols="12" sm="5">
                                                            <v-row>
                                                                <WarningEvaluationTooltip v-if="checkYears('4_foreign')"/>
                                                                <v-col cols="12" sm="10">
                                                                    <v-text-field v-model="manageForm.evaluations_without_daz_4_total_per_year"
                                                                                  :error-messages="errors.evaluations_without_daz_4_total_per_year"
                                                                                  label="Bisher eingereichte Einschätzungen" type="number" disabled required></v-text-field>
                                                                </v-col>
                                                            </v-row>
                                                        </v-col>
                                                    </v-row>
                                                </v-card>
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </v-card-text>

                                <v-card-actions>
                                    <v-hover v-slot:default="{ isHovering, props }">
                                        <v-btn-primary @click="clear" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Zurücksetzen</v-btn-primary>
                                    </v-hover>
                                    <v-spacer></v-spacer>
                                    <v-hover v-slot:default="{ isHovering, props }">
                                        <v-btn @click="close" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Stornieren</v-btn>
                                    </v-hover>
                                    <v-hover v-slot:default="{ isHovering, props }">
                                        <v-btn-primary @click="manageYearlyEvaluation" v-bind="props" :color="isHovering ? 'accent' : 'primary'" :disabled="!canSaveMainForm">Speichern</v-btn-primary>
                                    </v-hover>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-btn>
                </v-hover>
            </div>

            <v-dialog v-model="dialogDeleteEvaluation" width="20vw">
                <v-card height="30vh">
                    <v-card-text>
                        <v-container>
                            <v-row>
                                <v-col cols="12">
                                    <p>Sind Sie sicher, dass Sie die Jährliche Rückmeldung {{deletingItemName}} löschen möchten?</p>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-card-text>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-hover v-slot:default="{ isHovering, props }">
                            <v-btn @click="close" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Abbrechen</v-btn>
                        </v-hover>
                        <v-hover v-slot:default="{ isHovering, props }">
                            <v-btn-primary @click="deleteEvaluation" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Löschen</v-btn-primary>
                        </v-hover>
                    </v-card-actions>
                </v-card>
            </v-dialog>


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
                                                {{ getWarningText('2,5', manageForm.children_2_with_german_lang, manageForm.evaluations_with_daz_2_total_per_year) }}
                                            </li>
                                            <li v-if="checkYears('2_foreign')">
                                                {{ getWarningText('2,5', manageForm.children_2_with_foreign_lang, manageForm.evaluations_without_daz_2_total_per_year) }}
                                            </li>
                                            <li v-if="checkYears('4_german')">
                                                {{ getWarningText('4,5', manageForm.children_4_with_german_lang, manageForm.evaluations_with_daz_4_total_per_year) }}
                                            </li>
                                            <li v-if="checkYears('4_foreign')">
                                                {{ getWarningText('4,5', manageForm.children_4_with_foreign_lang, manageForm.evaluations_without_daz_4_total_per_year) }}
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
        </template>

        <div class="tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8">

            <v-data-table-server
                v-model:items-per-page="perPage"
                :items-per-page-options="[
                  {value: 10, title: '10'},
                  {value: 25, title: '25'},
                  {value: 50, title: '50'},
                  {value: 100, title: '100'},
                  {value: -1, title: '$vuetify.dataFooter.itemsPerPageAll'}
                ]"
                :items-per-page-text="'Objekte pro Seite:'"
                :headers="headers"
                :page="currentPage"
                :items-length="totalItems"
                :items="modifiedItems"
                :search="search"
                v-sortable-data-table
                :loading="loading"
                class="data-table-container elevation-1"
                item-value="name"
                @update:options="goToPage"
            >

                <template v-slot:item="{ item }">
                    <tr :data-id="item.selectable.id" :data-order="item.selectable.order">
                        <td>{{item.selectable.year}}</td>

                        <td>{{item.selectable?.kita?.formatted_name}}</td>

                        <td>{{ item.selectable.children_2_born_per_year }}</td>

                        <td>{{ item.selectable.children_4_born_per_year }}</td>


                        <td>{{ item.selectable.evaluations_with_daz_2_total_per_year + item.selectable.evaluations_without_daz_2_total_per_year }}</td>

                        <td>{{ item.selectable.evaluations_with_daz_4_total_per_year + item.selectable.evaluations_without_daz_4_total_per_year }}</td>

                        <td align="center">
                            <v-tooltip location="top">
                                <template v-slot:activator="{ props }">
                                    <Link :href="route('yearly_evaluations.show', { id: item.selectable.id })">
                                        <v-icon v-bind="props" size="small" class="tw-me-2">mdi-pencil</v-icon>
                                    </Link>
                                </template>
                                <span>Jährliche Rückmeldung bearbeiten</span>
                            </v-tooltip>

                            <v-tooltip location="top">
                                <template v-slot:activator="{ props }">
                                    <v-icon v-bind="props" size="small" class="tw-me-2" @click="openDeleteEvaluationDialog(item.raw)">mdi-delete</v-icon>
                                </template>
                                <span>Jährliche Rückmeldung löschen</span>
                            </v-tooltip>
                        </td>
                    </tr>
                </template>

                <template v-slot:no-data>
                    <div class="tw-py-6">
                        <template v-if="allFiltersEmpty">
                            <h3 class="tw-mb-4">Die Tabelle ist leer.</h3>
                        </template>
                        <template v-else>
                            <h3 class="tw-mb-4">Die Tabelle ist leer. Bitte setzen Sie die Suchfilter zurück.</h3>

                            <v-btn color="primary" @click="goToPage({ page: 1, itemsPerPage: perPage, clearFilters: true })">Reset</v-btn>
                        </template>
                    </div>
                </template>

            </v-data-table-server>
        </div>

    </AuthenticatedLayout>
</template>
