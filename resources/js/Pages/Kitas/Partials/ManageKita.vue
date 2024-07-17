<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import { computed, ref, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import {Head, useForm, usePage, Link, router} from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { debounce } from 'lodash';

const props = defineProps({
    kita: Object,
    kitaUsers: Array,
    errors: Object,
    roles: Array,
    types: Array,
    operators: Array,
    users: Array,
    // Users table
    currentPage: Number,
    perPage: Number,
    lastPage: Number,
    total: Number,
    paging: Boolean,
    orderBy: String,
    sort: String,
    filters: Object,
    usersEmails: Array,
});


/*
 * Inertia events handling
 */
// For page reloading on pagination or searching
Inertia.on('success', (event) => {
    let newProps = event.detail.page.props;
    let pageType = event.detail.page.component;

    if (pageType === 'Kitas/Partials/ManageKita' && newProps) {
        editedKita.value = newProps.kita;
    }
});


/*
 * Main data
 */
const currentUser = usePage().props.auth.user ?? {};  // Global info about user

const editedKita = ref(props.kita);
const errors = ref(props.errors || {});
const loading = ref(false);
const deletingItemName = ref(null);
const dialog = ref(false);
const connectUserDialog = ref(false);
const dialogDeleteKitaUser = ref(false);

// Computed
watch(dialog, (val) => {
    if (!val) {
        close();
    }
});

// Methods
const close = () => {
    dialog.value = false;
    connectUserDialog.value = false;
    dialogDeleteKitaUser.value = false;
    dialogUsersEmails.value = false;
    manageCreateKitaUserForm.reset();
    manageCreateKitaUserForm.clearErrors();
    manageConnectKitaUserForm.reset();
    manageConnectKitaUserForm.clearErrors();
    errors.value = {};
};

const clear = () => {
    manageCreateKitaUserForm.reset();
    manageCreateKitaUserForm.clearErrors();
    manageConnectKitaUserForm.reset();
    manageConnectKitaUserForm.clearErrors();

    manageForm.reset();
    manageForm.clearErrors();
    manageForm.zip_code = null
    manageForm.name = null
    manageForm.number = null
    manageForm.provider_of_the_kita = null
    manageForm.street = null
    manageForm.house_number = null
    manageForm.additional_info = null
    manageForm.city = null
};

const openDeleteUserFromKitaDialog = (item) => {
    deletingItemName.value = item.full_name
    deleteForm.user = item.id;
    dialogDeleteKitaUser.value = true
};

const deleteForm = useForm({
    user: null,
});

const deleteUserFromKita = async () => {
    deleteForm.processing = true;

    let formOptions = {
        onSuccess: (page) => {
            close();
        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            deleteForm.processing = false;
        },
    };

    deleteForm.post(route('kitas.disconnect_user', {id: props.kita.id}), formOptions);
};

const manageForm = useForm({
    id: editedKita.value.id,
    name: editedKita.value?.name,
    number: editedKita.value?.number,
    street: editedKita.value?.street,
    house_number: editedKita.value?.house_number,
    additional_info: editedKita.value?.additional_info,
    zip_code: editedKita.value?.zip_code,
    operator_id: editedKita.value?.operator_id,
    city: editedKita.value?.city,
    num_pedagogical_staff: editedKita.value?.num_pedagogical_staff,
    approved: editedKita.value?.approved,
    type: editedKita.value?.type,
});

const manageKita = async () => {
    manageForm.processing = true;

    let formOptions = {
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
    };

    manageForm.put(route('kitas.update', {kita: manageForm.id}), formOptions);
};


const manageCreateKitaUserForm = useForm({
    first_name: null,
    last_name: null,
    email: null,
    role: null,
    two_factor_auth_enabled: false,
    phone_number: null,
    kitas: [editedKita.value.id]
});

const manageCreateKitaUser = async () => {
    manageCreateKitaUserForm.processing = true;

    manageCreateKitaUserForm.post(route('users.store_from_kita'), {
        onSuccess: (page) => {
            close();
        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            manageCreateKitaUserForm.processing = false;
        },
    });
};


const manageConnectKitaUserForm = useForm({
    users: null,
    kitas: [editedKita.value.id]
});

const manageConnectKitaUser = async () => {
    manageConnectKitaUserForm.processing = true;

    manageConnectKitaUserForm.post(route('kitas.connect_users', {kita: manageCreateKitaUserForm.kitas}), {
        onSuccess: (page) => {
            close();
        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            manageConnectKitaUserForm.processing = false;
        },
    });
};

/*
 * Users table
 */
const headers = [
  { title: 'Status', key: 'is_online', width: '5%', sortable: false, align: 'center' },
  { title: 'Vorname', key: 'first_name', width: '17.5%', sortable: true },
  { title: 'Nachname', key: 'last_name', width: '17.5%', sortable: true },
  { title: 'E-Mail', key: 'email', width: '25%', sortable: true },
  { title: 'Rolle', key: 'primary_role_name', width: '20%', sortable: true },
  { title: 'Aktion', key: 'actions', width: '15%', sortable: false, align: 'center' },
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

const currentPage = ref(props.currentPage); // Track the current page number
const perPage = ref(props.perPage); // Number of products per page
const orderBy = ref(props.orderBy);
const sort = ref(props.sort);
const totalItems = ref(props.total);
const lastPage = ref(props.lastPage);
const statusFilter = ref(props.filters.status ?? null);
const firstNameFilter = ref(props.filters.first_name ?? null);
const lastNameFilter = ref(props.filters.last_name ?? null);
const emailFilter = ref(props.filters.email ?? null);
const rolesFilter = ref(props.filters.roles ?? null);
const search = ref('');

const selectedUsersEmails = ref([]);
const dialogUsersEmails = ref(false);

const modifiedItems = computed(() => {
    return props.kitaUsers.map(item => {
        const modifiedItem = {...item};
        for (const key in modifiedItem) {
            if (modifiedItem[key] === null || modifiedItem[key] === undefined) {
                modifiedItem[key] = '-';
            }
        }
        return modifiedItem;
    });
});

const allFiltersEmpty = computed(() => {
    return statusFilter.value === null && firstNameFilter.value === null && lastNameFilter.value === null && emailFilter.value === null && rolesFilter.value === null;
});

const someFiltersNotEmpty = computed(() => {
    return statusFilter.value !== null || firstNameFilter.value !== null || lastNameFilter.value !== null || emailFilter.value !== null || rolesFilter.value !== null;
});

watch(statusFilter, (val) => {
    triggerSearch();
});

watch(firstNameFilter, debounce((val) => {
    triggerSearch();
}, 500));

watch(lastNameFilter, debounce((val) => {
    triggerSearch();
}, 500));

watch(emailFilter, debounce((val) => {
    triggerSearch();
}, 500));

watch(rolesFilter, (val) => {
    triggerSearch();
});

const triggerSearch = () => {
    loading.value = true;
    search.value = String(Date.now());
};

const openUsersEmailsDialog = () => {
    dialogUsersEmails.value = true;
    selectedUsersEmails.value = props.usersEmails;
};

const goToPage = async ({ page, itemsPerPage, sortBy, clearFilters }) => {
    if (clearFilters) {
        statusFilter.value = null;
        firstNameFilter.value = null;
        lastNameFilter.value = null;
        emailFilter.value = null;
        rolesFilter.value = null;
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
        if (statusFilter.value) {
            data.status = statusFilter.value;
        }

        if (firstNameFilter.value) {
            data.first_name = firstNameFilter.value;
        }

        if (lastNameFilter.value) {
            data.last_name = lastNameFilter.value;
        }

        if (emailFilter.value) {
            data.email = emailFilter.value;
        }

        if (rolesFilter.value) {
            data.with_roles = rolesFilter.value;
        }

        await router.get(route(route().current(), {kita: manageForm.id}), data, {
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
    <Head :title="`Verwalte Einrichtung ${kita.name}`"/>

    <AuthenticatedLayout :errors="errors">
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">Verwalte Einrichtung {{ kita.name }}</h2>
        </template>

        <div class="tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8">
            <v-container>
                <v-row>
                    <v-col cols="12">
                        <h3>Eigenschaften</h3>
                    </v-col>
                </v-row>
            </v-container>

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
                            label="Typ*"
                            required
                        ></v-select>
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
                            <Link :href="route('kitas.index')">
                                <v-btn class="mr-2" variant="text" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Zurück</v-btn>
                            </Link>
                        </v-hover>
                        <v-hover v-slot:default="{ isHovering, props }">
                            <v-btn-primary @click="manageKita" v-bind="props"
                                           :color="isHovering ? 'accent' : 'primary'">Speichern
                            </v-btn-primary>
                        </v-hover>
                    </v-col>
                </v-row>
            </v-container>

            <v-container>
                <v-row class="tw-border-t-8 tw-mt-8 tw-pt-8">
                    <v-col cols="12" sm="6">
                        <h3>Zugeordnete Benutzer</h3>
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
                                                            <p>Wählen Sie die Benutzer aus, die Sie diesem Einrichtung hinzufügen möchten</p>
                                                        </v-col>
                                                    </v-row>
                                                    <v-row>
                                                        <v-col cols="12">
                                                            <v-autocomplete
                                                                v-model="manageConnectKitaUserForm.users"
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
                                                    <v-btn-primary @click="manageConnectKitaUser" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Speichern</v-btn-primary>
                                                </v-hover>
                                            </v-card-actions>
                                        </v-card>
                                    </v-dialog>
                                </v-btn>
                            </v-hover>

                            <v-hover v-slot:default="{ isHovering, props }">
                                <v-btn v-bind="props" :color="isHovering ? 'accent' : 'primary'" dark>
                                    Benutzer hinzufügen

                                    <v-dialog v-model="dialog" activator="parent" width="80vw">
                                        <v-card height="80vh">
                                            <v-card-title>
                                                <span class="tw-text-h5">Neue Benutzer</span>
                                            </v-card-title>

                                            <v-card-text>
                                                <v-container>
                                                    <v-row>
                                                        <v-col cols="12" sm="6">
                                                            <v-text-field v-model="manageCreateKitaUserForm.first_name" :error-messages="errors.first_name" label="Vorname" required></v-text-field>
                                                        </v-col>

                                                        <v-col cols="12" sm="6">
                                                            <v-text-field v-model="manageCreateKitaUserForm.last_name" :error-messages="errors.last_name" label="Nachname" required></v-text-field>
                                                        </v-col>
                                                    </v-row>


                                                    <v-row>
                                                        <v-col cols="12" sm="6">
                                                            <v-text-field v-model="manageCreateKitaUserForm.email" :error-messages="errors.email" label="Email" required></v-text-field>
                                                        </v-col>

                                                        <v-col cols="12" sm="6">
                                                            <v-text-field v-model="manageCreateKitaUserForm.phone_number" :error-messages="errors.phone_number" label="Telefonnummer"></v-text-field>
                                                        </v-col>
                                                    </v-row>

                                                    <v-row>
                                                        <v-col cols="12" sm="6">
                                                            <v-select
                                                                v-model="manageCreateKitaUserForm.role"
                                                                :items="roles"
                                                                :error-messages="errors.role"
                                                                item-title="human_name"
                                                                item-value="id"
                                                                label="Role"
                                                                required
                                                            ></v-select>
                                                        </v-col>

                                                        <v-col cols="12" md="4" sm="6">
                                                            <v-checkbox
                                                                v-model="manageCreateKitaUserForm.two_factor_auth_enabled"
                                                                label="Zwei-Faktor-Authentifizierung"
                                                                :value="true"
                                                            ></v-checkbox>
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
                                                    <v-btn-primary @click="manageCreateKitaUser" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Speichern</v-btn-primary>
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
                <v-dialog v-model="dialogDeleteKitaUser" width="20vw">
                    <v-card height="30vh">
                        <v-card-text>
                            <v-container>
                                <v-row>
                                    <v-col cols="12">
                                        <p>Sind Sie sicher, dass Sie den Benutzer {{deletingItemName}} aus Einrichtung Liste löschen möchten? (Der Benutzer wird vom aktuellen Einrichtung getrennt)</p>
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
                                <v-btn-primary @click="deleteUserFromKita" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Löschen</v-btn-primary>
                            </v-hover>
                        </v-card-actions>
                    </v-card>
                </v-dialog>

                <v-row>
                    <v-col cols="12">
                        <div class="tw-bg-white tw-flex tw-justify-between tw-px-6 tw-py-6">
                            <div class="tw-w-full">
                                <v-row>
                                    <v-col cols="12" sm="4">
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

                                    <v-col cols="12" sm="4">
                                        <v-text-field
                                            v-model="firstNameFilter"
                                            label="Vorname"
                                            clearable
                                        ></v-text-field>
                                    </v-col>

                                    <v-col cols="12" sm="4">
                                        <v-text-field
                                            v-model="lastNameFilter"
                                            label="Nachname"
                                            clearable
                                        ></v-text-field>
                                    </v-col>
                                </v-row>

                                <v-row>
                                    <v-col cols="12" sm="6">
                                        <v-text-field
                                            v-model="emailFilter"
                                            label="Email"
                                            clearable
                                        ></v-text-field>
                                    </v-col>

                                    <v-col cols="12" sm="6">
                                        <v-select
                                            v-model="rolesFilter"
                                            :items="roles"
                                            item-title="human_name"
                                            item-value="name"
                                            label="Rolle"
                                            multiple
                                            :disabled="loading"
                                            clearable
                                        ></v-select>
                                    </v-col>
                                </v-row>
                            </div>
                        </div>
                    </v-col>

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
                            :sort-by="sortItem"
                            :search="search"
                            v-sortable-data-table
                            :loading="loading"
                            class="data-table-container data-table-container-hide-footer elevation-1"
                            item-value="name"
                            @update:options="goToPage"
                        >
                            <template v-slot:item="{ item }">
                                <tr :data-id="item.selectable?.id" :data-order="item.selectable?.order">
                                    <td align="center">
                                        <v-icon size="medium" :class="{ active: item?.selectable.is_online }">mdi-circle</v-icon>
                                    </td>

                                    <td>{{item.selectable?.first_name}}</td>

                                    <td>{{item.selectable?.last_name}}</td>

                                    <td>{{item.selectable?.email}}</td>

                                    <td>{{item.selectable?.primary_role_human_name}}</td>

                                    <td align="right">
                                        <v-tooltip v-if="kita?.approved && item.selectable?.is_manager" location="top">
                                            <template v-slot:activator="{ props }">
                                                <a :href="`mailto:?bcc=${item.selectable.email}`" v-bind="props">
                                                  <v-icon v-bind="props" size="small" class="tw-me-2">mdi-email</v-icon>
                                                </a>
                                            </template>
                                            <span>Schreibe E-Mail</span>
                                        </v-tooltip>

                                        <v-tooltip location="top">
                                            <template v-slot:activator="{ props }">
                                                <Link :href="`${route('users.edit', { id: item.selectable.id })}?from=kitas.show;${kita.id}`">
                                                    <v-icon v-bind="props" size="small" class="tw-me-2">mdi-pencil</v-icon>
                                                </Link>
                                            </template>
                                            <span>Benutzer bearbeiten</span>
                                        </v-tooltip>

                                        <v-tooltip location="top">
                                            <template v-slot:activator="{ props }">
                                                <v-icon v-bind="props" size="small" class="tw-me-2"
                                                        @click="openDeleteUserFromKitaDialog(item.raw)">mdi-delete
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
        </div>
    </AuthenticatedLayout>
</template>
