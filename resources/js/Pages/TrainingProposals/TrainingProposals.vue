<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import { computed, ref, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, useForm, usePage, router, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { tr } from 'vuetify/locale';
import { debounce } from 'lodash';
import { formatDate, formatDateTime, prepareDate, getTrainingProposalStatusIcon } from '@/Composables/common.js';

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
    userTrainingProposals: Array,
    kitas: Array,
    multipliers: Array,
    statuses: Array,
});

/*
 * Inertia events handling
 */
// For page reloading on pagination or searching
Inertia.on('success', (event) => {
    let newProps = event.detail.page.props;
    let pageType = event.detail.page.component;

    if (pageType === 'TrainingProposals/TrainingProposals' && newProps) {
        currentPage.value = newProps.currentPage;
        perPage.value = newProps.perPage;
        orderBy.value = newProps.orderBy;
        sort.value = newProps.sort;
        totalItems.value = newProps.total;
        lastPage.value = newProps.lastPage;
        // searchFilter.value = newProps.filters.search ?? null;
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
const locationFilter = ref(props.filters.location ?? null);
const participantCountFilter = ref(props.filters.participant_count ?? null);
const typeFilter = ref(props.filters.type ?? null);
const multiIdFilter = ref(props.filters.multi_id ?? null);
const statusFilter = ref(props.filters.status ?? null);
const kitaIdFilter = ref(props.filters.with_kitas ?? null);
const search = ref('');
const errors = ref(props.errors || {});

const isFirstDateFilterOpened = ref(false);
const isSecondDateFilterOpened = ref(false);
const isFirstDateFieldOpened = ref(false);
const isSecondDateFieldOpened = ref(false);

const loading = ref(false);
const dialog = ref(false);
const acceptTrainingProposalDialog = ref(false);
const revokeTrainingProposalDialog = ref(false);
const dialogDeleteTrainingProposal = ref(false);
const deletingItemName = ref(null);

const rawFirstDateFilter = ref(null);
const rawSecondDateFilter = ref(null);
const firstDateFilter = ref(null);
const secondDateFilter = ref(null);

const rawFirstDateField = ref(null);
const rawSecondDateField = ref(null);
const firstDateField = ref(null);
const secondDateField = ref(null);

const selectedTrainingProposal = ref(null);
const selectedTrainingProposalKitas = ref([]);

const mainTableHeaders = [
    { title: 'Erster Schulungstag', key: 'first_date', width: '4%', sortable: true },
    { title: 'Zweiter Schulungstag', key: 'second_date', width: '4%', sortable: true },
    { title: 'Ort', key: 'location', width: '25%', sortable: true },
    { title: 'Teilnehmer ', key: 'participant_count', width: '5%', sortable: true },
    { title: 'KiTa', key: 'kitas_list', width: '26%', sortable: false },
    { title: 'Status', key: 'status', width: '5%', sortable: true },
    { title: 'Geändert am', key: 'updated_at', width: '16%', sortable: true },
    { title: 'Aktion', key: 'actions', width: '15%', sortable: false, align: 'center' },
];

const additionalTableHeaders = [
    { title: 'Erster Schulungstag', key: 'first_date', width: '4%', sortable: false },
    { title: 'Zweiter Schulungstag', key: 'second_date', width: '4%', sortable: false },
    { title: 'Ort', key: 'location', width: '25%', sortable: false },
    { title: 'Teilnehmer ', key: 'participant_count', width: '5%', sortable: false },
    { title: 'KiTa', key: 'kitas_list', width: '26%', sortable: false },
    { title: 'Status', key: 'status', width: '5%', sortable: false },
    { title: 'Geändert am', key: 'updated_at', width: '16%', sortable: false },
    { title: 'Aktion', key: 'actions', width: '15%', sortable: false, align: 'center' },
];


// Computed
const modifyItems = (items) => {
    return items?.map(item => {
        const modifiedItem = {...item};
        for (const key in modifiedItem) {
            if (modifiedItem[key] === null || modifiedItem[key] === undefined) {
                modifiedItem[key] = '-';
            }
        }
        return modifiedItem;
    });
};

const modifiedItems = computed(() => {
    return modifyItems(props.items);
});

const modifiedUserTrainingProposals = computed(() => {
    return modifyItems(props.userTrainingProposals);
});

const allFiltersEmpty = computed(() => {
    return firstDateFilter.value === null &&
        secondDateFilter.value === null &&
        locationFilter.value === null &&
        participantCountFilter.value === null &&
        typeFilter.value === null &&
        multiIdFilter.value === null &&
        statusFilter.value === null &&
        kitaIdFilter.value === null;
});

const someFiltersNotEmpty = computed(() => {
    return firstDateFilter.value !== null ||
        secondDateFilter.value !== null ||
        locationFilter.value !== null ||
        participantCountFilter.value !== null ||
        typeFilter.value !== null ||
        multiIdFilter.value !== null ||
        statusFilter.value !== null ||
        kitaIdFilter.value !== null;
});

// Watch
watch(dialog, (val) => {
    if (!val) {
        close();
    }
});

watch(firstDateFilter, (val) => {
    rawFirstDateFilter.value = val ? prepareDate(val) : null;
    triggerSearch();
});

watch(secondDateFilter, (val) => {
    rawSecondDateFilter.value = val ? prepareDate(val) : null;
    triggerSearch();
});

watch(rawFirstDateFilter, (val) => {
    triggerSearch();
});

watch(rawSecondDateFilter, (val) => {
    triggerSearch();
});

watch(locationFilter, debounce((val) => {
    triggerSearch();
}, 500));

watch(participantCountFilter, debounce((val) => {
    triggerSearch();
}, 500));

watch(typeFilter, (val) => {
    triggerSearch();
});

watch(multiIdFilter, (val) => {
    triggerSearch();
});

watch(statusFilter, (val) => {
    triggerSearch();
});

watch(kitaIdFilter, debounce((val) => {
    triggerSearch();
}, 500));

watch(firstDateField, (val) => {
    rawFirstDateField.value = val ? prepareDate(val) : null;
});

watch(secondDateField, (val) => {
    rawSecondDateField.value = val ? prepareDate(val) : null;
});

const triggerSearch = () => {
    loading.value = true;
    search.value = String(Date.now());
};

// Methods
const clearFirstDateFilter = () => {
    firstDateFilter.value = null;
    rawFirstDateFilter.value = null;
};
const clearSecondDateFilter = () => {
    secondDateFilter.value = null;
    rawSecondDateFilter.value = null;
};

const goToPage = async ({ page, itemsPerPage, sortBy, clearFilters }) => {
    if (clearFilters) {
        firstDateFilter.value = null;
        secondDateFilter.value = null;
        locationFilter.value = null;
        participantCountFilter.value = null;
        typeFilter.value = null;
        multiIdFilter.value = null;
        statusFilter.value = null;
        kitaIdFilter.value = null;
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

        // Apply filters
        if (firstDateFilter.value) {
            data.first_date = firstDateFilter.value.toLocaleString();
        }

        if (secondDateFilter.value) {
            data.second_date = secondDateFilter.value.toLocaleString();
        }

        if (locationFilter.value) {
            data.location = locationFilter.value;
        }

        if (participantCountFilter.value) {
            data.participant_count = participantCountFilter.value;
        }

        if (typeFilter.value) {
            data.type = typeFilter.value;
        }

        if (multiIdFilter.value) {
            data.with_multipliers = multiIdFilter.value;
        }

        if (statusFilter.value) {
            data.status = statusFilter.value;
        }

        if (kitaIdFilter.value) {
            data.with_kitas = kitaIdFilter.value;
        }

        await router.get(route(route().current()), data, {
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

const openDeleteTrainingProposalDialog = (item) => {
    deletingItemName.value = item.name
    deleteForm.id = item.id;
    dialogDeleteTrainingProposal.value = true
};

const deleteForm = useForm({
    id: null,
});

const deleteTrainingProposal = async () => {
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

    deleteForm.delete(route('training_proposals.destroy', { id: deleteForm.id }), formOptions);
};

const close = () => {
    dialog.value = false;
    acceptTrainingProposalDialog.value = false;
    revokeTrainingProposalDialog.value = false;

    manageForm.reset();
    manageForm.clearErrors();
    manageStatusForm.reset();
    manageStatusForm.clearErrors();

    rawFirstDateField.value = null;
    rawSecondDateField.value = null;
    firstDateField.value = null;
    secondDateField.value = null;

    selectedTrainingProposal.value = null;
    selectedTrainingProposalKitas.value = null;

    errors.value = {};
};

const clear = () => {
    manageForm.reset();
    manageForm.clearErrors();

    rawFirstDateField.value = null;
    rawSecondDateField.value = null;
    firstDateField.value = null;
    secondDateField.value = null;
};


const manageForm = useForm({
    // multi_id: null,
    first_date: null,
    second_date: null,
    location: null,
    participant_count: null,
    // status: null,
    street: null,
    house_number: null,
    zip_code: null,
    city: null,
    district: null,
    notes: null,
});

const manageTrainingProposal = async () => {
    manageForm.processing = true;

    manageForm.first_date = firstDateField.value ? new Date(new Date(firstDateField.value).setHours(12, 0, 0, 0)).toISOString() : null;
    manageForm.second_date = secondDateField.value ? new Date(new Date(secondDateField.value).setHours(12, 0, 0, 0)).toISOString() : null;

    manageForm.post(route('training_proposals.store'), {
        onSuccess: (page) => {
            close();
        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            manageForm.processing = false;
        },
    });
};


const openChangeTrainingProposalStatusDialog = (item, status) => {
    switch (status) {
        case 'reserved':
            acceptTrainingProposalDialog.value = true;
            break;
        case 'open':
            revokeTrainingProposalDialog.value = true;
            break;
    }

    manageStatusForm.id = item?.id;
    manageStatusForm.status = status;

    selectedTrainingProposal.value = item;
    selectedTrainingProposalKitas.value = item?.kitas;
};

const manageStatusForm = useForm({
    id: null,
    multi_id: null,
    status: null,
});

const manageTrainingProposalStatus = async (status, multi_id) => {
    manageStatusForm.processing = true;

    manageStatusForm.multi_id = multi_id;
    manageStatusForm.status = status;

    manageStatusForm.put(route('training_proposals.update', { trainingProposal: manageStatusForm.id }), {
        onSuccess: (page) => {
            close();
        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            manageStatusForm.processing = false;
        },
    });
};
</script>

<template>
    <Head title="Terminvorschläge" />

    <AuthenticatedLayout :errors="errors">
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">Terminvorschläge</h2>

            <div class="tw-flex tw-items-center tw-justify-end">
                <v-hover v-if="$page.props.auth.user.is_super_admin || $page.props.auth.user.is_admin" v-slot:default="{ isHovering, props }">
                    <v-btn v-bind="props" :color="isHovering ? 'accent' : 'primary'" dark>
                        Anlegen

                        <!-- Create item popup -->
                        <v-dialog v-model="dialog" activator="parent" width="80vw">
                            <v-card height="80vh">
                                <v-card-title>
                                    <span class="tw-text-h5">Verwalte Terminvorschläge</span>
                                </v-card-title>

                                <v-card-text>
                                    <v-container>
                                        <v-row>
                                            <v-col cols="12" sm="4">
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
                                                                clearable
                                                                v-bind="props"
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
                                                            :close-on-content-click="false">
                                                        <template v-slot:activator="{ props }">
                                                            <v-text-field
                                                                label="Zweiter Schulungstag*"
                                                                class="tw-cursor-pointer"
                                                                :model-value="rawSecondDateField"
                                                                :error-messages="errors.second_date"
                                                                prepend-icon="mdi-calendar"
                                                                readonly
                                                                clearable
                                                                v-bind="props"
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
                                                    :disabled="loading"
                                                    clearable
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
                                                            :disabled="loading"
                                                            required>
                                                </v-textarea>
                                            </v-col>

                                            <v-col cols="12" sm="6">
                                                <v-textarea v-model="manageForm.notes"
                                                            :error-messages="errors.notes"
                                                            label="Notizen"
                                                            :disabled="loading"
                                                            required>
                                                </v-textarea>
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
                                        <v-btn-primary @click="manageTrainingProposal" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Speichern</v-btn-primary>
                                    </v-hover>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-btn>
                </v-hover>
            </div>

            <!-- Delete item popup -->
            <v-dialog v-if="$page.props.auth.user.is_super_admin || $page.props.auth.user.is_admin" v-model="dialogDeleteTrainingProposal" width="20vw">
                <v-card height="30vh">
                    <v-card-text>
                        <v-container>
                            <v-row>
                                <v-col cols="12">
                                    <p>Sind Sie sicher, dass Sie die ausgewählte Terminvorschlag löschen möchten?</p>
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
                            <v-btn-primary @click="deleteTrainingProposal" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Löschen</v-btn-primary>
                        </v-hover>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </template>

        <div class="tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8">
            <div v-if="$page.props.auth.user.is_super_admin || $page.props.auth.user.is_admin" class="tw-bg-white tw-flex tw-justify-between tw-px-6 tw-py-6">
                <div class="tw-w-full">
                    <v-row>
                        <v-col cols="12" sm="4">
                            <v-locale-provider locale="de">
                                <v-menu v-model="isFirstDateFilterOpened"
                                        :return-value.sync="firstDateFilter"
                                        :close-on-content-click="false">
                                    <template v-slot:activator="{ props }">
                                        <v-text-field
                                            label="Erster Schulungstag"
                                            class="tw-cursor-pointer"
                                            :model-value="rawFirstDateFilter"
                                            prepend-icon="mdi-calendar"
                                            readonly
                                            clearable
                                            v-bind="props"
                                            @click:clear="clearFirstDateFilter"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker @update:modelValue="isFirstDateFilterOpened = false" v-model="firstDateFilter"></v-date-picker>
                                </v-menu>
                            </v-locale-provider>
                        </v-col>

                        <v-col cols="12" sm="4">
                            <v-locale-provider locale="de">
                                <v-menu v-model="isSecondDateFilterOpened"
                                        :return-value.sync="secondDateFilter"
                                        :close-on-content-click="false">
                                    <template v-slot:activator="{ props }">
                                        <v-text-field
                                            label="Zweiter Schulungstag"
                                            class="tw-cursor-pointer"
                                            :model-value="rawSecondDateFilter"
                                            prepend-icon="mdi-calendar"
                                            readonly
                                            clearable
                                            v-bind="props"
                                            @click:clear="clearSecondDateFilter"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker v-model="secondDateFilter"
                                                   @update:modelValue="isSecondDateFilterOpened = false"
                                    ></v-date-picker>
                                </v-menu>
                            </v-locale-provider>
                        </v-col>

                        <v-col cols="12" sm="4">
                            <v-text-field
                                v-model="locationFilter"
                                label="Ort"
                                clearable
                            ></v-text-field>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="12" sm="3">
                            <v-text-field
                                type="number"
                                v-model="participantCountFilter"
                                label="Teilnehmerzahl"
                                clearable
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" sm="3">
                            <v-select
                                v-model="statusFilter"
                                :items="statuses"
                                item-title="title"
                                item-value="value"
                                label="Status"
                                multiple
                                :disabled="loading"
                                clearable
                            ></v-select>
                        </v-col>

                        <v-col cols="12" sm="3">
                            <template v-if="$page.props.auth.user.is_super_admin || $page.props.auth.user.is_admin">
                                <v-select
                                    v-model="multiIdFilter"
                                    :items="multipliers"
                                    item-title="full_name"
                                    item-value="id"
                                    label="Multiplikator"
                                    multiple
                                    :disabled="loading"
                                    clearable
                                ></v-select>
                            </template>
                        </v-col>

                        <v-col cols="12" sm="3">
                            <template v-if="$page.props.auth.user.is_super_admin || $page.props.auth.user.is_admin">
                                <v-select
                                    v-model="kitaIdFilter"
                                    :items="kitas"
                                    item-title="name"
                                    item-value="id"
                                    label="KiTa"
                                    multiple
                                    :disabled="loading"
                                    clearable
                                ></v-select>
                            </template>
                        </v-col>
                    </v-row>
                </div>
            </div>

            <div v-if="$page.props.auth.user.is_user_multiplier" class="tw-mx-4 tw-mb-8">
                <h2 class="tw-font-semibold tw-text-base tw-text-gray-800 tw-leading-tight">
                    Offene Terminvorschläge
                </h2>
            </div>

            <!-- Main data table -->
            <v-data-table-server
                v-model:items-per-page="perPage"
                :items-per-page-options="[
                  { value: 10, title: '10' },
                  { value: 25, title: '25' },
                  { value: 50, title: '50' },
                  { value: 100, title: '100' },
                  { value: -1, title: '$vuetify.dataFooter.itemsPerPageAll' }
                ]"
                :items-per-page-text="'Objekte pro Seite:'"
                :headers="mainTableHeaders"
                :page="currentPage"
                :items-length="totalItems"
                :items="modifiedItems"
                :search="search"
                :loading="loading"
                class="data-table-container elevation-1"
                item-value="name"
                @update:options="goToPage"
            >
                <template v-slot:item="{ item }">
                    <tr :data-id="item.id" :data-order="item.order">
                        <td>{{!item.first_date || item.first_date === '-' ? item.first_date : formatDate(item.first_date, 'de-DE')}}</td>

                        <td>{{!item.second_date || item.second_date === '-' ? item.second_date : formatDate(item.second_date, 'de-DE')}}</td>

                        <td>{{item.formatted_location}}</td>

                        <td>{{item.participant_count}}</td>

                        <td>{{item?.kitas_list && item?.kitas_list.length ? item?.kitas_list.join(',') : '-'}}</td>

                        <td>
                            <v-tooltip location="top">
                                <template v-slot:activator="{ props }">
                                    <span class="tw-cursor-pointer">
                                        <v-icon v-bind="props" size="small" class="tw-me-2">{{getTrainingProposalStatusIcon(item.status)}}</v-icon>
                                    </span>
                                </template>
                                <span>{{item.formatted_status}}</span>
                            </v-tooltip>
                        </td>

                        <td>{{!item.updated_at || item.updated_at === '-' ? item.updated_at : formatDateTime(item.updated_at, 'de-DE')}}</td>

                        <td class="text-center">
                            <template v-if="$page.props.auth.user.is_super_admin || $page.props.auth.user.is_admin">
                                <v-tooltip v-if="item?.kitas_users_emails.length > 0" location="top">
                                    <template v-slot:activator="{ props }">
                                        <a :href="`mailto:?bcc=${item?.kitas_users_emails.join(',')}`" v-bind="props">
                                            <v-icon v-bind="props" size="small" class="tw-me-2">mdi-email</v-icon>
                                        </a>
                                    </template>
                                    <span>Mail an KiTa(s) schreiben</span>
                                </v-tooltip>
                            </template>

                            <template v-if="item.status === 'open' && ($page.props.auth.user.is_user_multiplier)">
                                <v-tooltip location="top">
                                    <template v-slot:activator="{ props }">
                                        <span class="tw-cursor-pointer" @click="openChangeTrainingProposalStatusDialog(item, 'reserved')">
                                            <v-icon v-bind="props" size="small" class="tw-me-2">mdi-plus-circle-outline</v-icon>
                                        </span>
                                    </template>
                                    <span>Termin reservieren</span>
                                </v-tooltip>
                            </template>

                            <template v-if="item.status === 'reserved' && ($page.props.auth.user.is_user_multiplier)">
                                <v-tooltip location="top">
                                    <template v-slot:activator="{ props }">
                                        <span class="tw-cursor-pointer" @click="openChangeTrainingProposalStatusDialog(item, 'open')">
                                            <v-icon v-bind="props" size="small" class="tw-me-2">mdi-minus-circle-outline</v-icon>
                                        </span>
                                    </template>
                                    <span>Reservierung aufheben</span>
                              </v-tooltip>
                            </template>

                            <v-tooltip v-if="$page.props.auth.user.is_super_admin || $page.props.auth.user.is_admin" location="top">
                                <template v-slot:activator="{ props }">
                                    <Link :href="route('training_proposals.show', { id: item.id })">
                                        <v-icon v-bind="props" size="small" class="tw-me-2">mdi-pencil</v-icon>
                                    </Link>
                                </template>
                                <span>Schulung bearbeiten</span>
                            </v-tooltip>

                            <v-tooltip v-if="$page.props.auth.user.is_super_admin || $page.props.auth.user.is_admin" location="top">
                                <template v-slot:activator="{ props }">
                                    <v-icon v-bind="props" size="small" class="tw-me-2" @click="openDeleteTrainingProposalDialog(item)">mdi-delete</v-icon>
                                </template>
                                <span>Schulung löschen</span>
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

            <!-- Additional data table -->
            <template v-if="$page.props.auth.user.is_user_multiplier">
                <div class="tw-border-t-8 tw-mt-16 tw-pt-8"></div>

                <div class="tw-mx-4 tw-mb-8">
                    <h2 class="tw-font-semibold tw-text-base tw-text-gray-800 tw-leading-tight">
                        Meine Terminvorschläge
                    </h2>
                </div>

                <v-data-table-server
                    :items-per-page-options="[
                      { value: 10, title: '10' },
                      { value: 25, title: '25' },
                      { value: 50, title: '50' },
                      { value: 100, title: '100' },
                      { value: -1, title: '$vuetify.dataFooter.itemsPerPageAll' }
                    ]"
                    :items-per-page-text="'Objekte pro Seite:'"
                    :headers="additionalTableHeaders"
                    :items="modifiedUserTrainingProposals"
                    :search="search"
                    :loading="loading"
                    class="data-table-container elevation-1"
                    item-value="name"
                >
                    <template v-slot:item="{ item }">
                        <tr :data-id="item.id" :data-order="item.order">
                            <td>{{!item.first_date || item.first_date === '-' ? 'item.first_date' : formatDate(item.first_date, 'de-DE')}}</td>

                            <td>{{!item.second_date || item.second_date === '-' ? 'item.second_date' : formatDate(item.second_date, 'de-DE')}}</td>

                            <td>{{item.formatted_location}}</td>

                            <td>{{item.participant_count}}</td>

                            <td></td>

                            <td>
                                <v-tooltip location="top">
                                    <template v-slot:activator="{ props }">
                                        <span class="tw-cursor-pointer">
                                            <v-icon v-bind="props" size="small" class="tw-me-2">{{getTrainingProposalStatusIcon(item.status)}}</v-icon>
                                        </span>
                                    </template>
                                    <span>{{item.formatted_status}}</span>
                                </v-tooltip>
                            </td>

                            <td>{{!item.updated_at || item.updated_at === '-' ? item.updated_at : formatDateTime(item.updated_at, 'de-DE')}}</td>

                            <td class="text-center">
                                <v-tooltip v-if="item?.kitas_users_emails.length > 0" location="top">
                                    <template v-slot:activator="{ props }">
                                        <a :href="`mailto:?bcc=${item?.kitas_users_emails.join(',')}`" v-bind="props">
                                            <v-icon v-bind="props" size="small" class="tw-me-2">mdi-email</v-icon>
                                        </a>
                                    </template>
                                    <span>Mail an KiTa(s) schreiben</span>
                                </v-tooltip>

                                <template v-if="item.status === 'reserved' && ($page.props.auth.user.is_user_multiplier)">
                                    <v-tooltip location="top">
                                        <template v-slot:activator="{ props }">
                                            <span class="tw-cursor-pointer" @click="openChangeTrainingProposalStatusDialog(item, 'open')">
                                                <v-icon v-bind="props" size="small" class="tw-me-2">mdi-minus-circle-outline</v-icon>
                                            </span>
                                        </template>
                                        <span>Reservierung aufheben</span>
                                    </v-tooltip>
                                </template>

                                <v-tooltip location="top">
                                    <template v-slot:activator="{ props }">
                                        <Link :href="route('training_proposals.show', { id: item.id })">
                                            <v-icon v-bind="props" size="small" class="tw-me-2">mdi-pencil</v-icon>
                                        </Link>
                                    </template>
                                    <span>Schulung bearbeiten</span>
                                </v-tooltip>
                            </td>
                        </tr>
                    </template>

                    <template #bottom></template>

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
            </template>

            <!-- Popups -->
            <v-container>
                <v-row>
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
                                    <v-btn-primary @click="manageTrainingProposalStatus('reserved', $page.props.auth.user.id)" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Einreichen</v-btn-primary>
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
                </v-row>
            </v-container>
        </div>
    </AuthenticatedLayout>
</template>
