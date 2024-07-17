<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import { computed, ref, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, useForm, usePage, router, Link } from '@inertiajs/vue3';
import { formatDate, formatDateTime } from '@/Composables/common';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {debounce} from "lodash";

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
    roles: Array,
});


/*
 * Inertia events handling
 */
// For page reloading on pagination or searching
Inertia.on('success', (event) => {
    let newProps = event.detail.page.props;
    let pageType = event.detail.page.component;

    if (pageType === 'Users/Users' && newProps) {
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
const fullNameFilter = ref(props.filters.full_name ?? null);
const emailFilter = ref(props.filters.email ?? null);
const rolesFilter = ref(props.filters.roles ?? null);
const search = ref('');
const errors = ref(props.errors || {});

const loading = ref(false);
const dialog = ref(false);
const dialogDeleteUser = ref(false);

const headers = [
    { title: 'Status', key: 'is_online', width: '5%', sortable: false, align: 'center' },
    { title: 'Name', key: 'first_name', width: '25%', sortable: true },
    { title: 'Email', key: 'email', width: '20%', sortable: true },
    { title: 'Rolle', key: 'primary_role_name', width: '10%', sortable: true },
    { title: 'Letzter Login', key: 'last_seen_at', width: '15%', sortable: true },
    { title: 'Erster Login', key: 'first_login_at', width: '15%', sortable: true },
    { title: 'Aktionen', key: 'actions', width: '10%', sortable: false },
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
    return fullNameFilter.value === null && emailFilter.value === null && rolesFilter.value === null;
});

const someFiltersNotEmpty = computed(() => {
    return fullNameFilter.value !== null || emailFilter.value !== null || rolesFilter.value !== null;
});


// Watch
watch(dialog, (val) => {
    if (!val) {
        close();
    }
});

watch(fullNameFilter, debounce((val) => {
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

// Methods
const goToPage = async ({ page, itemsPerPage, sortBy, clearFilters }) => {
    if (clearFilters) {
        fullNameFilter.value = null;
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
        if (fullNameFilter.value) {
            data.full_name = fullNameFilter.value;
        }

        if (emailFilter.value) {
            data.email = emailFilter.value;
        }

        if (rolesFilter.value) {
            data.with_roles = rolesFilter.value;
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

const openDeleteUserDialog = (item) => {
    deleteForm.id = item.id;
    dialogDeleteUser.value = true
};

const deleteForm = useForm({
    id: null,
});

const deleteUser = async () => {
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

    deleteForm.delete(route('users.destroy', { id: deleteForm.id }), formOptions);
};

const close = () => {
    dialog.value = false;
    dialogDeleteUser.value = false;
    deleteForm.id = null;
    manageForm.reset();
    manageForm.clearErrors();

    errors.value = {};
};

const manageForm = useForm({
    first_name: null,
    last_name: null,
    email: null,
    role: null,
    two_factor_auth_enabled: false,
    phone_number: null,
});

const manageUser = async () => {
    manageForm.processing = true;

    let formOptions = {
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

    manageForm.post(route('users.store'), formOptions);
};
</script>

<template>
    <Head title="Benutzer" />

    <AuthenticatedLayout :errors="errors">
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">Benutzer</h2>

            <div class="tw-flex tw-items-center tw-justify-end">
                <v-hover v-slot:default="{ isHovering, props }">
                    <v-btn v-bind="props" :color="isHovering ? 'accent' : 'primary'" dark>
                        Neuen Benutzer hinzufügen

                        <v-dialog v-model="dialog" activator="parent" width="80vw">
                            <v-card height="80vh">
                                <v-card-title>
                                    <span class="tw-text-h5">Neuen Benutzer</span>
                                </v-card-title>

                                <v-card-text>
                                    <v-container>
                                        <v-row>
                                            <v-col cols="12" sm="6">
                                                <v-text-field v-model="manageForm.first_name" :error-messages="errors.first_name" label="Vorname" required></v-text-field>
                                            </v-col>

                                            <v-col cols="12" sm="6">
                                                <v-text-field v-model="manageForm.last_name" :error-messages="errors.last_name" label="Nachname" required></v-text-field>
                                            </v-col>
                                        </v-row>

                                        <v-row>
                                            <v-col cols="12" sm="6">
                                                <v-text-field v-model="manageForm.email" :error-messages="errors.email" label="Email" required></v-text-field>
                                            </v-col>

                                            <v-col cols="12" sm="6">
                                                <v-text-field v-model="manageForm.phone_number" :error-messages="errors.phone_number" label="Telefonnummer"></v-text-field>
                                            </v-col>
                                        </v-row>

                                        <v-row>
                                            <v-col cols="12" sm="6">
                                                <v-select
                                                    v-model="manageForm.role"
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
                                                    v-model="manageForm.two_factor_auth_enabled"
                                                    label="Zwei-Faktor-Authentifizierung"
                                                    :value="true"
                                                ></v-checkbox>
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
                                        <v-btn-primary @click="manageUser" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Speichern</v-btn-primary>
                                    </v-hover>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-btn>
                </v-hover>
            </div>

            <v-dialog v-model="dialogDeleteUser" width="20vw">
                <v-card height="30vh">
                    <v-card-text>
                        <v-container>
                            <v-row>
                                <v-col cols="12">
                                    <p>Sind Sie sicher, dass Sie den aktuellen Benutzer löschen möchten?</p>
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
                            <v-btn-primary @click="deleteUser" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Löschen</v-btn-primary>
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
                            <v-text-field
                                v-model="fullNameFilter"
                                label="Name"
                                clearable
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" sm="4">
                            <v-text-field
                                v-model="emailFilter"
                                label="Email"
                                clearable
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" sm="4">
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

            <v-data-table-server
                v-model:items-per-page="perPage"
                :headers="headers"
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
                    <tr>
                        <td align="center">
                            <v-icon size="medium" :class="{ active: item.selectable.is_online }">mdi-circle</v-icon>
                        </td>

                        <td>{{item.selectable.full_name}}</td>

                        <td>{{item.selectable.email}}</td>

                        <td>{{item.selectable.primary_role_human_name}}</td>

                        <td>{{!item.selectable.last_seen_at || item.selectable.last_seen_at === '-' ? item.selectable.last_seen_at : formatDateTime(item.selectable.last_seen_at, 'sv-SE')}}</td>

                        <td>{{!item.selectable.first_login_at || item.selectable.first_login_at === '-' ? item.selectable.first_login_at : formatDate(item.selectable.first_login_at, 'fr-CA')}}</td>

                        <td>
                            <v-tooltip v-if="$page.props.auth.user.is_super_admin || (($page.props.auth.user.is_admin || $page.props.auth.user.is_manager) && !item.selectable.is_super_admin && !item.selectable.is_admin) || ($page.props.auth.user.is_admin && $page.props.auth.user.id === item.selectable.id)" location="top">
                                <template v-slot:activator="{ props }">
                                    <Link :href="route('users.edit', { id: item.selectable.id })">
                                        <v-icon v-bind="props" size="small" class="tw-me-2">mdi-pencil</v-icon>
                                    </Link>
                                </template>
                                <span>Benutzer bearbeiten</span>
                            </v-tooltip>

                            <v-tooltip v-if="$page.props.auth.user.is_super_admin || ($page.props.auth.user.is_admin || $page.props.auth.user.is_manager && !item.selectable.is_super_admin && !item.selectable.is_admin)" location="top">
                                <template v-slot:activator="{ props }">
                                    <v-icon v-bind="props" size="small" class="tw-me-2" @click="openDeleteUserDialog(item.raw)">mdi-delete</v-icon>
                                </template>
                                <span>Benutzer löschen</span>
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

                            <v-btn color="primary" @click="goToPage({ page: 1, itemsPerPage: perPage, clearFilters: true })">Zurücksetzen</v-btn>
                        </template>
                    </div>
                </template>

            </v-data-table-server>
        </div>
    </AuthenticatedLayout>
</template>
