<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import { computed, ref, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, usePage, router, Link } from '@inertiajs/vue3';
import { formatDateTime } from '@/Composables/common.js';
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
        searchFilter.value = newProps.filters.search ?? null;
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
const searchFilter = ref(props.filters.search ?? null);
const search = ref('');
const errors = ref(props.errors || {});

const loading = ref(false);

const headers = [
    { title: 'Name', key: 'name', width: '47.5%', sortable: true },
    { title: 'Hinzugefügt am', key: 'created_at', width: '47.5%', sortable: true },
    { title: 'Aktion', key: 'actions', width: '5%', sortable: false, align: 'center' },
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
    return searchFilter.value === null;
});

const someFiltersNotEmpty = computed(() => {
    return searchFilter.value !== null;
});

// Methods
const goToPage = async ({ page, itemsPerPage, sortBy, clearFilters }) => {
    if (clearFilters) {
        searchFilter.value = null;
    }

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
        } else {
            options.data.order_by = null;
            options.data.sort = null;
        }

        // Search filters
        options.data.search = searchFilter.value;

        await router.reload(options);

        currentPage.value = page;
        perPage.value = itemsPerPage;
        loading.value = false;
    }
};
</script>

<template>
    <Head title="Downloadbereich" />

    <AuthenticatedLayout :errors="errors">
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">Downloadbereich</h2>
        </template>

        <div class="tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8">
            <div class="tw-bg-white tw-flex tw-justify-between tw-px-6 tw-py-6">
                <div class="tw-w-full">
                    <v-row>
                        <v-col cols="12" sm="5">
                            <v-text-field v-model="searchFilter" label="Name"></v-text-field>
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
                        >Suche</v-btn>
                    </v-hover>
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
                :loading="loading"
                class="data-table-container elevation-1"
                item-value="name"
                @update:options="goToPage"
            >

                <template v-slot:item="{ item }">
                    <tr :data-id="item.selectable.id" :data-order="item.selectable.order">
                        <td>{{item.selectable.name}}</td>

                        <td>{{formatDateTime(item.selectable.created_at, 'sv-SE')}}</td>

                        <td>
                            <v-tooltip v-if="item.selectable?.path" location="top">
                                <template v-slot:activator="{ props }">
                                    <a :href="item.selectable.path" download>
                                        <v-icon v-bind="props" size="small" class="tw-me-2">mdi-download</v-icon>
                                    </a>
                                </template>
                                <span>Datei herunterladen</span>
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
    </AuthenticatedLayout>
</template>
