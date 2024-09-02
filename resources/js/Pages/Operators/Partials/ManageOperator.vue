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

const props = defineProps({
    operator: Object,
    errors: Object,
    users: Array,
    operatorUsers: Array,
    operatorKitas: Array,
    // Users & kitas tables
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

    if (pageType === 'Operators/Partials/ManageOperator' && newProps) {
        editedOperator.value = newProps.operator;
    }
});


/*
 * Main data
 */
const currentUser = usePage().props.auth.user ?? {};  // Global info about user

const editedOperator = ref(props.operator);
const errors = ref(props.errors || {});
const loading = ref(false);
const deletingUserName = ref(null);
const deletingKitaName = ref(null);
const dialog = ref(false);
const connectUserDialog = ref(false);
const dialogDeleteOperatorUser = ref(false);
const dialogDeleteOperatorKita = ref(false);

watch(dialog, (val) => {
    if (!val) {
        close();
    }
});

// Methods
const close = () => {
    dialog.value = false;
    connectUserDialog.value = false;
    dialogDeleteOperatorUser.value = false;
    dialogDeleteOperatorKita.value = false;
    dialogUsersEmails.value = false;
    manageConnectOperatorUserForm.reset();
    manageConnectOperatorUserForm.clearErrors();
    deleteUserFromOperatorForm.reset();
    deleteUserFromOperatorForm.clearErrors();
    errors.value = {};
};

const clear = () => {
    manageForm.reset();
    manageForm.clearErrors();
    manageForm.name = null;
    manageForm.self_training = null;

    manageConnectOperatorUserForm.reset();
    manageConnectOperatorUserForm.clearErrors();
    deleteUserFromOperatorForm.reset();
    deleteUserFromOperatorForm.clearErrors();
};

const manageForm = useForm({
    id: editedOperator.value.id,
    name: editedOperator.value.name,
    self_training: editedOperator.value.self_training,
});

const manageOperator = async () => {
    manageForm.processing = true;

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

    manageForm.put(route('operators.update', {operator: manageForm.id}), formOptions);
};

/*
 * Connect / disconnect User from Operator
 */
const manageConnectOperatorUserForm = useForm({
    users: null,
    kitas: [editedOperator.value.id]
});

const manageConnectOperatorUser = async () => {
    manageConnectOperatorUserForm.processing = true;

    manageConnectOperatorUserForm.post(route('operators.connect_users', {operator: manageConnectOperatorUserForm.kitas}), {
        onSuccess: (page) => {
            close();
        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            manageConnectOperatorUserForm.processing = false;
        },
    });
};

const openDeleteUserFromOperatorDialog = (item) => {
    deletingUserName.value = item.full_name
    deleteUserFromOperatorForm.user = item.id;
    dialogDeleteOperatorUser.value = true
};

const deleteUserFromOperatorForm = useForm({
    user: null,
});

const deleteUserFromOperator = async () => {
    deleteUserFromOperatorForm.processing = true;

    let formOptions = {
        onSuccess: (page) => {
            close();
        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            deleteUserFromOperatorForm.processing = false;
        },
    };

    deleteUserFromOperatorForm.post(route('operators.disconnect_user', {id: props.operator.id}), formOptions);
};

/*
 * Connect / disconnect Kita from Operator
 */
const openDeleteKitaFromOperatorDialog = (item) => {
    deletingKitaName.value = item.full_name
    deleteKitaFromOperatorForm.kita = item.id;
    dialogDeleteOperatorKita.value = true
};

const deleteKitaFromOperatorForm = useForm({
    kita: null,
});

const deleteKitaFromOperator = async () => {
    deleteKitaFromOperatorForm.processing = true;

    let formOptions = {
        onSuccess: (page) => {
            close();
        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            deleteKitaFromOperatorForm.processing = false;
        },
    };

    deleteKitaFromOperatorForm.post(route('operators.disconnect_kita', {id: props.operator.id}), formOptions);
};

/*
 * Users & kitas tables
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

const usersHeaders = [
  { title: 'Status', key: 'is_online', width: '5%', sortable: false, align: 'center' },
  { title: 'Vorname', key: 'first_name', width: '17.5%', sortable: true },
  { title: 'Nachname', key: 'last_name', width: '17.5%', sortable: true },
  { title: 'E-Mail', key: 'email', width: '25%', sortable: true },
  { title: 'Rolle', key: 'primary_role_name', width: '20%', sortable: true },
  { title: 'Aktion', key: 'actions', width: '15%', sortable: false, align: 'center' },
];

const kitaHeaders = [
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
const usersSearch = ref('');
const kitasSearch = ref('');
// Users filters
const statusFilter = ref(props.userFilters?.status ?? null);
const firstNameFilter = ref(props?.userFilters?.first_name ?? null);
const lastNameFilter = ref(props?.userFilters?.last_name ?? null);
const emailFilter = ref(props?.userFilters?.email ?? null);
// Kitas filters
const searchFilter = ref(props?.kitaFilters?.search ?? null);
const hasYearlyEvaluationsFilter = ref(props?.kitaFilters?.has_yearly_evaluations ?? null);
const approvedFilter = ref(props?.kitaFilters?.approved ?? null);
const typeFilter = ref(props?.kitaFilters?.type ?? null);
const zipCodeFilter = ref(props?.kitaFilters?.zip_code ?? null);

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

const statusFilterValues = [
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

const usersModifiedItems = computed(() => {
    return modifyItems(props.operatorUsers);
});

const kitasModifiedItems = computed(() => {
    return modifyItems(props.operatorKitas);
});

const allUsersFiltersEmpty = computed(() => {
    return statusFilter.value === null && firstNameFilter.value === null && lastNameFilter.value === null && emailFilter.value === null;
});

const allKitasFiltersEmpty = computed(() => {
    return searchFilter.value === null && hasYearlyEvaluationsFilter.value === null && approvedFilter.value === null && typeFilter.value === null && zipCodeFilter.value === null;
});

const someUsersFiltersNotEmpty = computed(() => {
    return statusFilter.value !== null || firstNameFilter.value !== null || lastNameFilter.value !== null || emailFilter.value !== null;
});

const someKitasFiltersNotEmpty = computed(() => {
    return searchFilter.value !== null || hasYearlyEvaluationsFilter.value !== null || approvedFilter.value !== null || typeFilter.value !== null || zipCodeFilter.value !== null;
});

// Users filters
watch(statusFilter, (val) => {
    triggerSearch('users');
});

watch(firstNameFilter, debounce((val) => {
    triggerSearch('users');
}, 500));

watch(lastNameFilter, debounce((val) => {
    triggerSearch('users');
}, 500));

watch(emailFilter, debounce((val) => {
    triggerSearch('users');
}, 500));

// Kitas filters
watch(searchFilter, debounce((val) => {
    triggerSearch('kitas');
}, 500));

watch(hasYearlyEvaluationsFilter, (val) => {
    triggerSearch('kitas');
});

watch(approvedFilter, (val) => {
    triggerSearch('kitas');
});

watch(typeFilter, (val) => {
    triggerSearch('kitas');
});

watch(zipCodeFilter, debounce((val) => {
    triggerSearch('kitas');
}, 500));

const triggerSearch = (type) => {
    loading.value = true;

    if (type === 'kitas') {
        kitasSearch.value = String(Date.now());
    } else {
        usersSearch.value = String(Date.now());
    }
};

const openUsersEmailsDialog = () => {
    dialogUsersEmails.value = true;
    selectedUsersEmails.value = props.usersEmails;
};

const goToUsersPage = async ({ page, itemsPerPage, sortBy, clearFilters }) => {
    if (clearFilters) {
        statusFilter.value = null;
        firstNameFilter.value = null;
        lastNameFilter.value = null;
        emailFilter.value = null;
    }

    if (
        (page === currentPage.value && clearFilters) ||
        allUsersFiltersEmpty ||
        someUsersFiltersNotEmpty
    ) {
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

        goToPage({ user_args: data }, { page, itemsPerPage, sortBy, clearFilters });
    }
};

const goToKitasPage = async ({ page, itemsPerPage, sortBy, clearFilters }) => {
    if (clearFilters) {
        searchFilter.value = null;
        hasYearlyEvaluationsFilter.value = null;
        typeFilter.value = null;
        zipCodeFilter.value = null;
    }

    if (
        (page === currentPage.value && clearFilters) ||
        allKitasFiltersEmpty ||
        someKitasFiltersNotEmpty
    ) {
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

        goToPage({ kita_args: data }, { page, itemsPerPage, sortBy, clearFilters });
    }
};

const goToPage = async (data, { page, itemsPerPage, sortBy, clearFilters }) => {
    loading.value = true;

    if (!data.user_args) {
      data.user_args = {};
    }

    if (!data.kita_args) {
      data.kita_args = {};
    }

    /*
     * Apply filters
     */
    // Apply users filters
    if (statusFilter.value) {
        data.user_args.status = statusFilter.value;
    }

    if (firstNameFilter.value) {
        data.user_args.first_name = firstNameFilter.value;
    }

    if (lastNameFilter.value) {
        data.user_args.last_name = lastNameFilter.value;
    }

    if (emailFilter.value) {
        data.user_args.email = emailFilter.value;
    }

    // Apply kitas filters
    if (searchFilter.value) {
        data.kita_args.search = searchFilter.value;
    }

    if (hasYearlyEvaluationsFilter.value) {
        data.kita_args.has_yearly_evaluations = hasYearlyEvaluationsFilter.value;
    }

    if (approvedFilter.value) {
        data.kita_args.approved = approvedFilter.value;
    }

    if (typeFilter.value) {
        data.kita_args.type = typeFilter.value;
    }

    if (zipCodeFilter.value) {
        data.kita_args.zip_code = zipCodeFilter.value;
    }

    await router.get(route(route().current(), {operator: manageForm.id}), data, {
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
};
</script>

<template>
    <Head :title="`Verwalte Träger ${operator.name}`"/>

    <AuthenticatedLayout :errors="errors">
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">Verwalte Träger {{ operator.name }}</h2>
        </template>

        <div class="tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8">
            <v-container>
                <v-row>
                    <v-col cols="12">
                        <h3>Träger</h3>
                    </v-col>
                </v-row>
            </v-container>

            <v-container>
                <v-row>
                    <v-col cols="12" sm="6">
                        <v-text-field v-model="manageForm.name"
                                      :error-messages="errors.name"
                                      label="Name" required
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6">
                        <v-checkbox
                            v-model="manageForm.self_training"
                            label="Selbschulend"
                            :value="true"
                        ></v-checkbox>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12" sm="6">
                        <v-hover v-slot:default="{ isHovering, props }">
                            <v-btn @click="clear" v-bind="props" :color="isHovering ? 'primary' : 'accent'">Zurücksetzen</v-btn>
                        </v-hover>
                    </v-col>

                    <v-col cols="12" sm="6" align="right">
                        <v-hover v-slot:default="{ isHovering, props }">
                            <Link :href="route('operators.index')">
                                <v-btn class="mr-2" variant="text" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Zurück</v-btn>
                            </Link>
                        </v-hover>
                        <v-hover v-slot:default="{ isHovering, props }">
                            <v-btn-primary @click="manageOperator" v-bind="props"
                                           :color="isHovering ? 'accent' : 'primary'">Speichern
                            </v-btn-primary>
                        </v-hover>
                    </v-col>
                </v-row>
            </v-container>

            <!-- Kitas table -->
            <v-container>
                <v-row class="tw-border-t-8 tw-mt-8 tw-pt-8">
                    <v-col cols="12" sm="6">
                        <h3>Zugeordnete Einrichtungen</h3>
                    </v-col>
                </v-row>
            </v-container>

            <v-container>
                <v-dialog v-model="dialogDeleteOperatorKita" width="20vw">
                    <v-card height="30vh">
                        <v-card-text>
                            <v-container>
                                <v-row>
                                    <v-col cols="12">
                                        <p>Sind Sie sicher, dass Sie den Einrichtung {{deletingKitaName}} aus Träger Liste löschen möchten? (Der Benutzer wird vom aktuellen Träger getrennt)</p>
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
                                  <v-btn-primary @click="deleteKitaFromOperator" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Löschen</v-btn-primary>
                              </v-hover>
                          </v-card-actions>
                    </v-card>
                </v-dialog>

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
                            :headers="kitaHeaders"
                            :items="kitasModifiedItems"
                            :search="kitasSearch"
                            v-sortable-data-table
                            :loading="loading"
                            class="data-table-container data-table-container-hide-footer elevation-1"
                            item-value="name"
                            @update:options="goToKitasPage"
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

                                          <v-tooltip location="top">
                                              <template v-slot:activator="{ props }">
                                                  <v-icon v-bind="props" size="small" class="tw-me-2"
                                                          @click="openDeleteKitaFromOperatorDialog(item.raw)">mdi-delete
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

            <v-container>
                <v-row>
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

            <!-- Users table -->
            <v-container>
                <v-row class="tw-border-t-8 tw-mt-8 tw-pt-8">
                    <v-col cols="12" sm="6">
                        <h3>Zugeordnete User-Multiplikatoren</h3>
                    </v-col>

                    <v-col cols="12" sm="6">
                        <div class="tw-flex tw-items-center tw-justify-end">
                            <v-hover v-if="$page.props.auth.user.is_super_admin || $page.props.auth.user.is_admin" v-slot:default="{ isHovering, props }">
                                <v-btn v-bind="props" :color="isHovering ? 'accent' : 'primary'" dark class="mr-2">
                                    Benutzer verbinden

                                    <v-dialog v-model="connectUserDialog" activator="parent" width="80vw">
                                        <v-card height="80vh">
                                            <v-card-title>
                                                <span class="tw-text-h5">Verbinden Benutzer</span>
                                            </v-card-title>

                                            <v-card-text>
                                                <v-container>
                                                    <v-row>
                                                        <v-col cols="12">
                                                            <p>Wählen Sie die Benutzer aus, die Sie diesem Träger hinzufügen möchten</p>
                                                        </v-col>
                                                    </v-row>
                                                    <v-row>
                                                        <v-col cols="12">
                                                            <v-autocomplete
                                                                v-model="manageConnectOperatorUserForm.users"
                                                                :items="users"
                                                                :error-messages="errors.users"
                                                                item-title="full_name"
                                                                item-value="id"
                                                                label="User"
                                                                multiple
                                                                required
                                                            ></v-autocomplete>
                                                        </v-col>
                                                    </v-row>
                                                </v-container>
                                            </v-card-text>

                                            <v-card-actions>
                                                <v-hover v-slot:default="{ isHovering, props }">
                                                    <v-btn-primary @click="clear" v-bind="props" :color="isHovering ? 'primary' : 'accent'">Zurücksetzen</v-btn-primary>
                                                </v-hover>
                                                <v-spacer></v-spacer>
                                                <v-hover v-slot:default="{ isHovering, props }">
                                                    <v-btn @click="close" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Zurück</v-btn>
                                                </v-hover>
                                                <v-hover v-slot:default="{ isHovering, props }">
                                                    <v-btn-primary @click="manageConnectOperatorUser" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Speichern</v-btn-primary>
                                                </v-hover>
                                            </v-card-actions>
                                        </v-card>
                                    </v-dialog>
                                </v-btn>
                            </v-hover>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
            <v-container>
                <v-dialog v-model="dialogDeleteOperatorUser" width="20vw">
                    <v-card height="30vh">
                        <v-card-text>
                            <v-container>
                                <v-row>
                                    <v-col cols="12">
                                        <p>Sind Sie sicher, dass Sie den Benutzer {{deletingUserName}} aus Träger Liste löschen möchten? (Der Benutzer wird vom aktuellen Träger getrennt)</p>
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
                                <v-btn-primary @click="deleteUserFromOperator" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Löschen</v-btn-primary>
                            </v-hover>
                        </v-card-actions>
                    </v-card>
                </v-dialog>

                <v-row>
                    <v-col cols="12">
                        <div class="tw-bg-white tw-flex tw-justify-between tw-px-6 tw-py-6">
                            <div class="tw-w-full">
                                <v-row>
                                    <v-col cols="12" sm="3">
                                        <v-select
                                            v-model="statusFilter"
                                            :items="statusFilterValues"
                                            item-title="title"
                                            item-value="value"
                                            label="Status"
                                            multiple
                                            :disabled="loading"
                                            clearable
                                        ></v-select>
                                    </v-col>

                                    <v-col cols="12" sm="3">
                                        <v-text-field
                                            v-model="firstNameFilter"
                                            label="Vorname"
                                            clearable
                                        ></v-text-field>
                                    </v-col>

                                    <v-col cols="12" sm="3">
                                        <v-text-field
                                            v-model="lastNameFilter"
                                            label="Nachname"
                                            clearable
                                        ></v-text-field>
                                    </v-col>

                                    <v-col cols="12" sm="3">
                                        <v-text-field
                                            v-model="emailFilter"
                                            label="Email"
                                            clearable
                                        ></v-text-field>
                                    </v-col>
                                </v-row>
                            </div>
                        </div>
                    </v-col>

                    <v-col cols="12">
                        <v-data-table-server
                            :items-per-page="-1"
                            :headers="usersHeaders"
                            :items="usersModifiedItems"
                            :search="usersSearch"
                            v-sortable-data-table
                            :loading="loading"
                            class="data-table-container data-table-container-hide-footer elevation-1"
                            item-value="name"
                            @update:options="goToUsersPage"
                        >
                            <template v-slot:item="{ item }">
                                <tr :data-id="item.selectable.id" :data-order="item.selectable.order">
                                    <td align="center">
                                        <v-icon size="medium" :class="{ active: item.selectable.is_online }">mdi-circle</v-icon>
                                    </td>

                                    <td>{{item.selectable.first_name}}</td>

                                    <td>{{item.selectable.last_name}}</td>

                                    <td>{{item.selectable.email}}</td>

                                    <td>{{item.selectable.primary_role_human_name}}</td>

                                    <td align="center">
                                        <v-tooltip location="top">
                                            <template v-slot:activator="{ props }">
                                                <Link :href="`${route('users.edit', { id: item.selectable.id })}?from=operators.show;${operator.id}`">
                                                    <v-icon v-bind="props" size="small" class="tw-me-2">mdi-pencil</v-icon>
                                                </Link>
                                            </template>
                                            <span>Benutzer bearbeiten</span>
                                        </v-tooltip>

                                        <v-tooltip location="top">
                                            <template v-slot:activator="{ props }">
                                                <v-icon v-bind="props" size="small" class="tw-me-2"
                                                        @click="openDeleteUserFromOperatorDialog(item.raw)">mdi-delete
                                                </v-icon>
                                            </template>
                                            <span>Benutzer löschen</span>
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
        </div>
    </AuthenticatedLayout>
</template>
