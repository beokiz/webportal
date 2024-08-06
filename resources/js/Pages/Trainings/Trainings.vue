<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import { computed, ref, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, useForm, usePage, router, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import VueTimepicker from 'vue3-timepicker';
import { tr } from 'vuetify/locale';
import { debounce } from 'lodash';
import { formatDate, formatDateTime, prepareDate } from '@/Composables/common.js';

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
    multipliers: Array,
    statuses: Array,
    types: Array,
});

/*
 * Inertia events handling
 */
// For page reloading on pagination or searching
Inertia.on('success', (event) => {
    let newProps = event.detail.page.props;
    let pageType = event.detail.page.component;

    if (pageType === 'Trainings/Trainings' && newProps) {
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
const maxParticipantCountFilter = ref(props.filters.max_participant_count ?? null);
const typeFilter = ref(props.filters.type ?? null);
const multiIdFilter = ref(props.filters.multi_id ?? null);
const statusFilter = ref(props.filters.status ?? null);
const search = ref('');
const errors = ref(props.errors || {});

const isFirstDateFilterOpened = ref(false);
const isSecondDateFilterOpened = ref(false);
const isFirstDateFieldOpened = ref(false);
const isSecondDateFieldOpened = ref(false);

const loading = ref(false);
const dialog = ref(false);
const confirmTrainingDialog = ref(false);
const completeTrainingDialog = ref(false);
const cancelTrainingDialog = ref(false);
const dialogDeleteTraining = ref(false);
const deletingItemName = ref(null);

const rawFirstDateFilter = ref(null);
const rawSecondDateFilter = ref(null);
const firstDateFilter = ref(null);
const secondDateFilter = ref(null);

const rawFirstDateField = ref(null);
const rawSecondDateField = ref(null);
const firstDateField = ref(null);
const secondDateField = ref(null);

const selectedTraining = ref(null);
const selectedTrainingKitas = ref([]);
const ntfKitas = ref([]);

const headers = [
    { title: 'Erster Schulungstag', key: 'first_date', width: '4%', sortable: true },
    { title: 'Zweiter Schulungstag', key: 'second_date', width: '4%', sortable: true },
    { title: 'Ort', key: 'location', width: '10%', sortable: true },
    { title: 'Teilnehmer ', key: 'prepared_participant_count', width: '5%', sortable: true },
    { title: 'Typ', key: 'type', width: '7%', sortable: true },
    { title: 'Status', key: 'status', width: '10%', sortable: true },
    { title: 'Multiplikator', key: 'multi_id', width: '10%', sortable: true },
    { title: 'Notizen', key: 'notes', width: '20%', sortable: true },
    { title: 'Erstellt am', key: 'created_at', width: '10%', sortable: true },
    { title: 'Geändert am', key: 'updated_at', width: '10%', sortable: true },
    { title: 'Aktion', key: 'actions', width: '10%', sortable: false, align: 'center' },
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
    return firstDateFilter.value === null &&
        secondDateFilter.value === null &&
        locationFilter.value === null &&
        participantCountFilter.value === null &&
        maxParticipantCountFilter.value === null &&
        typeFilter.value === null &&
        multiIdFilter.value === null &&
        statusFilter.value === null;
});

const someFiltersNotEmpty = computed(() => {
    return firstDateFilter.value !== null ||
        secondDateFilter.value !== null ||
        locationFilter.value !== null ||
        participantCountFilter.value !== null ||
        maxParticipantCountFilter.value !== null ||
        typeFilter.value !== null ||
        multiIdFilter.value !== null ||
        statusFilter.value !== null;
});

const completedTrainingInfo = computed(() => {
    return [
        {
            label: 'Erster Schukungstag',
            value: `${prepareDate(selectedTraining.value?.first_date)} ${selectedTraining.value?.first_date_start_and_end_time}`,
        },
        {
            label: 'Zweiter Schulungstag',
            value: `${prepareDate(selectedTraining.value?.second_date)} ${selectedTraining.value?.second_date_start_and_end_time}`,
        },
        {
            label: 'Ort',
            value: selectedTraining.value?.location,
        },
        {
            label: 'Typ',
            value: selectedTraining.value?.type,
        },
        {
            label: 'Teinhemheranzahl',
            value: selectedTraining.value?.participant_count,
        },
    ];
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

watch(maxParticipantCountFilter, debounce((val) => {
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

watch(firstDateField, (val) => {
    rawFirstDateField.value = prepareDate(val);
});

watch(secondDateField, (val) => {
    rawSecondDateField.value = prepareDate(val);
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
        maxParticipantCountFilter.value = null;
        typeFilter.value = null;
        multiIdFilter.value = null;
        statusFilter.value = null;
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

        if (maxParticipantCountFilter.value) {
            data.max_participant_count = maxParticipantCountFilter.value;
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

const openDeleteTrainingDialog = (item) => {
    deletingItemName.value = item.name
    deleteForm.id = item.id;
    dialogDeleteTraining.value = true
};

const deleteForm = useForm({
    id: null,
});

const deleteTraining = async () => {
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

    deleteForm.delete(route('trainings.destroy', { id: deleteForm.id }), formOptions);
};

const close = () => {
    dialog.value = false;
    dialogDeleteTraining.value = false;
    confirmTrainingDialog.value = false;
    completeTrainingDialog.value = false;
    cancelTrainingDialog.value = false;

    manageForm.reset();
    manageForm.clearErrors();
    manageStatusForm.reset();
    manageStatusForm.clearErrors();

    rawFirstDateField.value = null;
    rawSecondDateField.value = null;
    firstDateField.value = null;
    secondDateField.value = null;

    selectedTraining.value = null;
    selectedTrainingKitas.value = null;

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
    multi_id: null,
    first_date: null,
    first_date_start_and_end_time: '12:00',
    second_date: null,
    second_date_start_and_end_time: '12:00',
    location: null,
    max_participant_count: null,
    // participant_count: null,
    type: null,
    // status: null,
    notes: null,
});

const manageTraining = async () => {
    manageForm.processing = true;

    manageForm.first_date = firstDateField.value ? new Date(firstDateField.value).toLocaleString() : null;
    manageForm.second_date = secondDateField.value ? new Date(secondDateField.value).toLocaleString() : null;

    manageForm.post(route('trainings.store'), {
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


const openChangeTrainingStatusDialog = (item, status) => {
    switch (status) {
        case 'confirmed':
            confirmTrainingDialog.value = true;
            break;
        case 'completed':
            completeTrainingDialog.value = true;
            break;
        case 'cancelled':
            cancelTrainingDialog.value = true;
            break;
    }

    manageStatusForm.id = item?.id;
    manageStatusForm.status = status;

    selectedTraining.value = item;
    selectedTrainingKitas.value = item?.kitas;
};

const manageStatusForm = useForm({
    id: null,
    status: null,
});

const manageTrainingStatus = async (status) => {
    manageStatusForm.processing = true;

    manageStatusForm.status = status;

    manageStatusForm.put(route('trainings.update', { training: manageStatusForm.id }), {
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
                    const subject = selectedTraining.value?.email_messages[status]?.subject;
                    const body = selectedTraining.value?.email_messages[status]?.body;

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
    });
};
</script>

<template>
    <Head title="Schulungen" />

    <AuthenticatedLayout :errors="errors">
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">Schulungen</h2>

            <div class="tw-flex tw-items-center tw-justify-end">
                <v-hover v-if="$page.props.auth.user.is_super_admin || $page.props.auth.user.is_admin" v-slot:default="{ isHovering, props }">
                    <v-btn v-bind="props" :color="isHovering ? 'accent' : 'primary'" dark>
                        Anlegen

                        <v-dialog v-model="dialog" activator="parent" width="80vw">
                            <v-card height="80vh">
                                <v-card-title>
                                    <span class="tw-text-h5">Verwalte Schulungen</span>
                                </v-card-title>

                                <v-card-text>
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
                                                                clearable
                                                                v-bind="props"
                                                                :disabled="loading"
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
                                                                :disabled="loading">
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
                                                                clearable
                                                                v-bind="props"
                                                                :disabled="loading"
                                                            ></v-text-field>
                                                        </template>
                                                        <v-date-picker @update:modelValue="isSecondDateFieldOpened = false" v-model="secondDateField"></v-date-picker>
                                                    </v-menu>
                                                  </v-locale-provider>
                                            </v-col>

                                            <v-col cols="12" sm="6">
                                                <v-label class="tw-mt-6 tw-mr-2">Zeitraum zweiter Schulungstag*</v-label>

                                                <vue-timepicker v-model="manageForm.first_date_start_and_end_time"
                                                                :hideClearButton="true"
                                                                :hourLabel="'Std.'"
                                                                :minuteLabel="'Protokoll'"
                                                                :disabled="loading">
                                                </vue-timepicker>
                                            </v-col>
                                        </v-row>

                                        <v-row>
                                            <v-col cols="12" sm="4">
                                                <v-text-field
                                                    type="number"
                                                    v-model="manageForm.max_participant_count"
                                                    :error-messages="errors.max_participant_count"
                                                    label="Max. Teilnehmerzahl*"
                                                    :disabled="loading"
                                                    clearable
                                                ></v-text-field>
                                            </v-col>

                                            <v-col cols="12" sm="4">
                                                <v-select
                                                    v-model="manageForm.type"
                                                    :error-messages="errors.type"
                                                    :items="types"
                                                    item-title="title"
                                                    item-value="value"
                                                    label="Typ*"
                                                    :disabled="loading"
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
                                                    :disabled="loading"
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
                                        <v-btn-primary @click="manageTraining" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Speichern</v-btn-primary>
                                    </v-hover>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-btn>
                </v-hover>
            </div>

            <v-dialog v-if="$page.props.auth.user.is_super_admin || $page.props.auth.user.is_admin" v-model="dialogDeleteTraining" width="20vw">
                <v-card height="30vh">
                    <v-card-text>
                        <v-container>
                            <v-row>
                                <v-col cols="12">
                                    <p>Sind Sie sicher, dass Sie die ausgewählte Schulung löschen möchten?</p>
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
                            <v-btn-primary @click="deleteTraining" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Löschen</v-btn-primary>
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
                        <v-col cols="12" sm="4">
                            <v-text-field
                                type="number"
                                v-model="participantCountFilter"
                                label="Teilnehmerzahl"
                                clearable
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" sm="4">
                            <v-text-field
                                type="number"
                                v-model="maxParticipantCountFilter"
                                label="Max. Teilnehmerzahl"
                                clearable
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" sm="4">
                            <v-select
                                v-model="typeFilter"
                                :items="types"
                                item-title="title"
                                item-value="value"
                                label="Typ"
                                multiple
                                :disabled="loading"
                                clearable
                            ></v-select>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="12" sm="4">
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

                        <v-col cols="12" sm="4">
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

                        <v-col cols="12" sm="4">

                        </v-col>
                    </v-row>
                </div>
            </div>

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
                        <td>{{!item.selectable.first_date || item.selectable.first_date === '-' ? item.selectable.first_date : formatDate(item.selectable.first_date, 'fr-CA')}}</td>

                        <td>{{!item.selectable.second_date || item.selectable.second_date === '-' ? item.selectable.second_date : formatDate(item.selectable.second_date, 'fr-CA')}}</td>

                        <td>{{item.selectable.location}}</td>

                        <td>{{item.selectable.prepared_participant_count}}</td>

                        <td>{{item.selectable.type}}</td>

                        <td>{{item.selectable.status}}</td>

                        <td>{{item.selectable?.multiplier ? item.selectable?.multiplier?.full_name : '-'}}</td>

                        <td>{{item.selectable.notes}}</td>

                        <td>{{!item.selectable.created_at || item.selectable.created_at === '-' ? item.selectable.created_at : formatDateTime(item.selectable.created_at, 'sv-SE')}}</td>

                        <td>{{!item.selectable.updated_at || item.selectable.updated_at === '-' ? item.selectable.updated_at : formatDateTime(item.selectable.updated_at, 'sv-SE')}}</td>

                        <td class="text-center">
                            <template v-if="item.selectable.status === 'planned'">
                                <v-tooltip location="top">
                                    <template v-slot:activator="{ props }">
                                        <span class="tw-cursor-pointer" @click="openChangeTrainingStatusDialog(item.selectable, 'confirmed')">
                                            <v-icon v-bind="props" size="small" class="tw-me-2">mdi-progress-check</v-icon>
                                        </span>
                                    </template>
                                    <span>Schulungstermin bestätigen</span>
                                </v-tooltip>
                            </template>
                            <template v-if="item.selectable.status === 'confirmed'">
                                <v-tooltip location="top">
                                    <template v-slot:activator="{ props }">
                                        <span class="tw-cursor-pointer" @click="openChangeTrainingStatusDialog(item.selectable, 'completed')">
                                            <v-icon v-bind="props" size="small" class="tw-me-2">mdi-check</v-icon>
                                        </span>
                                    </template>
                                    <span>Training abschließen und Einrichtungen zulassen</span>
                              </v-tooltip>
                            </template>
                            <template v-if="item.selectable.status !== 'completed' && item.selectable.status !== 'cancelled' && ($page.props.auth.user.is_super_admin || $page.props.auth.user.is_admin)">
                                <v-tooltip location="top">
                                    <template v-slot:activator="{ props }">
                                        <span class="tw-cursor-pointer" @click="openChangeTrainingStatusDialog(item.selectable, 'cancelled')">
                                            <v-icon v-bind="props" size="small" class="tw-me-2">mdi-close</v-icon>
                                        </span>
                                    </template>
                                    <span>Training abbrechen</span>
                                </v-tooltip>
                            </template>

                            <v-tooltip location="top">
                                <template v-slot:activator="{ props }">
                                    <Link :href="route('trainings.show', { id: item.selectable.id })">
                                        <v-icon v-bind="props" size="small" class="tw-me-2">mdi-pencil</v-icon>
                                    </Link>
                                </template>
                                <span>Schulung bearbeiten</span>
                            </v-tooltip>

                            <v-tooltip v-if="$page.props.auth.user.is_super_admin || $page.props.auth.user.is_admin" location="top">
                                <template v-slot:activator="{ props }">
                                    <v-icon v-bind="props" size="small" class="tw-me-2" @click="openDeleteTrainingDialog(item.raw)">mdi-delete</v-icon>
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

                                    <v-row v-if="selectedTrainingKitas  && selectedTrainingKitas.length">
                                        <v-col cols="12">
                                            <p class="mb-4">Sind Sie sich sicher, dass Sie die Termine gegenüber den folgenden Kitas bestätigen wollen? Im Folgenden gibt es individuelle E-Mail-Vorschläge für jede Kita.</p>
                                            <p>Bitte klicken Sie auf den Namen der Kita, um diesen zu erhalten.</p>
                                        </v-col>

                                        <v-col cols="12" class="tw--mt-6">
                                            <v-list>
                                                <v-list-item class="hide-details" v-for="kita in selectedTrainingKitas" :key="kita.id">
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
                                    <v-btn-primary @click="manageTrainingStatus('confirmed')" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Einreichen</v-btn-primary>
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
                                    <v-btn-primary @click="manageTrainingStatus('completed')" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Einreichen</v-btn-primary>
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
                                    <v-btn-primary @click="manageTrainingStatus('cancelled')" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Einreichen</v-btn-primary>
                                </v-hover>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-row>
            </v-container>
        </div>
    </AuthenticatedLayout>
</template>
