<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import { computed, watch, ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, useForm, usePage, Link, router } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    user: Object,
    errors: Object,
    roles: Array,
    kitas: Array,
    operators: Array,
    userKitas: Array,
    userOperators: Array,
    from: String,
    // Users & kitas tables
    currentPage: Number,
    perPage: Number,
    lastPage: Number,
    total: Number,
    paging: Boolean,
    orderBy: String,
    sort: String,
    kitaFilters: Object,
    operatorFilters: Object,
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

    if (pageType === 'Users/Partials/ManageUser' && newProps) {
        editedUser.value = newProps.user;
    }
});


/*
 * Main data
 */
const currentUser = usePage().props.auth.user ?? {};  // Global info about user

const editedUser = ref(props.user);
const errors = ref(props.errors || {});
const loading = ref(false);
const deletingKitaName = ref(null);
const deletingOperatorName = ref(null);
const dialog = ref(false);
const connectKitaDialog = ref(false);
const connectOperatorDialog = ref(false);
const dialogDeleteUserKita = ref(false);
const dialogDeleteUserOperator = ref(false);

watch(dialog, (val) => {
    if (!val) {
        close();
    }
});

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

    return route('users.index');
});

// Methods
const close = () => {
    dialog.value = false;
    connectKitaDialog.value = false;
    connectOperatorDialog.value = false;
    dialogDeleteUserKita.value = false;
    dialogDeleteUserOperator.value = false;
    dialogUsersEmails.value = false;

    manageForm.reset();
    manageForm.clearErrors();
    sendVerificationLinkForm.reset();
    sendVerificationLinkForm.clearErrors();
    manageConnectUserKitaForm.reset();
    manageConnectUserKitaForm.clearErrors();
    manageConnectUserOperatorForm.reset();
    manageConnectUserOperatorForm.clearErrors();
    deleteKitaFromUserForm.reset();
    deleteKitaFromUserForm.clearErrors();
    deleteOperatorFromUserForm.reset();
    deleteOperatorFromUserForm.clearErrors();

    errors.value = {};
};

const clear = () => {
    // manageForm.reset();
    // manageForm.clearErrors();

    manageConnectUserKitaForm.kitas = null;
    manageConnectUserOperatorForm.operators = null;
    deleteKitaFromUserForm.kita = null;
    deleteOperatorFromUserForm.operator = null;

    sendVerificationLinkForm.reset();
    sendVerificationLinkForm.clearErrors();
    manageConnectUserKitaForm.reset();
    manageConnectUserKitaForm.clearErrors();
    manageConnectUserOperatorForm.reset();
    manageConnectUserOperatorForm.clearErrors();
    deleteKitaFromUserForm.reset();
    deleteKitaFromUserForm.clearErrors();
    deleteOperatorFromUserForm.reset();
    deleteOperatorFromUserForm.clearErrors();
};

const manageForm = useForm({
    id: editedUser.value.id,
    first_name: editedUser.value.first_name,
    last_name: editedUser.value.last_name,
    email: editedUser.value.email,
    password: null,
    password_confirmation: null,
    role: editedUser.value.primary_role_id,
    two_factor_auth_enabled: editedUser.value.two_factor_auth_enabled,
    phone_number: editedUser.value.phone_number,
});

const manageUser = async () => {
    manageForm.processing = true;

    // if (!manageForm.password) {
    //     delete manageForm.password;
    // }
    //
    // if (!manageForm.password_confirmation) {
    //     delete manageForm.password_confirmation;
    // }

    manageForm.put(route('users.update', { user: manageForm.id }), {
        preserveState: false,
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

const sendVerificationLinkForm = useForm({
    id: editedUser.value.id,
});

const sendVerificationLink = async () => {
    sendVerificationLinkForm.processing = true;

    let formOptions = {
        onSuccess: (page) => {
            close();
        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            sendVerificationLinkForm.processing = false;
        },
    };

    sendVerificationLinkForm.post(route('users.send_verification_link', { user: sendVerificationLinkForm.id }), formOptions);
};

const sendWelcomeNotificationForm = useForm({
    id: editedUser.value.id,
});

const sendWelcomeNotification = async () => {
    sendWelcomeNotificationForm.processing = true;

    let formOptions = {
        onSuccess: (page) => {
            close();
        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            sendWelcomeNotificationForm.processing = false;
        },
    };

    sendWelcomeNotificationForm.post(route('users.send_welcome_notification', { user: sendWelcomeNotificationForm.id }), formOptions);
};

/*
 * Connect / disconnect Kita from Operator
 */
const manageConnectUserKitaForm = useForm({
    kitas: null,
});

const manageConnectUserKita = async () => {
    manageConnectUserKitaForm.processing = true;

    manageConnectUserKitaForm.post(route('users.connect_kitas', {user: editedUser.value.id}), {
        onSuccess: (page) => {
            close();
        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            manageConnectUserKitaForm.processing = false;
        },
    });
};

const openDeleteKitaFromOperatorDialog = (item) => {
    deletingKitaName.value = item.full_name
    deleteKitaFromUserForm.kita = item.id;
    dialogDeleteUserKita.value = true
};

const deleteKitaFromUserForm = useForm({
    kita: null,
});

const deleteKitaFromUser = async () => {
    deleteKitaFromUserForm.processing = true;

    let formOptions = {
        onSuccess: (page) => {
            close();
        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            deleteKitaFromUserForm.processing = false;
        },
    };

    deleteKitaFromUserForm.post(route('users.disconnect_kita', {user: editedUser.value.id}), formOptions);
};

/*
 * Connect / disconnect Operator form User 
 */
const manageConnectUserOperatorForm = useForm({
    operators: null,
});

const manageConnectUserOperator = async () => {
    manageConnectUserOperatorForm.processing = true;

    manageConnectUserOperatorForm.post(route('users.connect_operators', {user: editedUser.value.id}), {
        onSuccess: (page) => {
            close();
        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            manageConnectUserOperatorForm.processing = false;
        },
    });
};

const openDeleteOperatorFromUserDialog = (item) => {
    deletingOperatorName.value = item.full_name
    deleteOperatorFromUserForm.operator = item.id;
    dialogDeleteUserOperator.value = true
};

const deleteOperatorFromUserForm = useForm({
    operator: null,
});

const deleteOperatorFromUser = async () => {
    deleteOperatorFromUserForm.processing = true;
  
    let formOptions = {
        onSuccess: (page) => {
            close();
        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            deleteOperatorFromUserForm.processing = false;
        },
    };
  
    deleteOperatorFromUserForm.post(route('users.disconnect_operator', {id: editedUser.value.id}), formOptions);
};

/*
 * Kitas & operators tables
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

const kitaHeaders = [
    { title: 'Name', key: 'name', width: '15%', sortable: true },
    { title: `Jährliche Rückmeldung ${new Date().getFullYear()} abgeschlossen`, key: 'has_yearly_evaluations', width: '35%', sortable: true },
    { title: 'Zugelassen', key: 'approved', width: '10%', sortable: true },
    { title: 'Träger', key: 'operator_id', width: '10%', sortable: true },
    { title: 'Typ', key: 'type', width: '10%', sortable: true },
    { title: 'Postleitzahl', key: 'zip_code', width: '10%', sortable: true },
    { title: 'Aktion', key: 'actions', width: '10%', sortable: false, align: 'center' },
];

const operatorsHeaders = [
    { title: 'Name', key: 'name', width: '45%', sortable: true},
    { title: 'Selbstschulend', key: 'self_training', width: '45%', sortable: true },
    { title: 'Aktion', key: 'actions', width: '10%', sortable: false, align: 'center' },
];

const currentPage = ref(props?.currentPage); // Track the current page number
const perPage = ref(props?.perPage); // Number of products per page
const orderBy = ref(props?.orderBy);
const sort = ref(props?.sort);
const totalItems = ref(props?.total);
const lastPage = ref(props?.lastPage);
const kitasSearch = ref('');
const operatorsSearch = ref('');
// Kitas filters
const searchFilter = ref(props?.kitaFilters?.search ?? null);
const hasYearlyEvaluationsFilter = ref(props?.kitaFilters?.has_yearly_evaluations ?? null);
const approvedFilter = ref(props?.kitaFilters?.approved ?? null);
const typeFilter = ref(props?.kitaFilters?.type ?? null);
const zipCodeFilter = ref(props?.kitaFilters?.zip_code ?? null);
// Operators filters
const someOperatorFilter = ref(props.operatorFilters?.some_operator_filter ?? null);

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

const kitasModifiedItems = computed(() => {
    return modifyItems(props.userKitas);
});

const operatorsModifiedItems = computed(() => {
    return modifyItems(props.userOperators);
});

const allKitasFiltersEmpty = computed(() => {
    return searchFilter.value === null && hasYearlyEvaluationsFilter.value === null && approvedFilter.value === null && typeFilter.value === null && zipCodeFilter.value === null;
});

const allOperatorsFiltersEmpty = computed(() => {
    return someOperatorFilter.value === null;
});

const someKitasFiltersNotEmpty = computed(() => {
    return searchFilter.value !== null || hasYearlyEvaluationsFilter.value !== null || approvedFilter.value !== null || typeFilter.value !== null || zipCodeFilter.value !== null;
});

const someOperatorsFiltersNotEmpty = computed(() => {
  return someOperatorFilter.value !== null;
});

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

// Operators filters
watch(someOperatorFilter, (val) => {
    triggerSearch('operators');
});

const triggerSearch = (type) => {
  loading.value = true;

  if (type === 'kitas') {
    kitasSearch.value = String(Date.now());
  } else {
    operatorsSearch.value = String(Date.now());
  }
};

const openUsersEmailsDialog = () => {
    dialogUsersEmails.value = true;
    selectedUsersEmails.value = props.usersEmails.map(item => item.value);
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

const goToOperatorsPage = async ({ page, itemsPerPage, sortBy, clearFilters }) => {
    if (clearFilters) {
        someOperatorFilter.value = null;
    }

    if (
        (page === currentPage.value && clearFilters) ||
        allOperatorsFiltersEmpty ||
        someOperatorsFiltersNotEmpty
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

        goToPage({ operator_args: data }, { page, itemsPerPage, sortBy, clearFilters });
    }
};

const goToPage = async (data, { page, itemsPerPage, sortBy, clearFilters }) => {
    loading.value = true;

    if (!data.kita_args) {
        data.kita_args = {};
    }

    if (!data.operator_args) {
        data.operator_args = {};
    }

    /*
     * Apply filters
     */
    if (props.from) {
        data.from = props.from;
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

    // Apply users filters
    if (someOperatorFilter.value) {
        data.operator_args.status = someOperatorFilter.value;
    }

    await router.get(route(route().current(), {user: manageForm.id}), data, {
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
    <Head title="Benutzer verwalten" />

    <AuthenticatedLayout :errors="errors">
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">Benutzer verwalten</h2>
        </template>

        <div class="tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8">
            <v-container>
                <v-row>
                    <v-col cols="12" class="text-right">
                        <template v-if="!editedUser.email_verified_at || editedUser.email_verified_at === '-'">
                            <v-hover v-slot:default="{ isHovering, props }">
                                <v-btn class="tw-ml-4 tw-mb-4" v-bind="props" :color="isHovering ? 'accent' : 'primary'" dark @click="sendVerificationLink" :disabled="manageForm.processing || sendVerificationLinkForm.processing || sendWelcomeNotificationForm.processing">
                                    <span>Verifizierungsmail erneut senden</span>
                                </v-btn>
                            </v-hover>
                        </template>

                        <template v-if="$page.props.auth.user.id !== editedUser.id">
                            <v-hover v-slot:default="{ isHovering, props }">
                                <v-btn class="tw-ml-4 tw-mb-4" v-bind="props" :color="isHovering ? 'accent' : 'primary'" dark @click="sendWelcomeNotification" :disabled="manageForm.processing || sendVerificationLinkForm.processing || sendWelcomeNotificationForm.processing">
                                    <span>Zulassung senden</span>
                                </v-btn>
                            </v-hover>
                        </template>
                    </v-col>
                </v-row>
            </v-container>

            <v-container>
                <v-row>
                    <v-col cols="12" sm="4">
                        <v-text-field v-model="manageForm.first_name" :error-messages="errors.first_name" label="Vorname" required></v-text-field>
                    </v-col>

                    <v-col cols="12" sm="4">
                        <v-text-field v-model="manageForm.last_name" :error-messages="errors.last_name" label="Nachname" required></v-text-field>
                    </v-col>

                    <v-col cols="12" sm="4">
                        <v-text-field v-model="manageForm.email" :error-messages="errors.email" label="Email" required></v-text-field>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12" sm="4">
                        <v-text-field v-model="manageForm.phone_number" :error-messages="errors.phone_number" label="Telefonnummer"></v-text-field>
                    </v-col>

                    <v-col cols="12" sm="4">
                        <v-text-field type="password" autocomplete="new-password" v-model="manageForm.password" :error-messages="errors.password" label="Passwort" required></v-text-field>
                    </v-col>

                    <v-col cols="12" sm="4">
                        <v-text-field type="password" v-model="manageForm.password_confirmation" :error-messages="errors.password_confirmation" label="Passwort Bestätigung" required></v-text-field>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12" sm="4">
                        <v-select
                            :disabled="$page.props.auth.user.id === editedUser.id"
                            v-model="manageForm.role"
                            :items="roles"
                            :error-messages="errors.role"
                            item-title="human_name"
                            item-value="id"
                            label="Rolle"
                            required
                        ></v-select>
                    </v-col>

                    <v-col cols="12" sm="4">
                        <v-checkbox
                            v-model="manageForm.two_factor_auth_enabled"
                            label="Zwei-Faktor-Authentifizierung"
                            :value="true"
                        ></v-checkbox>
                    </v-col>
                </v-row>
            </v-container>

            <v-container>
                <v-row>
                    <v-col cols="12" md="3" sm="4">
                        <div class="tw-flex tw-justify-between">
                            <v-hover v-slot:default="{ isHovering, props }">
                                <Link :href="backRoute">
                                    <v-btn v-bind="props" :color="isHovering ? 'primary' : 'accent'">Zurück</v-btn>
                                </Link>
                            </v-hover>

                            <v-hover v-slot:default="{ isHovering, props }">
                                <v-btn-primary @click="manageUser" v-bind="props" :color="isHovering ? 'accent' : 'primary'" :disabled="manageForm.processing || sendVerificationLinkForm.processing">
                                    Speichern
                                </v-btn-primary>
                            </v-hover>
                        </div>
                    </v-col>
                </v-row>
            </v-container>

            <!-- Kitas table -->
            <template v-if="editedUser.is_manager || editedUser.is_employer || editedUser.is_user_multiplier">
                <v-container>
                    <v-row class="tw-border-t-8 tw-mt-8 tw-pt-8">
                        <v-col cols="12" sm="6">
                            <h3>Zugeordnete Einrichtungen</h3>
                        </v-col>

                        <v-col cols="12" sm="6">
                            <div class="tw-flex tw-items-center tw-justify-end">
                                <v-hover v-slot:default="{ isHovering, props }">
                                    <v-btn v-bind="props" :color="isHovering ? 'accent' : 'primary'" dark class="mr-2">
                                        Einrichtungen verbinden

                                        <v-dialog v-model="connectKitaDialog" activator="parent" width="80vw">
                                            <v-card height="80vh">
                                                <v-card-title>
                                                    <span class="tw-text-h5">Einrichtungen verbinden</span>
                                                </v-card-title>

                                                <v-card-text>
                                                    <v-container>
                                                        <v-row>
                                                            <v-col cols="12">
                                                                <p>Wählen Sie die Einrichtungen aus, die Sie diesem Träger hinzufügen möchten</p>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row>
                                                            <v-col cols="12">
                                                                <v-autocomplete
                                                                    v-model="manageConnectUserKitaForm.kitas"
                                                                    :items="kitas"
                                                                    :error-messages="errors.kitas"
                                                                    item-title="name"
                                                                    item-value="id"
                                                                    label="KiTas"
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
                                                        <v-btn-primary @click="manageConnectUserKita" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Speichern</v-btn-primary>
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
                    <v-dialog v-model="dialogDeleteUserKita" width="20vw">
                        <v-card height="30vh">
                            <v-card-text>
                                <v-container>
                                    <v-row>
                                        <v-col cols="12">
                                            <p>Möchten Sie das Objekt {{deletingKitaName}} wirklich aus der Einrichtungsliste löschen?</p>
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
                                   <v-btn-primary @click="deleteKitaFromUser" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Löschen</v-btn-primary>
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
                                :loading="loading"
                                class="data-table-container data-table-container-hide-footer elevation-1"
                                item-value="name"
                                @update:options="goToKitasPage"
                            >
                                <template v-slot:item="{ item }">
                                    <tr :data-id="item.id" :data-order="item.order">
                                        <td>{{item?.name}}</td>

                                        <td>{{item?.has_yearly_evaluations ? 'Ja' : 'Nein'}}</td>

                                        <td>{{item?.approved ? 'Ja' : 'Nein'}}</td>

                                        <td>{{item?.operator?.name ?? '-'}}</td>

                                        <td>{{item?.formatted_type ?? item?.type}}</td>

                                        <td>{{item?.zip_code}}</td>

                                        <td class="text-center">
                                            <v-tooltip location="top">
                                                <template v-slot:activator="{ props }">
                                                    <Link :href="`${route('kitas.show', { id: item.id })}?from=users.edit;user;${editedUser.id}`">
                                                        <v-icon v-bind="props" size="small" class="tw-me-2">mdi-pencil</v-icon>
                                                    </Link>
                                                </template>
                                                <span>Einrichtung bearbeiten</span>
                                            </v-tooltip>

                                            <v-tooltip location="top">
                                                <template v-slot:activator="{ props }">
                                                    <v-icon v-bind="props" size="small" class="tw-me-2"
                                                            @click="openDeleteKitaFromOperatorDialog(item)">mdi-delete
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
            </template>

            <!-- Operators table -->
            <template v-if="editedUser.is_user_multiplier">
                <v-container>
                    <v-row class="tw-border-t-8 tw-mt-8 tw-pt-8">
                        <v-col cols="12" sm="6">
                            <h3>Zugewiesener Träger</h3>
                        </v-col>

                        <v-col cols="12" sm="6">
                            <div class="tw-flex tw-items-center tw-justify-end">
                                <v-hover v-slot:default="{ isHovering, props }">
                                    <v-btn v-bind="props" :color="isHovering ? 'accent' : 'primary'" dark class="mr-2">
                                        Träger verbinden

                                        <v-dialog v-model="connectOperatorDialog" activator="parent" width="80vw">
                                            <v-card height="80vh">
                                                <v-card-title>
                                                    <span class="tw-text-h5">Träger verbinden</span>
                                                </v-card-title>

                                                <v-card-text>
                                                    <v-container>
                                                        <v-row>
                                                            <v-col cols="12">
                                                                <p>Wählen Sie die Träger aus, die Sie diesem Träger hinzufügen möchten</p>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row>
                                                            <v-col cols="12">
                                                                <v-autocomplete
                                                                    v-model="manageConnectUserOperatorForm.operators"
                                                                    :items="operators"
                                                                    :error-messages="errors.operators"
                                                                    item-title="name"
                                                                    item-value="id"
                                                                    label="Träger"
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
                                                        <v-btn-primary @click="manageConnectUserOperator" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Speichern</v-btn-primary>
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
                <v-dialog v-model="dialogDeleteUserOperator" width="20vw">
                    <v-card height="30vh">
                        <v-card-text>
                            <v-container>
                                <v-row>
                                    <v-col cols="12">
                                        <p>Sind Sie sicher, dass Sie den Träger {{deletingOperatorName}} aus Benutzer Liste löschen möchten?</p>
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
                                <v-btn-primary @click="deleteOperatorFromUser" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Löschen</v-btn-primary>
                            </v-hover>
                        </v-card-actions>
                    </v-card>
                </v-dialog>

                <v-row>
                    <v-col cols="12">
                        <v-data-table-server
                            :items-per-page="-1"
                            :headers="operatorsHeaders"
                            :items="operatorsModifiedItems"
                            :search="operatorsSearch"
                            :loading="loading"
                            class="data-table-container data-table-container-hide-footer elevation-1"
                            item-value="name"
                            @update:options="goToOperatorsPage"
                        >
                            <template v-slot:item="{ item }">
                                <tr :data-id="item.id" :data-order="item.order">
                                    <td>{{item.name}}</td>

                                    <td>{{item.self_training ? 'Ja' : 'Nein' }}</td>

                                    <td align="center">
                                        <v-tooltip location="top">
                                            <template v-slot:activator="{ props }">
                                                <Link :href="`${route('operators.show', { id: item.id })}?from=users.edit;user;${editedUser.id}`">
                                                    <v-icon v-bind="props" size="small" class="tw-me-2">mdi-pencil</v-icon>
                                                </Link>
                                            </template>
                                            <span>Träger bearbeiten</span>
                                        </v-tooltip>

                                        <v-tooltip location="top">
                                            <template v-slot:activator="{ props }">
                                                <v-icon v-bind="props" size="small" class="tw-me-2"
                                                        @click="openDeleteOperatorFromUserDialog(item)">mdi-delete
                                                </v-icon>
                                            </template>
                                            <span>Träger löschen</span>
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
        </div>
    </AuthenticatedLayout>
</template>
