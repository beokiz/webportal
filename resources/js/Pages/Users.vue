<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import { computed, ref, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { Head, useForm, usePage, router } from '@inertiajs/vue3';
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
    roles: Array,
});


/*
 * Inertia events handling
 */
// For page reloading on pagination or searching
Inertia.on('success', (event) => {
    let newProps = event.detail.page.props;
    let pageType = event.detail.page.component;

    if (pageType === 'Users' && newProps) {
        currentPage.value = newProps.currentPage;
        perPage.value = newProps.perPage;
        orderBy.value = newProps.orderBy;
        sort.value = newProps.sort;
        totalItems.value = newProps.total;
        lastPage.value = newProps.lastPage;
        fullNameFilter.value = newProps.filters.full_name ?? null;
        emailFilter.value = newProps.filters.email ?? null;
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
const search = ref('');
const errors = ref(props.errors || {});

const loading = ref(false);
const dialog = ref(false);
const editedIndex = ref(-1);

const headers = [
    { title: 'First Name', key: 'first_name', width: '20%', sortable: true },
    { title: 'Role', key: 'is_client', width: '20%', sortable: true },
    { title: 'Last Name', key: 'last_name', width: '20%', sortable: true, align: 'start' },
    { title: 'Email', key: 'email', width: '20%', sortable: true },
    { title: 'Actions', key: 'actions', width: '10%', sortable: false },
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
    return fullNameFilter.value === null && emailFilter.value === null;
});

const someFiltersNotEmpty = computed(() => {
    return fullNameFilter.value !== null || emailFilter.value !== null;
});

const formTitle = computed(() => {
    return editedIndex.value === -1 ? 'New Manager' : 'Edit Manager';
});


//Watch
watch(dialog, (val) => {
    if (!val) {
        close();
    }
});

// Methods
const goToPage = async ({ page, itemsPerPage, sortBy, clearFilters }) => {
    // TODO: Rewrite!!!
    if (clearFilters) {
        fullNameFilter.value = null;
        emailFilter.value = null;
    }

    // TODO: Rewrite!!!
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
        options.data.full_name = fullNameFilter.value;
        options.data.email = emailFilter.value;

        await router.reload(options);

        currentPage.value = page;
        perPage.value = itemsPerPage;
        loading.value = false;
    }
};

const checkUserRole = (user) => {
    let roleName;

    switch (true) {
        case user.raw.is_super_admin:
            roleName = 'Super admin';
            break;
        case user.raw.is_admin:
            roleName = 'Admin';
            break;
        case user.raw.is_client:
            roleName = 'Client';
            break;
        default:
            roleName = '-';
            break;
    }

    return roleName;
};

const editItem = (item) => {
    editedIndex.value = modifiedItems.value.indexOf(item);

    Object.assign(manageForm, {
        id: item.id,
        first_name: item.first_name,
        last_name: item.last_name,
        email: item.email,
        password: item.password,
        role: item.roles.length > 0 ? item.roles[0].id : null,
    });

    dialog.value = true;
};

const close = () => {
    dialog.value = false;
    manageForm.reset();
    manageForm.clearErrors();
    editedIndex.value = -1;

    errors.value = {};
};

const manageForm = useForm({
    id: null,
    first_name: null,
    last_name: null,
    email: null,
    password: null,
    password_confirmation: null,
    role: null,
});

const manageUser = async () => {
    manageForm.processing = true;

    if (!manageForm.password) {
        delete manageForm.password;
    }

    if (!manageForm.password_confirmation) {
        delete manageForm.password_confirmation;
    }

    let formOptions = {
        // preserveScroll: true,
        // preserveState: true,
        // resetOnSuccess: false,
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

    if (editedIndex.value === -1) {
        manageForm.post(route('users.store'), formOptions);
    } else {
        manageForm.put(route('users.update', {user: manageForm.id}), formOptions);
    }
};
</script>

<template>
    <Head title="Users" />

    <AuthenticatedLayout :errors="errors">
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">Users</h2>

            <div class="tw-flex tw-items-center tw-justify-end tw-mb-6">
                <v-hover v-slot:default="{ isHovering, props }">
                    <v-btn v-bind="props" :color="isHovering ? 'accent' : 'primary'" dark>
                        Add new manager

                        <v-dialog v-model="dialog" activator="parent" width="80vw">
                            <v-card height="80vh">
                                <v-card-title>
                                    <span class="tw-text-h5">{{ formTitle }}</span>
                                </v-card-title>

                                <v-card-text>
                                    <v-container>
                                        <v-row>
                                            <v-col cols="12" sm="6">
                                                <v-text-field v-model="manageForm.first_name" :error-messages="errors.first_name" label="First Name" required></v-text-field>
                                            </v-col>
                                            <v-col cols="12" sm="6">
                                                <v-text-field v-model="manageForm.last_name" :error-messages="errors.last_name" label="Last Name" required></v-text-field>
                                            </v-col>
                                        </v-row>


                                        <v-row>
                                            <v-col cols="12" md="4" sm="6">
                                                <v-text-field v-model="manageForm.email" :error-messages="errors.email" label="Email" required></v-text-field>
                                            </v-col>
                                            <v-col cols="12" sm="4">
                                                <v-text-field type="password" autocomplete="new-password" v-model="manageForm.password" :error-messages="errors.password" label="Password" required></v-text-field>
                                            </v-col>
                                            <v-col cols="12" sm="4">
                                                <v-text-field type="password" v-model="manageForm.password_confirmation" :error-messages="errors.password_confirmation" label="Password Confirmation" required></v-text-field>
                                            </v-col>
                                        </v-row>

                                        <v-row>
                                            <v-col cols="12" sm="4">
                                                <v-select
                                                    v-model="manageForm.role"
                                                    :items="roles"
                                                    :error-messages="errors.role"
                                                    item-title="name"
                                                    item-value="id"
                                                    label="Role"
                                                    required
                                                ></v-select>
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </v-card-text>

                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-hover v-slot:default="{ isHovering, props }">
                                        <v-btn @click="close" v-bind="props" :color="isHovering ? 'accent' : 'primary'" variant="elevated">Cancel</v-btn>
                                    </v-hover>
                                    <v-hover v-slot:default="{ isHovering, props }">
                                        <v-btn @click="manageUser" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Save</v-btn>
                                    </v-hover>

                                    <v-btn-primary @click="close" v-bind="props">Cancel</v-btn-primary>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-btn>
                </v-hover>
            </div>
        </template>

        <div class="tw-table-block tw-max-w-7xl tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8">
            <div class="tw-bg-white tw-flex tw-justify-between tw-px-6 tw-py-6">
                <div class="tw-w-full">
                    <v-row>
                        <v-col cols="12" sm="3">
                            <v-text-field v-model="fullNameFilter" label="Name"></v-text-field>
                        </v-col>

                        <v-col cols="12" sm="3">
                            <v-text-field v-model="emailFilter" label="Email"></v-text-field>
                        </v-col>
                    </v-row>
                </div>

                <div class="tw-ml-6">
                    <v-hover v-slot:default="{ isHovering, props }">
                        <v-btn
                            class="tw-mt-2"
                            v-bind="props"
                            :color="isHovering ? 'accent' : 'primary'"
                            @click="search = String(Date.now())"
                            dark
                        >Search</v-btn>
                    </v-hover>
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
                        <td>{{item.selectable.first_name}}</td>

                        <td>{{checkUserRole(item)}}</td>

                        <td>{{item.selectable.last_name}}</td>

                        <td>{{item.selectable.email}}</td>

                        <td>
                            <v-tooltip location="top">
                                <template v-slot:activator="{ props }">
                                    <v-icon v-bind="props" size="small" class="tw-me-2" @click="editItem(item.raw)">mdi-pencil</v-icon>
                                </template>
                                <span>Edit user</span>
                            </v-tooltip>
                        </td>
                    </tr>
                </template>

                <template v-slot:no-data>
                    <div class="tw-py-6">
                        <template v-if="allFiltersEmpty">
                            <h3 class="tw-mb-4">Table is empty</h3>
                        </template>
                        <template v-else>
                            <h3 class="tw-mb-4">Table is empty. Please, reset search filters</h3>

                            <v-btn color="primary" @click="goToPage({ page: 1, itemsPerPage: perPage, clearFilters: true })">Reset</v-btn>
                        </template>
                    </div>
                </template>

            </v-data-table-server>
        </div>
    </AuthenticatedLayout>
</template>
