<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import { computed, ref, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, useForm, usePage, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { debounce } from "lodash";
import { prepareDate } from "@/Composables/common.js";
import VueTimepicker from "vue3-timepicker";

const props = defineProps({
    trainingProposal: Object,
    notEditable: Boolean,
    allKitas: Array,
    trainingProposalKitas: Array,
    errors: Object,
    multipliers: Array,
    operators: Array,
    statuses: Array,
    types: Array,
    emailMessages: Object,
    from: String,
    // Kitas table
    currentPage: Number,
    perPage: Number,
    lastPage: Number,
    total: Number,
    paging: Boolean,
    orderBy: String,
    sort: String,
    filters: Object,
    zipCodes: Array,
    kitaTypes: Array,
    usersEmails: Array,
});


/*
 * Inertia events handling
 */
// For page reloading on pagination or searching
Inertia.on('success', (event) => {
    let newProps = event.detail.page.props;
    let pageType = event.detail.page.component;

    if (pageType === 'TrainingProposals/Partials/ManageTrainingProposal' && newProps) {
        editedTrainingProposal.value = newProps.trainingProposal;
    }
});


/*
 * Main data
 */
// const currentUser = usePage().props.auth.user ?? {};  // Global info about user

const editedTrainingProposal = ref(props.trainingProposal);
const errors = ref(props.errors || {});
const loading = ref(false);
const selectedKitaName = ref(null);
const dialog = ref(false);
const acceptTrainingProposalDialog = ref(false);
const revokeTrainingProposalDialog = ref(false);
const confirmKitaParticipationDialog = ref(false);
const confirmTrainingProposalDialog = ref(false);
const addMultiplierToTrainingProposalDialog = ref(false);
const addKitaToTrainingProposalDialog = ref(false);
const removeKitaFromTrainingProposalDialog = ref(false);

const isFirstDateFieldOpened = ref(false);
const isSecondDateFieldOpened = ref(false);

const rawFirstDateField = ref(prepareDate(props.trainingProposal.first_date));
const rawSecondDateField = ref(prepareDate(props.trainingProposal.second_date));
const firstDateField = ref(new Date(props.trainingProposal.first_date).toString());
const secondDateField = ref(new Date(props.trainingProposal.second_date).toString());

const confirmedTrainingProposalInfo = ref([
    {
      label: 'Erster Schulungstag',
      value: `${prepareDate(editedTrainingProposal.value?.first_date)}`,
    },
    {
      label: 'Zweiter Schulungstag',
      value: `${prepareDate(editedTrainingProposal.value?.second_date)}`,
    },
    {
      label: 'Ort',
      value: editedTrainingProposal.value?.formatted_location,
    },
    {
      label: 'Teilnehmerzahl',
      value: editedTrainingProposal.value?.participant_count,
    },
]);

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

    return route('training_proposals.index');
});

watch(dialog, (val) => {
    if (!val) {
        close();
    }
});

watch(firstDateField, (val) => {
    rawFirstDateField.value = val ? prepareDate(val) : null;
});

watch(secondDateField, (val) => {
    rawSecondDateField.value = val ? prepareDate(val) : null;
});

// Methods
const manageForm = useForm({
    id: editedTrainingProposal.value?.id,
    multi_id: editedTrainingProposal.value?.multi_id,
    first_date: editedTrainingProposal.value?.first_date,
    second_date: editedTrainingProposal.value?.second_date,
    location: editedTrainingProposal.value?.location,
    participant_count: editedTrainingProposal.value?.participant_count,
    // status: editedTrainingProposal.value?.status,
    street: editedTrainingProposal.value?.street,
    house_number: editedTrainingProposal.value?.house_number,
    zip_code: editedTrainingProposal.value?.zip_code,
    city: editedTrainingProposal.value?.city,
    district: editedTrainingProposal.value?.district,
    notes: editedTrainingProposal.value?.notes,
});

const manageTrainingProposal = async () => {
    manageForm.processing = true;

    manageForm.first_date = firstDateField.value ? new Date(new Date(firstDateField.value).setHours(12, 0, 0, 0)).toISOString() : null;
    manageForm.second_date = secondDateField.value ? new Date(new Date(secondDateField.value).setHours(12, 0, 0, 0)).toISOString() : null;

    let formOptions = {
        // preserveState: false,
        onSuccess: (page) => {
            close();
        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            manageForm.processing = false;
        },
    };

    manageForm.put(route('training_proposals.update', { trainingProposal: manageForm.id }), formOptions);
};


const openAddMultiplierToTrainingProposalDialog = () => {
    addMultiplierToTrainingProposalDialog.value = true;
};

const addMultiplierToTrainingProposalForm = useForm({
    multi_id: null,
});

const addMultiplierToTrainingProposal = async () => {
    addMultiplierToTrainingProposalForm.processing = true;

    addMultiplierToTrainingProposalForm.post(route('training_proposals.add_multiplier', { trainingProposal: props.trainingProposal.id }), {
        onSuccess: (page) => {
            close();
        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            addMultiplierToTrainingProposalForm.processing = false;
        },
    });
};


const openConfirmTrainingProposalDialog = () => {
    confirmTrainingProposalDialog.value = true;
};

const openAcceptTrainingProposalDialog = () => {
    acceptTrainingProposalDialog.value = true;
};

const openRevokeTrainingProposalDialog = () => {
    revokeTrainingProposalDialog.value = true;
};

const manageStatusForm = useForm({
    id: editedTrainingProposal.value?.id,
    status: null,
    multi_id: null,
});

const manageTrainingProposalStatus = async (status) => {
    manageStatusForm.processing = true;

    manageStatusForm.status = status;

    if (status !== 'open') {
        delete manageStatusForm.multi_id;
    }

    let formOptions = {
        preserveState: false,
        onSuccess: (page) => {
            close();
        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            manageStatusForm.processing = false;
        },
    };

    manageStatusForm.put(route('training_proposals.update', { trainingProposal: manageStatusForm.id }), formOptions);
};

/*
 * Add / remove Kita from Training Proposal
 */
const openAddKitaToTrainingProposalDialog = () => {
    addKitaToTrainingProposalDialog.value = true;
};

const addKitaToTrainingProposalForm = useForm({
    kitas: [],
});

const showConfirmationPopupButton = computed(() => {
    let currentUser = usePage().props.auth.user;
    let attachedKitasCount = props.trainingProposalKitas?.length;

    return editedTrainingProposal.value?.status === 'reserved' && attachedKitasCount > 0 && editedTrainingProposal.value?.multi_id;
});

const allKitasExcludeAdded = computed(() => {
    let trainingProposalKitasIds = props.trainingProposalKitas.map(kita => kita.id);

    return props.allKitas.filter(kita => !trainingProposalKitasIds.includes(kita.id));
});

const addKitaToTrainingProposal = async () => {
    addKitaToTrainingProposalForm.processing = true;

    addKitaToTrainingProposalForm.post(route('training_proposals.add_kitas', { trainingProposal: props.trainingProposal.id }), {
        onSuccess: (page) => {
            close();
        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            addKitaToTrainingProposalForm.processing = false;
        },
    });
};

const openRemoveKitaFromTrainingProposalDialog = (item) => {
    selectedKitaName.value = item.name
    removeKitaFromTrainingProposalForm.kita = item.id;
    removeKitaFromTrainingProposalDialog.value = true;
};

const removeKitaFromTrainingProposalForm = useForm({
    kita: null,
});

const removeKitaFromTrainingProposal = async () => {
    removeKitaFromTrainingProposalForm.processing = true;

    let formOptions = {
        onSuccess: (page) => {
            close();
        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            removeKitaFromTrainingProposalForm.processing = false;
        },
    };

    removeKitaFromTrainingProposalForm.post(route('training_proposals.remove_kita', {id: props.trainingProposal.id}), formOptions);
};


const close = () => {
    dialog.value = false;
    selectedKitaName.value = null;
    acceptTrainingProposalDialog.value = false;
    revokeTrainingProposalDialog.value = false;
    confirmKitaParticipationDialog.value = false;
    confirmTrainingProposalDialog.value = false;
    addMultiplierToTrainingProposalDialog.value = false;
    addKitaToTrainingProposalDialog.value = false;
    removeKitaFromTrainingProposalDialog.value = false;

    manageStatusForm.reset();
    manageStatusForm.clearErrors();
    addKitaToTrainingProposalForm.reset();
    addKitaToTrainingProposalForm.clearErrors();
    removeKitaFromTrainingProposalForm.reset();
    removeKitaFromTrainingProposalForm.clearErrors();

    errors.value = {};
};

const clear = () => {
    manageForm.reset();
    manageForm.clearErrors();

    manageStatusForm.reset();
    manageStatusForm.clearErrors();
    addKitaToTrainingProposalForm.reset();
    addKitaToTrainingProposalForm.clearErrors();
    removeKitaFromTrainingProposalForm.reset();
    removeKitaFromTrainingProposalForm.clearErrors();

    firstDateField.value = null;
    secondDateField.value = null;
};

const confirmKitaParticipationForm = useForm({
    kita_id: null,
});


const openConfirmKitaParticipationDialog = (item) => {
    selectedKitaName.value = item.name;
    confirmKitaParticipationForm.kita_id = item.id;
    confirmKitaParticipationDialog.value = true;
};


const confirmKitaParticipation = async () => {
    console.log("Bestätigung gestartet für Kita:", confirmKitaParticipationForm.kita_id);

    confirmKitaParticipationForm.processing = true;

    let formOptions = {
        onSuccess: () => {
            closeConfirmKitaParticipationDialog();
        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            confirmKitaParticipationForm.processing = false;
        },
    };

    try {
        await confirmKitaParticipationForm.post(
            route('training_proposals.confirm_by_admin', { id: props.trainingProposal.id }),
            { kita_id: confirmKitaParticipationForm.kita_id, ...formOptions }
        );
    } catch (error) {
        console.error("Fehler bei der Anfrage:", error);
    }
};

const closeConfirmKitaParticipationDialog = () => {
    confirmKitaParticipationDialog.value = false;
    confirmKitaParticipationForm.reset();
};

/*
 * Kitas tables
 */
const modifyItems = (items) => {
    return items && items.length > 0 ? items.map(item => {
        const modifiedItem = {...item};
        for (const key in modifiedItem) {
            if (modifiedItem[key] === null || modifiedItem[key] === undefined) {
                modifiedItem[key] = '-';
            }
        }
        return modifiedItem;
    }) : [];
};

const headers = [
    { title: 'Name', key: 'name', width: '10%', sortable: true },
    { title: 'Bestätigt', key: 'training_proposal_confirmed', width: '10%', sortable: false },
    { title: `Jährliche Rückmeldung ${new Date().getFullYear()} abgeschlossen`, key: 'has_yearly_evaluations', width: '25%', sortable: true },
    { title: 'Zugelassen', key: 'approved', width: '10%', sortable: true },
    { title: 'Träger', key: 'operator_id', width: '10%', sortable: true },
    { title: 'Sonstiger Träger', key: 'other_operator', width: '10%', sortable: true },
    { title: 'Typ', key: 'type', width: '9%', sortable: true },
    { title: 'Postleitzahl', key: 'zip_code', width: '8%', sortable: true },
    { title: 'Aktion', key: 'actions', width: '8%', sortable: false, align: 'center' },
];

const currentPage = ref(props?.currentPage); // Track the current page number
const perPage = ref(props?.perPage); // Number of products per page
const orderBy = ref(props?.orderBy);
const sort = ref(props?.sort);
const totalItems = ref(props?.total);
const lastPage = ref(props?.lastPage);
const search = ref('');
// Kitas filters
const searchFilter = ref(props?.filters?.search ?? null);
const hasYearlyEvaluationsFilter = ref(props?.filters?.has_yearly_evaluations ?? null);
const approvedFilter = ref(props?.filters?.approved ?? null);
const operatorIdFilter = ref(null);
const otherOperatorFilter = ref(props.filters?.other_operator ?? null);
const typeFilter = ref(props?.filters?.type ?? null);
const zipCodeFilter = ref(props?.filters?.zip_code ?? null);

const hasYearlyEvaluationsFilterValues = [
    {
        title: 'Ja',
        value: 'true',
    },
    {
        title: 'Nein',
        value: 'false',
    },
];

const approvedFilterValues = [
    {
        title: 'Ja',
        value: 'true',
    },
    {
        title: 'Nein',
        value: 'false',
    },
];

const selectedUsersEmails = ref([]);
const dialogUsersEmails = ref(false);

const modifiedItems = computed(() => {
    let modifiedItems = modifyItems(props.trainingProposalKitas);

    return modifiedItems.map(item => {
        const modifiedItem = {...item};

        modifiedItem.training_proposal_confirmed = item?.training_proposal_confirmations?.some(item => item.confirmed === true && item.training_proposal_id === editedTrainingProposal.value?.id);

        return modifiedItem;
    });
});

const allFiltersEmpty = computed(() => {
    return searchFilter.value === null && hasYearlyEvaluationsFilter.value === null && approvedFilter.value === null && operatorIdFilter.value === null && otherOperatorFilter.value === null && typeFilter.value === null && zipCodeFilter.value === null;
});

const someFiltersNotEmpty = computed(() => {
    return searchFilter.value !== null || hasYearlyEvaluationsFilter.value !== null || approvedFilter.value !== null || operatorIdFilter.value !== null || otherOperatorFilter.value !== null || typeFilter.value !== null || zipCodeFilter.value !== null;
});

// Kitas filters
watch(searchFilter, debounce((val) => {
    triggerSearch();
}, 500));

watch(hasYearlyEvaluationsFilter, (val) => {
    triggerSearch();
});

watch(approvedFilter, (val) => {
    triggerSearch();
});

watch(operatorIdFilter, (val) => {
    triggerSearch();
});

watch(otherOperatorFilter, debounce((val) => {
    triggerSearch();
}, 500));

watch(typeFilter, (val) => {
    triggerSearch();
});

watch(zipCodeFilter, debounce((val) => {
    triggerSearch();
}, 500));

const triggerSearch = (type) => {
    loading.value = true;
    search.value = String(Date.now());
};

const openUsersEmailsDialog = () => {
    dialogUsersEmails.value = true;
    selectedUsersEmails.value = props.usersEmails.map(item => item.value);
};

const goToPage = async ({ page, itemsPerPage, sortBy, clearFilters }) => {
    if (clearFilters) {
        searchFilter.value = null;
        hasYearlyEvaluationsFilter.value = null;
        approvedFilter.value = null;
        operatorIdFilter.value = null;
        otherOperatorFilter.value = null;
        typeFilter.value = null;
        zipCodeFilter.value = null;
    }

    if (
        (page === currentPage.value && clearFilters) ||
        allFiltersEmpty ||
        someFiltersNotEmpty
    ) {
        loading.value = true;

        let data = {
            page: page,
            per_page: itemsPerPage,
        };

        if (sortBy && sortBy.length > 0) {
            data.order_by = sortBy[0].key;
            data.sort = sortBy[0].order;
        } else {
            data.order_by = null;
            data.sort = null;
        }

        if (props.from) {
            data.from = props.from;
        }

        // Apply kitas filters
        if (searchFilter.value) {
            data.search = searchFilter.value;
        }

        if (hasYearlyEvaluationsFilter.value) {
            data.has_yearly_evaluations = hasYearlyEvaluationsFilter.value;
        }

        if (approvedFilter.value) {
            data.approved = approvedFilter.value;
        }

        if (operatorIdFilter.value) {
            data.operator_id = operatorIdFilter.value;
        }

        if (otherOperatorFilter.value) {
            data.other_operator = otherOperatorFilter.value;
        }

        if (typeFilter.value) {
            data.type = typeFilter.value;
        }

        if (zipCodeFilter.value) {
            data.zip_code = zipCodeFilter.value;
        }

        await router.get(route(route().current(), { trainingProposal: manageForm.id }), data, {
            preserveScroll: true,
            preserveState: true,
            onCancelToken: cancelToken => {},
            onCancel: () => {},
            onBefore: visit => {
                loading.value = true;
            },
            onStart: visit => {},
            onProgress: progress => {},
            onSuccess: page => {
                currentPage.value = data.page;
                perPage.value = data.per_page;
            },
            onError: errors => {
                console.log(errors);
            },
            onFinish: visit => {
                loading.value = false;
            },
        });
    }
};
</script>

<template>
    <Head :title="`Verwalte Terminvorschlag`"/>

    <AuthenticatedLayout :errors="errors">
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">Verwalte Terminvorschlag</h2>
        </template>

        <div class="tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8">
            <v-container>
                <v-row>
                    <v-col cols="12" sm="3">
                        <h3>Eigenschaften des Terminvorschlags</h3>
                    </v-col>

                    <v-col cols="12" sm="9" class="text-right">
                        <template v-if="editedTrainingProposal.status === 'open' && ($page.props.auth.user.is_super_admin || $page.props.auth.user.is_admin)">
                            <v-hover v-slot:default="{ isHovering, props }">
                                <v-btn class="tw-ml-4 tw-mb-4" v-bind="props" :color="isHovering ? 'accent' : 'primary'" dark @click="openAddMultiplierToTrainingProposalDialog">
                                    <span>Akzeptieren für Multiplikator</span>
                                </v-btn>
                            </v-hover>
                        </template>

                        <template v-if="showConfirmationPopupButton">
                            <v-hover v-slot:default="{ isHovering, props }">
                                <v-btn class="tw-ml-4 tw-mb-4" v-bind="props" :color="isHovering ? 'accent' : 'success'" dark @click="openConfirmTrainingProposalDialog">
                                    <span>Termin bestätigen</span>
                                </v-btn>
                            </v-hover>
                        </template>

                        <template v-if="editedTrainingProposal.status === 'reserved'">
                            <v-hover v-slot:default="{ isHovering, props }">
                                <v-btn class="tw-ml-4 tw-mb-4" v-bind="props" :color="isHovering ? 'background' : 'error'" dark @click="openRevokeTrainingProposalDialog">
                                    <span>Reservierung aufheben</span>
                                </v-btn>
                            </v-hover>
                        </template>

                        <template v-if="$page.props.auth.user.is_super_admin || $page.props.auth.user.is_admin || $page.props.auth.user.is_user_multiplier">
                            <v-hover v-if="editedTrainingProposal?.kitas_users_emails && editedTrainingProposal?.kitas_users_emails.length > 0" v-slot:default="{ isHovering, props }">
                                <v-btn class="tw-ml-4 tw-mb-4" :href="`mailto:?bcc=${editedTrainingProposal?.kitas_users_emails.join(',')}`" v-bind="props" :color="isHovering ? 'accent' : 'primary'" dark>
                                    <v-icon v-bind="props" size="small" class="tw-me-2">mdi-email</v-icon>
                                    <span>Mail an KiTa(s) schreiben</span>
                                </v-btn>
                            </v-hover>
                        </template>
                    </v-col>
                </v-row>
            </v-container>

            <v-container>
                <v-row>
                    <v-col cols="12" sm="4">
                        <v-locale-provider locale="de">
                            <v-menu v-model="isFirstDateFieldOpened"
                                    :return-value.sync="firstDateField"
                                    :close-on-content-click="false"
                                    :disabled="notEditable">
                                <template v-slot:activator="{ props }">
                                    <v-text-field
                                        label="Erster Schulungstag*"
                                        class="tw-cursor-pointer"
                                        :model-value="rawFirstDateField"
                                        :error-messages="errors.first_date"
                                        prepend-icon="mdi-calendar"
                                        v-bind="props"
                                        readonly
                                        :disabled="loading"
                                    ></v-text-field>
                                </template>
                                <v-date-picker @update:modelValue="isFirstDateFieldOpened = false" v-model="firstDateField"></v-date-picker>
                            </v-menu>
                        </v-locale-provider>
                    </v-col>

                    <v-col cols="12" sm="4">
                        <v-locale-provider locale="de">
                            <v-menu v-model="isSecondDateFieldOpened"
                                    :return-value.sync="secondDateField"
                                    :close-on-content-click="false"
                                    :disabled="notEditable">
                                <template v-slot:activator="{ props }">
                                    <v-text-field
                                        label="Zweiter Schulungstag*"
                                        class="tw-cursor-pointer"
                                        :model-value="rawSecondDateField"
                                        :error-messages="errors.second_date"
                                        prepend-icon="mdi-calendar"
                                        v-bind="props"
                                        readonly
                                        :disabled="loading"
                                    ></v-text-field>
                                </template>
                                <v-date-picker @update:modelValue="isSecondDateFieldOpened = false" v-model="secondDateField"></v-date-picker>
                            </v-menu>
                        </v-locale-provider>
                    </v-col>

                    <v-col cols="12" sm="4">
                        <v-text-field
                            type="number"
                            v-model="manageForm.participant_count"
                            :error-messages="errors.participant_count"
                            label="Teilnehmerzahl*"
                            clearable
                            :readonly="notEditable"
                            :disabled="loading"
                        ></v-text-field>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12" sm="4">
                        <v-text-field
                            v-model="manageForm.street"
                            :error-messages="errors.street"
                            label="Straße*"
                            :disabled="loading"
                            clearable
                        ></v-text-field>
                    </v-col>

                    <v-col cols="12" sm="4">
                        <v-text-field
                            v-model="manageForm.house_number"
                            :error-messages="errors.house_number"
                            label="Hausnummer*"
                            :disabled="loading"
                            clearable
                        ></v-text-field>
                    </v-col>

                    <v-col cols="12" sm="4">
                        <v-text-field
                            v-model="manageForm.zip_code"
                            :error-messages="errors.zip_code"
                            label="Postleitzahl*"
                            :disabled="loading"
                            clearable
                        ></v-text-field>
                    </v-col>
                    <template v-if="$page.props.auth.user.is_super_admin || $page.props.auth.user.is_admin">
                        <v-col cols="12" sm="4">
                            <v-select
                                v-model="manageForm.multi_id"
                                :error-messages="errors.multi_id"
                                :items="multipliers"
                                item-title="full_name"
                                item-value="id"
                                label="Multiplikator*"
                                :disabled="loading || $page.props.auth.user.is_user_multiplier"
                                clearable
                            ></v-select>
                        </v-col>
                    </template>
                </v-row>

                <v-row>
                    <v-col cols="12" sm="6">
                        <v-text-field
                            v-model="manageForm.district"
                            :error-messages="errors.district"
                            label="Bezirk"
                            :disabled="loading"
                            clearable
                        ></v-text-field>
                    </v-col>

                    <v-col cols="12" sm="6">
                        <v-text-field
                            v-model="manageForm.city"
                            :error-messages="errors.city"
                            label="Stadt*"
                            :disabled="loading"
                            clearable
                        ></v-text-field>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12" sm="6">
                        <v-textarea v-model="manageForm.location"
                                    :error-messages="errors.location"
                                    label="Ort*"
                                    required
                                    :readonly="notEditable"
                                    :disabled="loading">
                        </v-textarea>
                    </v-col>

                    <v-col cols="12" sm="6">
                        <v-textarea v-model="manageForm.notes"
                                    :error-messages="errors.notes"
                                    label="Notizen"
                                    required
                                    :readonly="notEditable"
                                    :disabled="loading">
                        </v-textarea>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12" sm="6">
                        <v-hover v-if="!notEditable || ($page.props.auth.user.is_super_admin || $page.props.auth.user.is_admin)" v-slot:default="{ isHovering, props }">
                            <v-btn @click="clear" v-bind="props" :color="isHovering ? 'primary' : 'accent'">Zurücksetzen</v-btn>
                        </v-hover>
                    </v-col>

                    <v-col cols="12" sm="6" align="right">
                        <v-hover v-slot:default="{ isHovering, props }">
                            <Link :href="backRoute">
                                <v-btn class="mr-2" variant="text" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Zurück</v-btn>
                            </Link>
                        </v-hover>

                        <v-hover v-if="!notEditable || ($page.props.auth.user.is_super_admin || $page.props.auth.user.is_admin)" v-slot:default="{ isHovering, props }">
                            <v-btn-primary @click="manageTrainingProposal" v-bind="props" :color="isHovering ? 'accent' : 'primary'">
                                Speichern
                            </v-btn-primary>
                        </v-hover>
                    </v-col>
                </v-row>
            </v-container>

            <!-- Kitas table -->
            <template v-if="$page.props.auth.user.is_super_admin || $page.props.auth.user.is_admin || $page.props.auth.user.is_user_multiplier">
                <v-container>
                    <v-row class="tw-border-t-8 tw-mt-8 tw-pt-8">
                        <v-col cols="12" sm="6">
                            <h3>Teilnehmende Einrichtungen</h3>
                        </v-col>

                        <v-col cols="12" sm="6" class="text-right">
                            <template v-if="$page.props.auth.user.is_super_admin || $page.props.auth.user.is_admin">
                            <v-hover v-slot:default="{ isHovering, props }">
                                <v-btn
                                    v-bind="props"
                                    :color="isHovering ? 'accent' : 'primary'"
                                    dark
                                    :disabled="!(editedTrainingProposal.status === 'open' || editedTrainingProposal.status === 'reserved')"
                                    @click="openAddKitaToTrainingProposalDialog"
                                >
                                    <span>Einrichtung hinzufügen</span>
                                </v-btn>
                            </v-hover>
                            <v-tooltip
                                v-if="!(editedTrainingProposal.status === 'open' || editedTrainingProposal.status === 'reserved')"
                                location="top"
                            >
                                <template v-slot:activator="{ props }">
                                    <v-icon v-bind="props" class="tw-me-2">mdi-alert-circle-outline</v-icon>
                                </template>
                                <span>Einrichtungen können nur zu offenen oder reservierten Terminvorschlägen hinzugefügt werden.</span>
                            </v-tooltip>
                        </template>
                        </v-col>
                    </v-row>
                </v-container>

                <v-container>
                    <v-row>
                        <v-col cols="12" sm="4">
                            <v-text-field v-model="searchFilter"
                                          label="Name"
                                          :disabled="loading"
                                          clearable
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" sm="4">
                            <v-select
                                v-model="hasYearlyEvaluationsFilter"
                                :items="hasYearlyEvaluationsFilterValues"
                                :label="`Jährliche Rückmeldung ${new Date().getFullYear()} abgeschlossen`"
                                multiple
                                :disabled="loading"
                                clearable
                            ></v-select>
                        </v-col>

                        <v-col cols="12" sm="4">
                            <v-select
                                v-model="approvedFilter"
                                :items="approvedFilterValues"
                                label="Zugelassen"
                                multiple
                                :disabled="loading"
                                clearable
                            ></v-select>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="12" sm="6">
                            <v-select
                                v-model="operatorIdFilter"
                                :items="operators"
                                item-title="name"
                                item-value="id"
                                label="Träger"
                                multiple
                                :disabled="loading"
                                clearable
                            ></v-select>
                        </v-col>

                        <v-col cols="12" sm="6">
                            <v-text-field v-model="otherOperatorFilter"
                                          label="Sonstiger Träger"
                                          :disabled="loading"
                                          clearable
                            ></v-text-field>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="12" sm="6">
                            <v-select
                                v-model="typeFilter"
                                :items="kitaTypes"
                                label="Typ"
                                multiple
                                :disabled="loading"
                                clearable
                            ></v-select>
                        </v-col>

                        <v-col cols="12" sm="6">
                            <v-select
                                v-model="zipCodeFilter"
                                :items="zipCodes"
                                label="Postleitzahl"
                                multiple
                                :disabled="loading"
                                clearable
                            ></v-select>
                        </v-col>
                    </v-row>

                    <v-row>
                        <template v-if="$page.props.auth.user.is_super_admin || $page.props.auth.user.is_admin">
                            <v-col cols="12" class="text-right">
                                <v-hover v-if="usersEmails && usersEmails.length > 0" v-slot:default="{ isHovering, props }">
                                    <v-btn v-bind="props" :color="isHovering ? 'accent' : 'primary'" dark @click="openUsersEmailsDialog">
                                        <v-icon v-bind="props" size="small" class="tw-me-2">mdi-email</v-icon>
                                        <span>Schreibe E-Mail an Auswahl</span>
                                    </v-btn>
                                </v-hover>
                            </v-col>
                        </template>

                        <v-col cols="12">
                            <v-data-table-server
                                :items-per-page="-1"
                                :headers="headers"
                                :items="modifiedItems"
                                :search="search"
                                :loading="loading"
                                class="data-table-container data-table-container-hide-footer elevation-1"
                                item-value="name"
                                @update:options="goToPage"
                            >
                                <template v-slot:item="{ item }">
                                    <tr :data-id="item.id" :data-order="item.order">
                                        <td>{{item?.name}}</td>

                                        <td>{{item?.training_proposal_confirmed ? 'Ja' : 'Nein'}}</td>

                                        <td>{{item?.has_yearly_evaluations ? 'Ja' : 'Nein'}}</td>

                                        <td>{{item?.approved ? 'Ja' : 'Nein'}}</td>

                                        <td>{{item?.operator?.name ?? '-'}}</td>

                                        <td>{{!item?.operator?.name && item?.other_operator ? item?.other_operator : '-'}}</td>

                                        <td>{{item?.formatted_type ?? item?.type}}</td>

                                        <td>{{item?.zip_code}}</td>

                                        <td class="text-center">
                                            <template v-if="($page.props.auth.user.is_super_admin || $page.props.auth.user.is_admin)
                                                            && editedTrainingProposal.status === 'confirmation_pending'
                                                            && item?.training_proposal_confirmations?.length > 0
                                                            && !item?.training_proposal_confirmed">
                                                <v-tooltip location="top">
                                                    <template v-slot:activator="{ props }">
                                                        <v-icon
                                                            v-bind="props"
                                                            size="small"
                                                            class="tw-me-2"
                                                            @click="openConfirmKitaParticipationDialog(item)"
                                                        >
                                                            mdi-email-check-outline
                                                        </v-icon>
                                                    </template>
                                                    <span>Gesendeten Terminvorschlag für diese Einrichtung bestätigen</span>
                                                </v-tooltip>
                                            </template>
                                              <template v-if="$page.props.auth.user.is_super_admin || $page.props.auth.user.is_admin || $page.props.auth.user.is_user_multiplier">
                                                  <v-tooltip v-if="item?.users_emails.length > 0" location="top">
                                                      <template v-slot:activator="{ props }">
                                                          <a :href="`mailto:?bcc=${item?.users_emails.join(',')}`" v-bind="props">
                                                              <v-icon v-bind="props" size="small" class="tw-me-2">mdi-email</v-icon>
                                                          </a>
                                                      </template>
                                                      <span>Schreibe E-Mail</span>
                                                  </v-tooltip>
                                              </template>

                                              <template v-if="$page.props.auth.user.is_super_admin || $page.props.auth.user.is_admin">
                                                  <v-tooltip location="top">
                                                      <template v-slot:activator="{ props }">
                                                          <Link :href="`${route('kitas.show', { id: item.id })}?from=training_proposals.show;trainingProposal;${editedTrainingProposal.id}`">
                                                              <v-icon v-bind="props" size="small" class="tw-me-2">mdi-pencil</v-icon>
                                                          </Link>
                                                      </template>
                                                      <span>Einrichtung bearbeiten</span>
                                                  </v-tooltip>
                                              </template>
                                              <template v-else>
                                                  <v-tooltip location="top">
                                                      <template v-slot:activator="{ props }">
                                                          <Link :href="`${route('kitas.show', { id: item.id })}?from=training_proposals.show;trainingProposal;${editedTrainingProposal.id}`">
                                                              <v-icon v-bind="props" size="small" class="tw-me-2">mdi-eye</v-icon>
                                                          </Link>
                                                      </template>
                                                      <span>Einrichtung zeigen</span>
                                                  </v-tooltip>
                                              </template>

                                              <v-tooltip v-if="$page.props.auth.user.is_super_admin || $page.props.auth.user.is_admin" location="top">
                                                  <template v-slot:activator="{ props }">
                                                      <v-icon v-bind="props" size="small" class="tw-me-2"
                                                              @click="openRemoveKitaFromTrainingProposalDialog(item)">mdi-delete
                                                      </v-icon>
                                                  </template>
                                                  <span>Einrichtung löschen</span>
                                              </v-tooltip>
                                        </td>
                                    </tr>
                                </template>

                                <template v-slot:no-data>
                                    <div class="tw-py-6">
                                        <h3 class="tw-mb-4">Die Tabelle ist leer.</h3>
                                    </div>
                                </template>
                            </v-data-table-server>
                        </v-col>
                    </v-row>
                </v-container>
            </template>

            <!-- Popups -->
            <v-container>
                <v-row>
                    <!-- Confirm Training Proposal popup -->
                    <v-dialog v-model="confirmTrainingProposalDialog" width="80vw">
                        <v-card height="80vw">
                            <v-card-title>
                                <span class="tw-text-h5">Schulung gegenüber den KiTas bestätigen?</span>
                            </v-card-title>

                            <v-card-text>
                                <v-container>
                                    <v-row>
                                        <v-col cols="12">
                                            <template v-for="row in confirmedTrainingProposalInfo">
                                                <v-row>
                                                    <v-col cols="12" sm="2">
                                                        <span class="tw-font-bold tw-font-italic">{{ row?.label }}:</span>
                                                    </v-col>

                                                    <v-col cols="12" sm="10">
                                                        <span>{{ row?.value }}</span>
                                                    </v-col>
                                                </v-row>
                                            </template>
                                        </v-col>
                                    </v-row>

                                    <v-row v-if="trainingProposalKitas && trainingProposalKitas.length">
                                        <v-col cols="12">
                                            <p>Sind Sie sicher, dass Sie den Schulungstermin mit der/den Einrichtung(en) bestätigen wollen? Eine E-Mail wird an die verantwortliche(n) Person(en) der Einrichtung(en) versendet, um den Termin zu bestätigen.</p>
                                        </v-col>

                                        <v-col cols="12" class="tw--mt-6">
                                            <v-list>
                                                <v-list-item class="hide-details" v-for="kita in trainingProposalKitas" :key="kita.id">
<!--                                                    <v-checkbox-->
<!--                                                        class="!tw-font-bold !tw-font-italic !tw-text-black"-->
<!--                                                        :label="kita.name"-->
<!--                                                        :value="kita"-->
<!--                                                        v-model="ntfKitas"-->
<!--                                                    ></v-checkbox>-->
                                                  {{ kita.name }}
                                                </v-list-item>
                                            </v-list>
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
                                    <v-btn-primary @click="manageTrainingProposalStatus('confirmation_pending')" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Einreichen</v-btn-primary>
                                </v-hover>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>

                    <!-- Accept Training Proposal popup -->
                    <v-dialog v-model="acceptTrainingProposalDialog" width="20vw">
                        <v-card height="30vh">
                            <v-card-text>
                                <v-container>
                                    <v-row>
                                        <v-col cols="12">
                                            <p>Möchten Sie die ausgewählten Terminvorschläge wirklich akzeptieren?</p>
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
                                    <v-btn-primary @click="manageTrainingProposalStatus('reserved')" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Einreichen</v-btn-primary>
                                </v-hover>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>

                    <!-- Revoke Training Proposal popup -->
                    <v-dialog v-model="revokeTrainingProposalDialog" width="20vw">
                        <v-card height="30vh">
                            <v-card-text>
                                <v-container>
                                    <v-row>
                                        <v-col cols="12">
                                            <p>Sind Sie sicher, dass Sie die ausgewählten Terminvorschläge widerrufen möchten?</p>
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
                                    <v-btn-primary @click="manageTrainingProposalStatus('open', null)" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Einreichen</v-btn-primary>
                                </v-hover>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>

                    <!-- Add Multiplier to Training Proposal popup -->
                    <v-dialog v-model="addMultiplierToTrainingProposalDialog" width="80vw">
                        <v-card height="80vh">
                            <v-card-title>
                                <span class="tw-text-h5">Multiplikator hinzufügen</span>
                            </v-card-title>

                            <v-card-text>
                                <v-container>
                                    <v-row>
                                        <v-col cols="12">
                                            <p>Wählen Sie den Multiplikator aus, den Sie diesem Trainingsvorschlag hinzufügen möchten</p>
                                        </v-col>
                                    </v-row>

                                    <v-row>
                                        <v-col cols="12">
                                            <v-autocomplete
                                                v-model="addMultiplierToTrainingProposalForm.multi_id"
                                                :items="multipliers"
                                                :error-messages="errors.multi_id"
                                                item-title="full_name"
                                                item-value="id"
                                                label="Multiplikator"
                                                required
                                            ></v-autocomplete>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card-text>

                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-hover v-slot:default="{ isHovering, props }">
                                    <v-btn @click="close" v-bind="props" :color="isHovering ? 'accent' : 'primary'">
                                        Zurück
                                    </v-btn>
                                </v-hover>
                                <v-hover v-slot:default="{ isHovering, props }">
                                    <v-btn-primary @click="addMultiplierToTrainingProposal" v-bind="props" :color="isHovering ? 'accent' : 'primary'">
                                        Speichern
                                    </v-btn-primary>
                                </v-hover>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>

                    <!-- Add Kita to Training Proposal popup -->
                    <v-dialog v-model="addKitaToTrainingProposalDialog" width="80vw">
                        <v-card height="80vh">
                            <v-card-title>
                                <span class="tw-text-h5">Einrichtungen hinzufügen</span>
                            </v-card-title>

                            <v-card-text>
                                <v-container>
                                    <v-row>
                                        <v-col cols="12">
                                            <p>Wählen Sie die Einrichtungen aus, die Sie zu dieser Schulung hinzufügen möchten</p>
                                        </v-col>
                                    </v-row>

                                    <v-row>
                                        <v-col cols="12">
                                            <v-autocomplete
                                                v-model="addKitaToTrainingProposalForm.kitas"
                                                :items="allKitasExcludeAdded"
                                                :error-messages="errors.kitas"
                                                item-title="name"
                                                item-value="id"
                                                label="KiTa"
                                                multiple
                                                required
                                            ></v-autocomplete>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card-text>

                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-hover v-slot:default="{ isHovering, props }">
                                    <v-btn @click="close" v-bind="props" :color="isHovering ? 'accent' : 'primary'">
                                        Zurück
                                    </v-btn>
                                </v-hover>
                                <v-hover v-slot:default="{ isHovering, props }">
                                    <v-btn-primary @click="addKitaToTrainingProposal" v-bind="props" :color="isHovering ? 'accent' : 'primary'" :disabled="maxParticipantCountExceeded">
                                        Speichern
                                    </v-btn-primary>
                                </v-hover>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>

                    <!-- Remove Kita from Training Proposal popup -->
                    <v-dialog v-model="removeKitaFromTrainingProposalDialog" width="20vw">
                        <v-card height="30vh">
                            <v-card-text>
                                <v-container>
                                    <v-row>
                                        <v-col cols="12">
                                            <p>Möchten Sie die Einrichtung {{selectedKitaName}} wirklich aus der Ausbildung entfernen?</p>
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
                                    <v-btn-primary @click="removeKitaFromTrainingProposal" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Löschen</v-btn-primary>
                                </v-hover>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>

                    <!-- User emails popup -->
                    <v-col cols="12">
                        <v-dialog v-model="dialogUsersEmails" width="40vw">
                            <v-card height="80vh">
                                <v-card-title>
                                    <span class="tw-text-h5">Schreibe E-Mail an Auswahl</span>
                                </v-card-title>

                                <v-card-text>
                                    <v-container>
                                        <v-row>
                                            <v-col cols="12">
                                                <v-select
                                                    v-model="selectedUsersEmails"
                                                    :items="usersEmails"
                                                    label="Benutzer"
                                                    multiple
                                                    :disabled="loading"
                                                    clearable
                                                ></v-select>
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </v-card-text>

                                <v-card-actions>
<!--                                    <v-hover v-slot:default="{ isHovering, props }">-->
<!--                                        <v-btn @click="close" v-bind="props" :color="isHovering ? 'accent' : 'primary'">-->
<!--                                            Abbrechen-->
<!--                                        </v-btn>-->
<!--                                    </v-hover>-->
                                    <v-spacer></v-spacer>
                                    <v-hover v-slot:default="{ isHovering, props }">
                                        <v-btn-primary :href="`mailto:?bcc=${selectedUsersEmails.join(',')}`" v-bind="props" :color="isHovering ? 'accent' : 'primary'" :disabled="!selectedUsersEmails.length">
                                            Öffnen Sie den Mail-Client
                                        </v-btn-primary>
                                    </v-hover>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-col>

                    <!-- Bestätigung der Teinlahme der Einrichtung Popup-->
                    <v-dialog v-model="confirmKitaParticipationDialog" width="30vw">
                        <v-card height="30vh">
                            <v-card-title>
                                <span class="tw-text-h5">Termin für die Einrichtung bestätigen?</span>
                            </v-card-title>

                            <v-card-text>
                                <v-container>
                                    <v-row>
                                        <v-col cols="12">
                                            <p class="tw-mb-4">Bitte stimmen Sie die Bestätigung mit der Einrichtung ab!</p>
                                            <p class="tw-mb-4">
                                                Normalerweise bestätigt eine Einrichtung ihre Teilnahme an einem Schulungstermin über den Link in der an den Manager der Einrichtung versendeten E-Mail, nachdem der Termin durch den Multiplikator bestätigt wurde.
                                            </p>
                                            <p class="tw-mb-4">
                                                Mit Bestigung des Terminvorschlags wird ein Schulungstermin erzeugt!
                                            </p>
                                            <p class="tw-mb-4">
                                                Möchten Sie die Teilnahme der Einrichtung <strong>{{ selectedKitaName }}</strong> an diesem Schulungstermin bestätigen?
                                            </p>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card-text>

                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-hover v-slot:default="{ isHovering, props }">
                                    <v-btn @click="closeConfirmKitaParticipationDialog" v-bind="props" :color="isHovering ? 'accent' : 'primary'">
                                        Abbrechen
                                    </v-btn>
                                </v-hover>
                                <v-hover v-slot:default="{ isHovering, props }">
                                    <v-btn-primary @click="confirmKitaParticipation" v-bind="props" :color="isHovering ? 'accent' : 'primary'" :loading="confirmKitaParticipationForm.processing">
                                        Bestätigen
                                    </v-btn-primary>
                                </v-hover>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-row>
            </v-container>
        </div>
    </AuthenticatedLayout>
</template>
