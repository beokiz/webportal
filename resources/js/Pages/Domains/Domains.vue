<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, useForm, usePage, router, Link } from '@inertiajs/vue3';
import Sortable from 'sortablejs';
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

    if (pageType === 'Domains/Domains' && newProps) {
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
const draggableItem = ref(null);

const loading = ref(false);
const dialog = ref(false);
const dialogDeleteDomain = ref(false);
const deletingItemName = ref(null);

const headers = [
    { title: 'Kürzel', key: 'is_online', width: '15%', sortable: false},
    { title: 'Name', key: 'first_name', width: '75%', sortable: false },
    { title: 'Aktion', key: 'actions', width: '10%', sortable: false, align: 'center'},
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

const checkDazInputState = computed(() => {
    return !manageForm.daz_dependent ? 6 : 3;
});

const allFiltersEmpty = computed(() => {
    return searchFilter.value === null;
});

const someFiltersNotEmpty = computed(() => {
    return searchFilter.value !== null;
});

onMounted(() => {
    const options = {
        handle: '.v-data-table tbody .glyphicon-move',
        animation: 150,
        onUpdate: function (event) {
            saveNewOrder(event);
        },
    };
    Sortable.create(document.querySelector('.v-data-table tbody'), options);
});

// Watch
watch(dialog, (val) => {
    if (!val) {
        close();
    }
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
        }

        // Search filters
        options.data.search = searchFilter.value;

        await router.reload(options);

        currentPage.value = page;
        perPage.value = itemsPerPage;
        loading.value = false;
    }
};

const openDeleteDomainDialog = (item) => {
    deletingItemName.value = item.name
    deleteForm.id = item.id;
    dialogDeleteDomain.value = true
};

const deleteForm = useForm({
    id: null,
});

const deleteDomain = async () => {
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

    deleteForm.delete(route('domains.destroy', { id: deleteForm.id }), formOptions);
};

const close = () => {
    dialog.value = false;
    dialogDeleteDomain.value = false;
    manageForm.reset();
    manageForm.clearErrors();
    manageForm.daz_dependent = false

    errors.value = {};
};

const clear = () => {
    manageForm.reset();
    manageForm.clearErrors();
    manageForm.daz_dependent = false
};


const manageForm = useForm({
    name: null,
    abbreviation: null,
    // order: null,
    age_2_red_threshold: null,
    age_2_red_threshold_daz: null,
    age_2_yellow_threshold: null,
    age_2_yellow_threshold_daz: null,
    age_4_red_threshold: null,
    age_4_red_threshold_daz: null,
    age_4_yellow_threshold: null,
    age_4_yellow_threshold_daz: null,
    daz_dependent: false,
});

const manageDomain = async () => {
    manageForm.processing = true;

    if(!manageForm.daz_dependent){
        manageForm.age_2_red_threshold_daz = manageForm.age_2_red_threshold
        manageForm.age_2_yellow_threshold_daz = manageForm.age_2_yellow_threshold
        manageForm.age_4_red_threshold_daz = manageForm.age_4_red_threshold
        manageForm.age_4_yellow_threshold_daz = manageForm.age_4_yellow_threshold
    }

    manageForm.post(route('domains.store'), {
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

const reorderForm = useForm({
    items: [],
});

const saveNewOrder = (event) => {
    reorderForm.processing = true;

    let items = [];
    let pageIndex = 0;

    if (currentPage.value > 1) {
        pageIndex = (10 * currentPage.value) - 10;
    }

    [].forEach.call(event.from.querySelectorAll('tr'), function (el,index) {
        el.setAttribute("data-order", pageIndex + index);

        items.push({
            id: el.getAttribute('data-id'),
            order: pageIndex + index,
        });
    });

    reorderForm.items = items;

    reorderForm.post(route('domains.reorder'), {
        preserveScroll: true,
        preserveState: false,
        onSuccess: (page) => {
            //
        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            reorderForm.processing = false;
        },
    });
};
</script>

<template>
    <Head title="Domänen"/>

    <AuthenticatedLayout :errors="errors">
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">Domänen</h2>

            <div class="tw-flex tw-items-center tw-justify-end">
                <v-hover v-slot:default="{ isHovering, props }">
                    <v-btn v-bind="props" :color="isHovering ? 'accent' : 'primary'" dark>
                        Anlegen

                        <v-dialog v-model="dialog" activator="parent" width="80vw">
                            <v-card height="80vh">
                                <v-card-title>
                                    <span class="tw-text-h5">Verwalte Domäne</span>
                                </v-card-title>

                                <v-card-text>
                                    <v-container>
                                        <v-row>
                                            <v-col cols="12" sm="3">
                                                <v-text-field v-model="manageForm.abbreviation" :error-messages="errors.abbreviation"
                                                              label="Kürzel*" required></v-text-field>
                                            </v-col>
                                            <v-col cols="12" sm="6">
                                                <v-text-field v-model="manageForm.name" :error-messages="errors.name"
                                                              label="Name der Domäne*" required></v-text-field>
                                            </v-col>
                                            <v-col cols="12" sm="3">
                                                <v-switch
                                                    v-model="manageForm.daz_dependent"
                                                    hide-details
                                                    label="Einstellen mit Daz"
                                                ></v-switch>
                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col cols="12">
                                                <p>Schwellenwerte für Altersgruppe bis 2,5 Jahre</p>
                                            </v-col>
                                            <v-col cols="12" :sm="checkDazInputState">
                                                <v-text-field v-model="manageForm.age_2_red_threshold"
                                                              :error-messages="errors.age_2_red_threshold"
                                                              label="Schwellwert Rot*"
                                                              placeholder="z.B. 5"
                                                              type="number"
                                                              required></v-text-field>
                                            </v-col>
                                            <v-col v-if="manageForm.daz_dependent" cols="12" :sm="checkDazInputState">
                                                <v-text-field v-model="manageForm.age_2_red_threshold_daz"
                                                              :error-messages="errors.age_2_red_threshold_daz"
                                                              label="Schwellwert Rot mit Daz*"
                                                              placeholder="z.B. 3"
                                                              type="number"
                                                              required></v-text-field>
                                            </v-col>
                                            <v-col cols="12" :sm="checkDazInputState">
                                                <v-text-field v-model="manageForm.age_2_yellow_threshold"
                                                              :error-messages="errors.age_2_yellow_threshold"
                                                              label="Schwellwert Gelb*"
                                                              placeholder="z.B. 10"
                                                              type="number"
                                                              required></v-text-field>
                                            </v-col>
                                            <v-col v-if="manageForm.daz_dependent" cols="12" :sm="checkDazInputState">
                                                <v-text-field v-model="manageForm.age_2_yellow_threshold_daz"
                                                              :error-messages="errors.age_2_yellow_threshold_daz"
                                                              label="Schwellwert Gelb mit Daz*"
                                                              placeholder="z.B. 8"
                                                              type="number"
                                                              required></v-text-field>
                                            </v-col>
                                        </v-row>

                                        <v-row>
                                            <v-spacer></v-spacer>
                                            <v-col cols="12">
                                                <p>Schwellenwerte für Altersgruppe bis 4,5 Jahre</p>
                                            </v-col>
                                            <v-col cols="12" :sm="checkDazInputState">
                                                <v-text-field v-model="manageForm.age_4_red_threshold"
                                                              :error-messages="errors.age_4_red_threshold"
                                                              label="Schwellwert Rot*"
                                                              placeholder="z.B. 5"
                                                              type="number"
                                                              required></v-text-field>
                                            </v-col>
                                            <v-col v-if="manageForm.daz_dependent" cols="12" :sm="checkDazInputState">
                                                <v-text-field v-model="manageForm.age_4_red_threshold_daz"
                                                              :error-messages="errors.age_4_red_threshold_daz"
                                                              label="Schwellwert Rot mit Daz*"
                                                              placeholder="z.B. 3"
                                                              type="number"
                                                              required></v-text-field>
                                            </v-col>
                                            <v-col cols="12" :sm="checkDazInputState">
                                                <v-text-field v-model="manageForm.age_4_yellow_threshold"
                                                              :error-messages="errors.age_4_yellow_threshold"
                                                              label="Schwellwert Gelb*"
                                                              placeholder="z.B. 10"
                                                              type="number"
                                                              required></v-text-field>
                                            </v-col>
                                            <v-col v-if="manageForm.daz_dependent" cols="12" :sm="checkDazInputState">
                                                <v-text-field v-model="manageForm.age_4_yellow_threshold_daz"
                                                              :error-messages="errors.age_4_yellow_threshold_daz"
                                                              label="Schwellwert Gelb mit Daz*"
                                                              placeholder="z.B. 8"
                                                              type="number"
                                                              required></v-text-field>
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
                                        <v-btn-primary @click="manageDomain" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Speichern</v-btn-primary>
                                    </v-hover>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-btn>
                </v-hover>
            </div>

            <v-dialog v-model="dialogDeleteDomain" width="20vw">
                <v-card height="30vh">
                    <v-card-text>
                        <v-container>
                            <v-row>
                                <v-col cols="12">
                                    <p>Sind Sie sicher, dass Sie die Domäne {{deletingItemName}} löschen möchten?</p>
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
                            <v-btn-primary @click="deleteDomain" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Löschen</v-btn-primary>
                        </v-hover>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </template>

        <div class="tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8">
            <div class="tw-bg-white tw-flex tw-justify-between tw-px-6 tw-py-6">
                <div class="tw-w-full">
                    <v-row>
                        <v-col cols="12" sm="5">
                            <v-text-field v-model="searchFilter" label="Kürzel/Name"></v-text-field>
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
                  {value: 10, title: '10'},
                  {value: 25, title: '25'},
                  {value: 50, title: '50'},
                  {value: 100, title: '100'},
                  {value: -1, title: '$vuetify.dataFooter.itemsPerPageAll'}
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
                        <td>{{item.selectable.abbreviation}}</td>

                        <td>{{item.selectable.name}}</td>

                        <td>
                            <v-tooltip location="top">
                                <template v-slot:activator="{ props }">
                                    <v-icon
                                        draggable="true"
                                        @dragstart="draggableItem = item.raw"
                                        color="primary" v-bind="props" size="small" class="tw-me-2 glyphicon-move">mdi-arrow-collapse-vertical</v-icon>
                                </template>
                                <span>neu anordnen</span>
                            </v-tooltip>


                            <v-tooltip location="top">
                                <template v-slot:activator="{ props }">
                                    <Link :href="route('domains.show', { id: item.selectable.id })">
                                        <v-icon v-bind="props" size="small" class="tw-me-2">mdi-eye</v-icon>
                                    </Link>
                                </template>
                                <span>Domäne ansehen</span>
                            </v-tooltip>

                            <v-tooltip location="top">
                                <template v-slot:activator="{ props }">
                                    <v-icon v-bind="props" size="small" class="tw-me-2" @click="openDeleteDomainDialog(item.raw)">mdi-delete</v-icon>
                                </template>
                                <span>Domäne löschen</span>
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
