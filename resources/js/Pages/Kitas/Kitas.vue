<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import { computed, ref, watch } from 'vue';
import { debounce } from 'lodash';
import { Inertia } from '@inertiajs/inertia';
import { Head, useForm, usePage, router, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

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
    types: Array,
    zipCodes: Array,
    operators: Array,
    usersEmails: Array,
});

/*
 * Inertia events handling
 */
// For page reloading on pagination or searching
Inertia.on('success', (event) => {
    let newProps = event.detail.page.props;
    let pageType = event.detail.page.component;

    if (pageType === 'Kitas/Kitas' && newProps) {
        currentPage.value = newProps.currentPage;
        perPage.value = newProps.perPage;
        orderBy.value = newProps.orderBy;
        sort.value = newProps.sort;
        totalItems.value = newProps.total;
        lastPage.value = newProps.lastPage;
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
const searchFilter = ref(props.filters?.search ?? null);
const hasYearlyEvaluationsFilter = ref(props.filters?.has_yearly_evaluations ?? null);
const approvedFilter = ref(props.filters?.approved ?? null);
const operatorIdFilter = ref(null);
const typeFilter = ref(props.filters?.type ?? null);
const zipCodeFilter = ref(props.filters?.zip_code ?? null);
const search = ref('');
const errors = ref(props.errors || {});

const selectedUsersEmails = ref([]);

const loading = ref(false);
const dialog = ref(false);
const dialogUsersEmails = ref(false);
const dialogDeleteKita = ref(false);
const deletingItemName = ref(null);

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

const headers = [
    { title: 'Name', key: 'name', width: '15%', sortable: true },
    { title: `Jährliche Rückmeldung ${new Date().getFullYear()} abgeschlossen`, key: 'has_yearly_evaluations', width: '35%', sortable: true },
    { title: 'Zugelassen', key: 'approved', width: '10%', sortable: true },
    { title: 'Träger', key: 'operator_id', width: '10%', sortable: true },
    { title: 'Typ', key: 'type', width: '10%', sortable: true },
    { title: 'Postleitzahl', key: 'zip_code', width: '10%', sortable: true },
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
    return searchFilter.value === null && hasYearlyEvaluationsFilter.value === null && approvedFilter.value === null && operatorIdFilter.value === null && typeFilter.value === null && zipCodeFilter.value === null;
});

const someFiltersNotEmpty = computed(() => {
    return searchFilter.value !== null || hasYearlyEvaluationsFilter.value !== null || approvedFilter.value !== null || operatorIdFilter.value !== null || typeFilter.value !== null || zipCodeFilter.value !== null;
});

//Watch
watch(dialog, (val) => {
    if (!val) {
        close();
    }
});

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

// Methods
const openUsersEmailsDialog = () => {
    dialogUsersEmails.value = true;
    selectedUsersEmails.value = props.usersEmails;
};

const triggerSearch = () => {
    loading.value = true;
    search.value = String(Date.now());
};

const goToPage = async ({ page, itemsPerPage, sortBy, clearFilters }) => {
    if (clearFilters) {
        searchFilter.value = null;
        hasYearlyEvaluationsFilter.value = null;
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

        // Apply filters
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

const openDeleteKitaDialog = (item) => {
    deletingItemName.value = item.name
    deleteForm.id = item.id;
    dialogDeleteKita.value = true
};

const deleteForm = useForm({
    id: null,
});

const deleteKita = async () => {
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

    deleteForm.delete(route('kitas.destroy', { id: deleteForm.id }), formOptions);
};

const close = () => {
    dialog.value = false;
    dialogDeleteKita.value = false;
    dialogUsersEmails.value = false;
    manageForm.reset();
    manageForm.clearErrors();

    errors.value = {};
};

const clear = () => {
    manageForm.reset();
    manageForm.clearErrors();
};


const manageForm = useForm({
    name: null,
    number: null,
    street: null,
    house_number: null,
    city: null,
    additional_info: null,
    zip_code: null,
    operator_id: null,
    num_pedagogical_staff: null,
    approved: true,
    type: null,
});

const manageKita = async () => {
    manageForm.processing = true;

    manageForm.post(route('kitas.store'), {
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
</script>

<template>
    <Head title="Einrichtungen" />

    <AuthenticatedLayout :errors="errors">
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">Einrichtungen</h2>

            <div class="tw-flex tw-items-center tw-justify-end">
                <v-hover v-if="!$page.props.auth.user.is_manager" v-slot:default="{ isHovering, props }">
                    <v-btn v-bind="props" :color="isHovering ? 'accent' : 'primary'" dark>
                        Anlegen

                        <v-dialog v-model="dialog" activator="parent" width="80vw">
                            <v-card height="80vh">
                                <v-card-title>
                                    <span class="tw-text-h5">Verwalte Einrichtung</span>
                                </v-card-title>

                                <v-card-text>
                                    <v-container>
                                        <v-row>
                                            <v-col cols="12">
                                                <h4>Adresse</h4>
                                            </v-col>
                                        </v-row>

                                        <v-row>
                                            <v-col cols="12" sm="6">
                                                <v-text-field v-model="manageForm.name"
                                                              :error-messages="errors.name"
                                                              label="Name der Einrichtung / Einrichtung*"
                                                              required
                                                ></v-text-field>
                                            </v-col>

                                            <v-col cols="12" sm="6">
                                                <v-text-field v-model="manageForm.number"
                                                              :error-messages="errors.number"
                                                              label="Kita Nummer*"
                                                              type="number"
                                                              required
                                                ></v-text-field>
                                            </v-col>
                                        </v-row>

                                        <v-row>
                                            <v-col cols="12" sm="6">
                                                <v-text-field v-model="manageForm.street"
                                                              :error-messages="errors.street"
                                                              label="Straße*"
                                                              required
                                                ></v-text-field>
                                            </v-col>

                                            <v-col cols="12" sm="6">
                                                <v-text-field v-model="manageForm.house_number"
                                                              :error-messages="errors.house_number"
                                                              label="Hausnummer*"
                                                              required
                                                ></v-text-field>
                                            </v-col>
                                        </v-row>

                                        <v-row>
                                            <v-col cols="12">
                                                <v-text-field v-model="manageForm.additional_info"
                                                              :error-messages="errors.additional_info"
                                                              label="Sonstiges"
                                                              required
                                                ></v-text-field>
                                            </v-col>
                                        </v-row>

                                        <v-row>
                                            <v-col cols="12" sm="6">
                                                <v-text-field v-model="manageForm.zip_code"
                                                              :error-messages="errors.zip_code"
                                                              label="Postleitzahl*"
                                                              type="number"
                                                              required
                                                ></v-text-field>
                                            </v-col>

                                            <v-col cols="12" sm="6">
                                                <v-text-field v-model="manageForm.city"
                                                              :error-messages="errors.city"
                                                              label="Stadt*"
                                                              required
                                                ></v-text-field>
                                            </v-col>
                                        </v-row>

                                        <v-row>
                                            <v-col cols="12">
                                                <h4>Eigenschaften</h4>
                                            </v-col>
                                        </v-row>

                                        <v-row>
                                            <v-col cols="12" sm="6">
                                                <v-select
                                                    v-model="manageForm.operator_id"
                                                    :items="operators"
                                                    :error-messages="errors.operator_id"
                                                    item-title="name"
                                                    item-value="id"
                                                    label="Träger der Einrichtung*"
                                                    required
                                                ></v-select>
                                            </v-col>

                                            <v-col cols="12" sm="6">
                                                <v-text-field v-model="manageForm.num_pedagogical_staff"
                                                              :error-messages="errors.num_pedagogical_staff"
                                                              label="Größe pädagogisches Team"
                                                              type="number"
                                                ></v-text-field>
                                            </v-col>
                                        </v-row>

                                        <v-row>
                                            <v-col cols="12" sm="6">
                                                <v-checkbox
                                                    v-model="manageForm.approved"
                                                    label="Kita zur Ampel zugelassen"
                                                    :value="true"
                                                ></v-checkbox>
                                            </v-col>

                                            <v-col cols="12" sm="6">
                                                <v-select
                                                    v-model="manageForm.type"
                                                    :items="types"
                                                    :error-messages="errors.type"
                                                    label="Träger der Einrichtung*"
                                                    required
                                                ></v-select>
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
                                        <v-btn-primary @click="manageKita" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Speichern</v-btn-primary>
                                    </v-hover>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-btn>
                </v-hover>
            </div>

            <v-dialog v-model="dialogDeleteKita" width="20vw">
                <v-card height="30vh">
                    <v-card-text>
                        <v-container>
                            <v-row>
                                <v-col cols="12">
                                    <p>Sind Sie sicher, dass Sie die Einrichtung {{deletingItemName}} löschen möchten?</p>
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
                            <v-btn-primary @click="deleteKita" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Löschen</v-btn-primary>
                        </v-hover>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </template>

        <div class="tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8">
            <div class="tw-bg-white tw-flex tw-justify-between tw-px-6 tw-py-6">
                <div class="tw-w-full">
                    <v-row>
                        <v-col cols="12" sm="4">
                            <v-text-field v-model="searchFilter"
                                          label="Suchen"
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
                                :items="types"
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
                </div>
            </div>

            <div>
                <v-row class="flex justify-end mb-4">
                    <v-hover v-slot:default="{ isHovering, props }">
                        <v-col cols="12" sm="4" class="text-right">
                            <v-btn v-bind="props" :color="isHovering ? 'accent' : 'primary'" dark @click="openUsersEmailsDialog">
                                <v-icon v-bind="props" size="small" class="tw-me-2">mdi-email</v-icon>
                                <span>Schreibe E-Mail an Auswahl</span>
                            </v-btn>
                        </v-col>
                    </v-hover>
                </v-row>
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
                        <td>{{item.selectable?.name}}</td>

                        <td>{{item.selectable?.has_yearly_evaluations ? 'Ja' : 'Nein'}}</td>

                        <td>{{item.selectable?.approved ? 'Ja' : 'Nein'}}</td>

                        <td>{{item.selectable?.operator?.name ?? '-'}}</td>

                        <td>{{item.selectable?.formatted_type ?? item.selectable?.type}}</td>

                        <td>{{item.selectable?.zip_code}}</td>

                        <td class="text-right">
                            <v-tooltip v-if="item.selectable?.approved && item.selectable?.users_emails.length > 0" location="top">
                                <template v-slot:activator="{ props }">
                                    <a :href="`mailto:?bcc=${item.selectable?.users_emails.join(',')}`" v-bind="props">
                                        <v-icon v-bind="props" size="small" class="tw-me-2">mdi-email</v-icon>
                                    </a>
                                </template>
                                <span>Schreibe E-Mail</span>
                            </v-tooltip>

                            <v-tooltip location="top">
                                <template v-slot:activator="{ props }">
                                    <Link :href="route('kitas.show', { id: item.selectable.id })">
                                        <v-icon v-bind="props" size="small" class="tw-me-2">mdi-pencil</v-icon>
                                    </Link>
                                </template>
                                <span>Einrichtung bearbeiten</span>
                            </v-tooltip>

                            <v-tooltip v-if="!$page.props.auth.user.is_manager" location="top">
                                <template v-slot:activator="{ props }">
                                    <v-icon v-bind="props" size="small" class="tw-me-2" @click="openDeleteKitaDialog(item.raw)">mdi-delete</v-icon>
                                </template>
                                <span>Einrichtung löschen</span>
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
    </AuthenticatedLayout>
</template>
