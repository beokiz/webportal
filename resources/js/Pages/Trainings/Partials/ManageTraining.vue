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
    training: Object,
    allKitas: Array,
    trainingKitas: Array,
    errors: Object,
    multipliers: Array,
    operators: Array,
    statuses: Array,
    types: Array,
    emailMessages: Object,
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

    if (pageType === 'Trainings/Partials/ManageTraining' && newProps) {
        editedTraining.value = newProps.training;
    }
});


/*
 * Main data
 */
const currentUser = usePage().props.auth.user ?? {};  // Global info about user

const editedTraining = ref(props.training);
const errors = ref(props.errors || {});
const loading = ref(false);
const selectedKitaName = ref(null);
const dialog = ref(false);
const confirmTrainingDialog = ref(false);
const completeTrainingDialog = ref(false);
const cancelTrainingDialog = ref(false);
const addKitaToTrainingDialog = ref(false);
const removeKitaFromTrainingDialog = ref(false);

const isFirstDateFieldOpened = ref(false);
const isSecondDateFieldOpened = ref(false);

const rawFirstDateField = ref(prepareDate(props.training.first_date));
const rawSecondDateField = ref(prepareDate(props.training.second_date));
const firstDateField = ref(new Date(props.training.first_date).toString());
const secondDateField = ref(new Date(props.training.second_date).toString());

const currentTrainingKitas = ref(props.trainingKitas ?? []);
const ntfKitas = ref([]);

const completedTrainingInfo = ref([
    {
        label: 'Erster Schukungstag',
        value: `${prepareDate(editedTraining.value?.first_date)} ${editedTraining.value?.first_date_start_and_end_time}`,
    },
    {
        label: 'Zweiter Schulungstag',
        value: `${prepareDate(editedTraining.value?.second_date)} ${editedTraining.value?.second_date_start_and_end_time}`,
    },
    {
        label: 'Ort',
        value: editedTraining.value?.location,
    },
    {
        label: 'Typ',
        value: editedTraining.value?.type,
    },
    {
        label: 'Teinhemheranzahl',
        value: editedTraining.value?.participant_count,
    },
]);

watch(dialog, (val) => {
    if (!val) {
        close();
    }
});

watch(firstDateField, (val) => {
    rawFirstDateField.value = prepareDate(val);
});

watch(secondDateField, (val) => {
    rawSecondDateField.value = prepareDate(val);
});

// Methods
const close = () => {
    dialog.value = false;
    selectedKitaName.value = null;
    addKitaToTrainingDialog.value = false;
    removeKitaFromTrainingDialog.value = false;
    confirmTrainingDialog.value = false;
    completeTrainingDialog.value = false;
    cancelTrainingDialog.value = false;

    manageStatusForm.reset();
    manageStatusForm.clearErrors();
    addKitaToTrainingForm.reset();
    addKitaToTrainingForm.clearErrors();
    removeKitaFromTrainingForm.reset();
    removeKitaFromTrainingForm.clearErrors();

    errors.value = {};
};

const clear = () => {
    manageForm.reset();
    manageForm.clearErrors();

    manageStatusForm.reset();
    manageStatusForm.clearErrors();
    addKitaToTrainingForm.reset();
    addKitaToTrainingForm.clearErrors();
    removeKitaFromTrainingForm.reset();
    removeKitaFromTrainingForm.clearErrors();
};

const manageForm = useForm({
    id: editedTraining.value?.id,
    multi_id: editedTraining.value?.multi_id,
    first_date: editedTraining.value?.first_date,
    first_date_start_and_end_time: editedTraining.value?.first_date_start_and_end_time,
    second_date: editedTraining.value?.second_date,
    second_date_start_and_end_time: editedTraining.value?.second_date_start_and_end_time,
    location: editedTraining.value?.location,
    max_participant_count: editedTraining.value?.max_participant_count,
    // participant_count: currentParticipantCount,
    type: editedTraining.value?.type,
    // status: editedTraining.value?.status,
    notes: editedTraining.value?.notes,
});

const manageTraining = async () => {
    manageForm.processing = true;

    manageForm.first_date = firstDateField.value ? new Date(firstDateField.value).toLocaleString() : null;
    manageForm.second_date = secondDateField.value ? new Date(secondDateField.value).toLocaleString() : null;

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

    manageForm.put(route('trainings.update', {training: manageForm.id}), formOptions);
};


const openConfirmTrainingDialog = () => {
  console.log([props.trainingKitas, currentTrainingKitas])
    confirmTrainingDialog.value = true;
};

const openCompleteTrainingDialog = () => {
    completeTrainingDialog.value = true;
};

const openCancelTrainingDialog = () => {
    cancelTrainingDialog.value = true;
};

const manageStatusForm = useForm({
    id: editedTraining.value?.id,
    status: null,
});

const manageTrainingStatus = async (status) => {
    manageStatusForm.processing = true;

    manageStatusForm.status = status;

    let formOptions = {
        preserveState: false,
        onSuccess: (page) => {
            // Open local mail client with kita managers emails
            if (status === 'confirmed') {
                let userEmails = [];

                ntfKitas.value.map(kita => {
                    kita?.users_emails.map(email => {
                        userEmails.push(email);
                    });
                });

                userEmails = userEmails.filter((value, index, array) => array.indexOf(value) === index);

                if (userEmails && userEmails.length) {
                    // Create a fake link
                    const link = document.createElement('a');

                    // Prepare email content
                    const subject = props.emailMessages[status]?.subject;
                    const body = props.emailMessages[status]?.body;

                    // Set href before clicking
                    link.href = `mailto:?bcc=${userEmails.join(',')}&subject=${subject}&body=${body}`;

                    // Append the link to the document
                    document.body.appendChild(link);

                    // Programmatically click the link
                    link.click();

                    // Remove the link from the document
                    document.body.removeChild(link);
                }

                ntfKitas.value = [];
            }

            close();
        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            manageStatusForm.processing = false;
        },
    };

    manageStatusForm.put(route('trainings.update', {training: manageStatusForm.id}), formOptions);
};

/*
 * Add / remove Kita from Training
 */
const openAddKitaToTrainingDialog = () => {
    addKitaToTrainingDialog.value = true;
};

const addKitaToTrainingForm = useForm({
    kitas: [],
});

const allKitasExcludeAdded = computed(() => {
    let trainingKitasIds = currentTrainingKitas.value.map(kita => kita.id);

    return props.allKitas.filter(kita => !trainingKitasIds.includes(kita.id));
});

const maxParticipantCountExceeded = computed(() => {
    let selectedKitas = props.allKitas.filter(kita => addKitaToTrainingForm.kitas.includes(kita.id)) ?? [];
    let newParticipantCount = selectedKitas.map(kita => kita.num_pedagogical_staff).reduce((partialSum, a) => partialSum + a, 0)

    let result = (newParticipantCount + editedTraining.value?.participant_count) > editedTraining.value?.max_participant_count;

    if (result) {
        errors.value.kitas = `Sie haben die maximale Teilnehmerzahl (${newParticipantCount + editedTraining.value?.participant_count}/${editedTraining.value?.max_participant_count}) überschritten.`;
    } else {
        delete errors.value?.kitas;
    }

    return result;
});

const addKitaToTraining = async () => {
    addKitaToTrainingForm.processing = true;

    addKitaToTrainingForm.post(route('trainings.add_kitas', {training: props.training.id}), {
        onSuccess: (page) => {
            close();
        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            addKitaToTrainingForm.processing = false;
        },
    });
};

const openRemoveKitaFromTrainingDialog = (item) => {
    selectedKitaName.value = item.name
    removeKitaFromTrainingForm.kita = item.id;
    removeKitaFromTrainingDialog.value = true;
};

const removeKitaFromTrainingForm = useForm({
    kita: null,
});

const removeKitaFromTraining = async () => {
    removeKitaFromTrainingForm.processing = true;

    let formOptions = {
        onSuccess: (page) => {
            close();
        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            removeKitaFromTrainingForm.processing = false;
        },
    };

    removeKitaFromTrainingForm.post(route('trainings.remove_kita', {id: props.training.id}), formOptions);
};



/*
 * Kitas tables
 */
const modifyItems = (items) => {
    return items.map(item => {
        const modifiedItem = {...item};
        for (const key in modifiedItem) {
            if (modifiedItem[key] === null || modifiedItem[key] === undefined) {
                modifiedItem[key] = '-';
            }
        }
        return modifiedItem;
    });
};

const headers = [
    { title: 'Name', key: 'name', width: '15%', sortable: true },
    { title: `Jährliche Rückmeldung ${new Date().getFullYear()} abgeschlossen`, key: 'has_yearly_evaluations', width: '35%', sortable: true },
    { title: 'Zugelassen', key: 'approved', width: '10%', sortable: true },
    { title: 'Träger', key: 'operator_id', width: '10%', sortable: true },
    { title: 'Typ', key: 'type', width: '10%', sortable: true },
    { title: 'Postleitzahl', key: 'zip_code', width: '10%', sortable: true },
    { title: 'Aktion', key: 'actions', width: '10%', sortable: false, align: 'center' },
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
    return modifyItems(currentTrainingKitas.value);
});

const allFiltersEmpty = computed(() => {
    return searchFilter.value === null && hasYearlyEvaluationsFilter.value === null && approvedFilter.value === null && operatorIdFilter.value === null && typeFilter.value === null && zipCodeFilter.value === null;
});

const someFiltersNotEmpty = computed(() => {
    return searchFilter.value !== null || hasYearlyEvaluationsFilter.value !== null || approvedFilter.value !== null || operatorIdFilter.value !== null || typeFilter.value !== null || zipCodeFilter.value !== null;
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

        if (typeFilter.value) {
            data.type = typeFilter.value;
        }

        if (zipCodeFilter.value) {
            data.zip_code = zipCodeFilter.value;
        }

        await router.get(route(route().current(), {training: manageForm.id}), data, {
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
    <Head :title="`Verwalte Termin Eigenschaften`"/>

    <AuthenticatedLayout :errors="errors">
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">Verwalte Termin Eigenschaften</h2>
        </template>

        <div class="tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8">
            <v-container>
                <v-row>
                    <v-col cols="12" sm="3">
                        <h3>Termin</h3>
                    </v-col>

                    <v-col cols="12" sm="9" class="text-right">
                        <template v-if="editedTraining.status === 'planned'">
                            <v-hover v-slot:default="{ isHovering, props }">
                                <v-btn class="tw-ml-4 tw-mb-4" v-bind="props" :color="isHovering ? 'accent' : 'primary'" dark @click="openConfirmTrainingDialog">
                                    <span>Schulungstermin bestätigen</span>
                                </v-btn>
                            </v-hover>
                        </template>
                        <template v-if="editedTraining.status === 'confirmed'">
                            <v-hover v-slot:default="{ isHovering, props }">
                                <v-btn class="tw-ml-4 tw-mb-4" v-bind="props" :color="isHovering ? 'background' : 'success'" dark @click="openCompleteTrainingDialog">
                                    <span>Training abschließen und Einrichtungen zulassen</span>
                                </v-btn>
                            </v-hover>
                        </template>
                        <template v-if="editedTraining.status !== 'completed' && editedTraining.status !== 'cancelled' && ($page.props.auth.user.is_super_admin || $page.props.auth.user.is_admin)">
                            <v-hover v-slot:default="{ isHovering, props }">
                                <v-btn class="tw-ml-4 tw-mb-4" v-bind="props" :color="isHovering ? 'background' : 'error'" dark @click="openCancelTrainingDialog">
                                    <span>Training abbrechen</span>
                                </v-btn>
                            </v-hover>
                        </template>
                    </v-col>
                </v-row>
            </v-container>

            <v-container>
                <v-row>
                    <v-col cols="12" sm="6">
                        <v-locale-provider locale="de">
                            <v-menu v-model="isFirstDateFieldOpened"
                                    :return-value.sync="firstDateField"
                                    :close-on-content-click="false">
                                <template v-slot:activator="{ props }">
                                    <v-text-field
                                        label="Erster Schulungstag*"
                                        class="tw-cursor-pointer"
                                        :model-value="rawFirstDateField"
                                        :error-messages="errors.first_date"
                                        prepend-icon="mdi-calendar"
                                        readonly
                                        v-bind="props"
                                        :disabled="loading || $page.props.auth.user.is_user_multiplier"
                                    ></v-text-field>
                                </template>
                                <v-date-picker @update:modelValue="isFirstDateFieldOpened = false" v-model="firstDateField"></v-date-picker>
                            </v-menu>
                        </v-locale-provider>
                    </v-col>

                    <v-col cols="12" sm="6">
                        <v-label class="tw-mt-6 tw-mr-2">Zeitraum erster Schulungstag*</v-label>

                        <vue-timepicker v-model="manageForm.first_date_start_and_end_time"
                                        :hideClearButton="true"
                                        :hourLabel="'Std.'"
                                        :minuteLabel="'Protokoll'"
                                        :disabled="loading || $page.props.auth.user.is_user_multiplier">
                        </vue-timepicker>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12" sm="6">
                        <v-locale-provider locale="de">
                            <v-menu v-model="isSecondDateFieldOpened"
                                    :return-value.sync="secondDateField"
                                    :close-on-content-click="false">
                                <template v-slot:activator="{ props }">
                                    <v-text-field
                                        label="Zweiter Schulungstag*"
                                        class="tw-cursor-pointer"
                                        :model-value="rawSecondDateField"
                                        :error-messages="errors.second_date"
                                        prepend-icon="mdi-calendar"
                                        readonly
                                        v-bind="props"
                                        :disabled="loading || $page.props.auth.user.is_user_multiplier"
                                    ></v-text-field>
                                </template>
                                <v-date-picker @update:modelValue="isSecondDateFieldOpened = false" v-model="secondDateField"></v-date-picker>
                            </v-menu>
                        </v-locale-provider>
                    </v-col>

                    <v-col cols="12" sm="6">
                        <v-label class="tw-mt-6 tw-mr-2">Zeitraum zweiter Schulungstag*</v-label>

                        <vue-timepicker v-model="manageForm.second_date_start_and_end_time"
                                        :hideClearButton="true"
                                        :hourLabel="'Std.'"
                                        :minuteLabel="'Protokoll'"
                                        :disabled="loading || $page.props.auth.user.is_user_multiplier">
                        </vue-timepicker>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12" sm="2">
                        <v-text-field
                            type="number"
                            v-model="editedTraining.participant_count"
                            :error-messages="errors.participant_count"
                            label="Teilnehmerzahl*"
                            :disabled="loading || $page.props.auth.user.is_user_multiplier"
                            readonly
                        ></v-text-field>
                    </v-col>

                    <v-col cols="12" sm="1" class="tw-text-center">
                        <span class="tw-inline-block tw-mt-4">von</span>
                    </v-col>

                    <v-col cols="12" sm="2">
                        <v-text-field
                            type="number"
                            v-model="manageForm.max_participant_count"
                            :error-messages="errors.max_participant_count"
                            label="Max. Teilnehmerzahl*"
                            clearable
                            :disabled="loading || $page.props.auth.user.is_user_multiplier"
                        ></v-text-field>
                    </v-col>

                    <v-col cols="12" sm="3">
                        <v-select
                            v-model="manageForm.type"
                            :error-messages="errors.type"
                            :items="types"
                            item-title="title"
                            item-value="value"
                            label="Typ*"
                            :disabled="loading || $page.props.auth.user.is_user_multiplier"
                            clearable
                        ></v-select>
                    </v-col>

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

<!--                                            <v-col cols="12" sm="3">-->
<!--                                              <v-select-->
<!--                                                  v-model="manageForm.status"-->
<!--                                                  :error-messages="errors.status"-->
<!--                                                  :items="statuses"-->
<!--                                                  item-title="title"-->
<!--                                                  item-value="value"-->
<!--                                                  label="Status*"-->
<!--                                                  :disabled="loading"-->
<!--                                                  clearable-->
<!--                                              ></v-select>-->
<!--                                            </v-col>-->
                </v-row>

                <v-row>
                    <v-col cols="12" sm="6">
                        <v-textarea v-model="manageForm.location"
                                    :error-messages="errors.location"
                                    label="Ort*"
                                    required
                                    :disabled="$page.props.auth.user.is_user_multiplier">
                        </v-textarea>
                    </v-col>

                    <v-col cols="12" sm="6">
                        <v-textarea v-model="manageForm.notes"
                                    :error-messages="errors.notes"
                                    label="Notizen"
                                    required
                                    :disabled="$page.props.auth.user.is_user_multiplier">
                        </v-textarea>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12" sm="6">
                        <v-hover v-if="$page.props.auth.user.is_super_admin || $page.props.auth.user.is_admin" v-slot:default="{ isHovering, props }">
                            <v-btn @click="clear" v-bind="props" :color="isHovering ? 'primary' : 'accent'">Zurücksetzen</v-btn>
                        </v-hover>
                    </v-col>

                    <v-col cols="12" sm="6" align="right">
                        <v-hover v-slot:default="{ isHovering, props }">
                            <Link :href="route('trainings.index')">
                                <v-btn class="mr-2" variant="text" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Zurück</v-btn>
                            </Link>
                        </v-hover>

                        <v-hover v-if="$page.props.auth.user.is_super_admin || $page.props.auth.user.is_admin" v-slot:default="{ isHovering, props }">
                            <v-btn-primary @click="manageTraining" v-bind="props"
                                           :color="isHovering ? 'accent' : 'primary'">Speichern
                            </v-btn-primary>
                        </v-hover>
                    </v-col>
                </v-row>
            </v-container>

            <!-- Kitas table -->
            <template v-if="$page.props.auth.user.is_super_admin || $page.props.auth.user.is_admin">
                <v-container>
                    <v-row class="tw-border-t-8 tw-mt-8 tw-pt-8">
                        <v-col cols="12" sm="6">
                            <h3>Teilnehmende Einrichtungen</h3>
                        </v-col>

                        <v-col cols="12" sm="6" class="text-right">
                            <v-hover v-slot:default="{ isHovering, props }">
                                <v-btn v-bind="props" :color="isHovering ? 'accent' : 'primary'" dark @click="openAddKitaToTrainingDialog">
                                    <span>Einrichtung hinzufügen</span>
                                </v-btn>
                            </v-hover>
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
                        <v-col cols="12" sm="4">
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

                        <v-col cols="12" sm="4">
                            <v-select
                                v-model="typeFilter"
                                :items="kitaTypes"
                                label="Typ"
                                multiple
                                :disabled="loading"
                                clearable
                            ></v-select>
                        </v-col>

                        <v-col cols="12" sm="4">
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
                        <v-col cols="12" class="text-right">
                            <v-hover v-if="usersEmails && usersEmails.length > 0" v-slot:default="{ isHovering, props }">
                                <v-btn v-bind="props" :color="isHovering ? 'accent' : 'primary'" dark @click="openUsersEmailsDialog">
                                    <v-icon v-bind="props" size="small" class="tw-me-2">mdi-email</v-icon>
                                    <span>Schreibe E-Mail an Auswahl</span>
                                </v-btn>
                            </v-hover>
                        </v-col>

                        <v-col cols="12">
                            <v-data-table-server
                                :items-per-page="-1"
                                :headers="headers"
                                :items="modifiedItems"
                                :search="search"
                                v-sortable-data-table
                                :loading="loading"
                                class="data-table-container data-table-container-hide-footer elevation-1"
                                item-value="name"
                                @update:options="goToPage"
                            >
                                <template v-slot:item="{ item }">
                                    <tr :data-id="item.selectable.id" :data-order="item.selectable.order">
                                        <td>{{item.selectable?.name}}</td>

                                        <td>{{item.selectable?.has_yearly_evaluations ? 'Ja' : 'Nein'}}</td>

                                        <td>{{item.selectable?.approved ? 'Ja' : 'Nein'}}</td>

                                        <td>{{item.selectable?.operator?.name ?? '-'}}</td>

                                        <td>{{item.selectable?.formatted_type ?? item.selectable?.type}}</td>

                                        <td>{{item.selectable?.zip_code}}</td>

                                        <td class="text-center">
                                              <template v-if="$page.props.auth.user.is_super_admin">
                                                  <v-tooltip v-if="item.selectable?.approved && item.selectable?.users_emails.length > 0" location="top">
                                                      <template v-slot:activator="{ props }">
                                                          <a :href="`mailto:?bcc=${item.selectable?.users_emails.join(',')}`" v-bind="props">
                                                              <v-icon v-bind="props" size="small" class="tw-me-2">mdi-email</v-icon>
                                                          </a>
                                                      </template>
                                                      <span>Schreibe E-Mail</span>
                                                  </v-tooltip>
                                              </template>

                                              <v-tooltip location="top">
                                                  <template v-slot:activator="{ props }">
                                                      <Link :href="route('kitas.show', { id: item.selectable.id })">
                                                          <v-icon v-bind="props" size="small" class="tw-me-2">mdi-pencil</v-icon>
                                                      </Link>
                                                  </template>
                                                  <span>Einrichtung bearbeiten</span>
                                              </v-tooltip>

                                              <v-tooltip v-if="!$page.props.auth.user.is_manager && !$page.props.auth.user.is_user_multiplier" location="top">
                                                  <template v-slot:activator="{ props }">
                                                      <v-icon v-bind="props" size="small" class="tw-me-2"
                                                              @click="openRemoveKitaFromTrainingDialog(item.raw)">mdi-delete
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
                    <!-- Confirm Training popup -->
                    <v-dialog v-model="confirmTrainingDialog" width="80vw">
                        <v-card height="80vw">
                            <v-card-title>
                                <span class="tw-text-h5">Schulung gegenüber den Kitas bestätigen?</span>
                            </v-card-title>

                            <v-card-text>
                                <v-container>
                                    <v-row>
                                        <v-col cols="12">
                                            <template v-for="row in completedTrainingInfo">
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

                                    <v-row v-if="currentTrainingKitas">
                                        <v-col cols="12">
                                            <p class="mb-4">Sind Sie sich sicher, dass Sie die Termine gegenüber den folgenden Kitas bestätigen wollen? Im Folgenden gibt es individuelle E-Mail-Vorschläge für jede Kita.</p>
                                            <p>Bitte klicken Sie auf den Namen der Kita, um diesen zu erhalten.</p>
                                        </v-col>

                                        <v-col cols="12" class="tw--mt-6">
                                            <v-list>
                                                <v-list-item class="hide-details" v-for="kita in currentTrainingKitas" :key="kita.id">
                                                    <v-checkbox
                                                        class="!tw-font-bold !tw-font-italic !tw-text-black"
                                                        :label="kita.name"
                                                        :value="kita"
                                                        v-model="ntfKitas"
                                                    ></v-checkbox>
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
                                    <v-btn-primary @click="manageTrainingStatus('confirmed')" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Löschen</v-btn-primary>
                                </v-hover>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>

                    <!-- Complete Training popup -->
                    <v-dialog v-model="completeTrainingDialog" width="20vw">
                        <v-card height="30vh">
                            <v-card-text>
                                <v-container>
                                    <v-row>
                                        <v-col cols="12">
                                            <p>Möchten Sie die ausgewählte Ausbildung wirklich absolvieren?</p>
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
                                    <v-btn-primary @click="manageTrainingStatus('completed')" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Löschen</v-btn-primary>
                                </v-hover>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>

                    <!-- Cancel Training popup -->
                    <v-dialog v-model="cancelTrainingDialog" width="20vw">
                        <v-card height="30vh">
                            <v-card-text>
                                <v-container>
                                    <v-row>
                                        <v-col cols="12">
                                            <p>Sind Sie sicher, dass Sie die ausgewählte Schulung stornieren möchten?</p>
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
                                    <v-btn-primary @click="manageTrainingStatus('cancelled')" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Löschen</v-btn-primary>
                                </v-hover>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>

                    <!-- Add Kita to Training popup -->
                    <v-dialog v-model="addKitaToTrainingDialog" width="80vw">
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
                                                v-model="addKitaToTrainingForm.kitas"
                                                :items="allKitasExcludeAdded"
                                                :error-messages="errors.kitas"
                                                item-title="name"
                                                item-value="id"
                                                label="Kita"
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
                                    <v-btn-primary @click="addKitaToTraining" v-bind="props" :color="isHovering ? 'accent' : 'primary'" :disabled="maxParticipantCountExceeded">
                                        Speichern
                                    </v-btn-primary>
                                </v-hover>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>

                    <!-- Remove Kita from Training popup -->
                    <v-dialog v-model="removeKitaFromTrainingDialog" width="20vw">
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
                                    <v-btn-primary @click="removeKitaFromTraining" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Löschen</v-btn-primary>
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
                                    <v-hover v-slot:default="{ isHovering, props }">
                                        <v-btn @click="close" v-bind="props" :color="isHovering ? 'accent' : 'primary'">
                                            Abbrechen
                                        </v-btn>
                                    </v-hover>
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
                </v-row>
            </v-container>
        </div>
    </AuthenticatedLayout>
</template>
