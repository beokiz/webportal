<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import {computed, onMounted, ref, watch} from "vue";
import { Inertia } from "@inertiajs/inertia";
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
});

/*
 * Inertia events handling
 */
// For page reloading on pagination or searching
Inertia.on('success', (event) => {
    let newProps = event.detail.page.props;
    let pageType = event.detail.page.component;

    if (pageType === 'Evaluations/Evaluations' && newProps) {
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
const search = ref('');
const errors = ref(props.errors || {});

const loading = ref(false);
const dialog = ref(false);
const dialogDeleteEvaluation = ref(false);
const deletingItemName = ref(null);

const headers = [
    { title: 'Name', key: 'name', width: '70%', sortable: false},
    { title: 'Postleitzahl', key: 'zip_code', width: '20%', sortable: false },
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

// Watch
watch(dialog, (val) => {
    if (!val) {
        close();
    }
});

// Methods
const goToPage = async ({ page, itemsPerPage, sortBy, clearFilters }) => {
    if (
        (page === currentPage.value && clearFilters)
    ) {
        loading.value = true;

        let options = { data: { page: page, per_page: itemsPerPage } };

        if (sortBy && sortBy.length > 0) {
            options.data.order_by = sortBy[0].key;
            options.data.sort = sortBy[0].order;
        }

        await router.reload(options);

        currentPage.value = page;
        perPage.value = itemsPerPage;
        loading.value = false;
    }
};

const openDeleteEvaluationDialog = (item) => {
    deletingItemName.value = item.name
    deleteForm.id = item.id;
    dialogDeleteEvaluation.value = true
};

const deleteForm = useForm({
    id: null,
});

const deleteEvaluation = async () => {
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

    deleteForm.delete(route('evaluations.destroy', { id: deleteForm.id }), formOptions);
};

const close = () => {
    dialog.value = false;
    dialogDeleteEvaluation.value = false;
    manageForm.reset();
    manageForm.clearErrors();

    errors.value = {};
};

const clear = () => {
    manageForm.reset();
    manageForm.clearErrors();
};


const manageForm = useForm({
    //
});

const manageEvaluation = async () => {
    manageForm.processing = true;

    manageForm.post(route('evaluations.store'), {
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
    <Head title="Evaluationen" />

    <AuthenticatedLayout :errors="errors">
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">Evaluationen</h2>

<!--            <div class="tw-flex tw-items-center tw-justify-end">-->
<!--                <v-hover v-if="!$page.props.auth.user.is_manager" v-slot:default="{ isHovering, props }">-->
<!--                    <v-btn v-bind="props" :color="isHovering ? 'accent' : 'primary'" dark>-->
<!--                        Anlegen-->

<!--                        <v-dialog v-model="dialog" activator="parent" width="80vw">-->
<!--                            <v-card height="80vh">-->
<!--                                <v-card-title>-->
<!--                                    <span class="tw-text-h5">Verwalte Einrichtung</span>-->
<!--                                </v-card-title>-->

<!--                                <v-card-text>-->
<!--                                    <v-container>-->
<!--                                        <v-row>-->
<!--                                            <v-col cols="12" sm="9">-->
<!--                                                <v-text-field v-model="manageForm.name" :error-messages="errors.name"-->
<!--                                                              label="Name der Einrichtung / Einrichtung*" required></v-text-field>-->
<!--                                            </v-col>-->
<!--                                            <v-col cols="12" sm="3">-->
<!--                                                <v-text-field v-model="manageForm.zip_code" :error-messages="errors.zip_code"-->
<!--                                                              label="Postleitzahl*" type="number" required></v-text-field>-->
<!--                                            </v-col>-->
<!--                                        </v-row>-->
<!--                                    </v-container>-->
<!--                                </v-card-text>-->

<!--                                <v-card-actions>-->
<!--                                    <v-hover v-slot:default="{ isHovering, props }">-->
<!--                                        <v-btn-primary @click="clear" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Zurücksetzen</v-btn-primary>-->
<!--                                    </v-hover>-->
<!--                                    <v-spacer></v-spacer>-->
<!--                                    <v-hover v-slot:default="{ isHovering, props }">-->
<!--                                        <v-btn @click="close" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Stornieren</v-btn>-->
<!--                                    </v-hover>-->
<!--                                    <v-hover v-slot:default="{ isHovering, props }">-->
<!--                                        <v-btn-primary @click="manageEvaluation" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Speichern</v-btn-primary>-->
<!--                                    </v-hover>-->
<!--                                </v-card-actions>-->
<!--                            </v-card>-->
<!--                        </v-dialog>-->
<!--                    </v-btn>-->
<!--                </v-hover>-->
<!--            </div>-->

<!--            <v-dialog v-model="dialogDeleteEvaluation" width="20vw">-->
<!--                <v-card height="30vh">-->
<!--                    <v-card-text>-->
<!--                        <v-container>-->
<!--                            <v-row>-->
<!--                                <v-col cols="12">-->
<!--                                    <p>Sind Sie sicher, dass Sie die Einrichtung {{deletingItemName}} löschen möchten?</p>-->
<!--                                </v-col>-->
<!--                            </v-row>-->
<!--                        </v-container>-->
<!--                    </v-card-text>-->

<!--                    <v-card-actions>-->
<!--                        <v-spacer></v-spacer>-->
<!--                        <v-hover v-slot:default="{ isHovering, props }">-->
<!--                            <v-btn @click="close" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Abbrechen</v-btn>-->
<!--                        </v-hover>-->
<!--                        <v-hover v-slot:default="{ isHovering, props }">-->
<!--                            <v-btn-primary @click="deleteEvaluation" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Löschen</v-btn-primary>-->
<!--                        </v-hover>-->
<!--                    </v-card-actions>-->
<!--                </v-card>-->
<!--            </v-dialog>-->
        </template>

<!--        <div class="tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8">-->
<!--            <div class="tw-bg-white tw-flex tw-justify-between tw-px-6 tw-py-6">-->
<!--                <div class="tw-w-full">-->
<!--                    <v-row>-->
<!--                        <v-col cols="12" sm="5">-->
<!--                            <v-text-field v-model="searchFilter" label="Name"></v-text-field>-->
<!--                        </v-col>-->
<!--                    </v-row>-->
<!--                </div>-->

<!--                <div class="tw-ml-6">-->
<!--                    <v-hover v-slot:default="{ isHovering, props }">-->
<!--                        <v-btn-->
<!--                            class="tw-mt-2"-->
<!--                            v-bind="props"-->
<!--                            :color="isHovering ? 'accent' : 'primary'"-->
<!--                            @click="search = String(Date.now())"-->
<!--                            dark-->
<!--                        >Suche</v-btn>-->
<!--                    </v-hover>-->
<!--                </div>-->
<!--            </div>-->

<!--            <v-data-table-server-->
<!--                v-model:items-per-page="perPage"-->
<!--                :items-per-page-options="[-->
<!--                  {value: 10, title: '10'},-->
<!--                  {value: 25, title: '25'},-->
<!--                  {value: 50, title: '50'},-->
<!--                  {value: 100, title: '100'},-->
<!--                  {value: -1, title: '$vuetify.dataFooter.itemsPerPageAll'}-->
<!--                ]"-->
<!--                :items-per-page-text="'Objekte pro Seite:'"-->
<!--                :headers="headers"-->
<!--                :page="currentPage"-->
<!--                :items-length="totalItems"-->
<!--                :items="modifiedItems"-->
<!--                :search="search"-->
<!--                v-sortable-data-table-->
<!--                :loading="loading"-->
<!--                class="data-table-container elevation-1"-->
<!--                item-value="name"-->
<!--                @update:options="goToPage"-->
<!--            >-->

<!--                <template v-slot:item="{ item }">-->
<!--                    <tr :data-id="item.selectable.id" :data-order="item.selectable.order">-->
<!--                        <td>{{item.selectable.name}}</td>-->

<!--                        <td>{{item.selectable.zip_code}}</td>-->

<!--                        <td>-->
<!--                            <v-tooltip location="top">-->
<!--                                <template v-slot:activator="{ props }">-->
<!--                                    <Link :href="route('evaluations.show', { id: item.selectable.id })">-->
<!--                                        <v-icon v-bind="props" size="small" class="tw-me-2">mdi-pencil</v-icon>-->
<!--                                    </Link>-->
<!--                                </template>-->
<!--                                <span>Einrichtung bearbeiten</span>-->
<!--                            </v-tooltip>-->

<!--                            <v-tooltip v-if="!$page.props.auth.user.is_manager" location="top">-->
<!--                                <template v-slot:activator="{ props }">-->
<!--                                    <v-icon v-bind="props" size="small" class="tw-me-2" @click="openDeleteEvaluationDialog(item.raw)">mdi-delete</v-icon>-->
<!--                                </template>-->
<!--                                <span>Einrichtung löschen</span>-->
<!--                            </v-tooltip>-->
<!--                        </td>-->
<!--                    </tr>-->
<!--                </template>-->

<!--                <template v-slot:no-data>-->
<!--                    <div class="tw-py-6">-->
<!--                        <template v-if="allFiltersEmpty">-->
<!--                            <h3 class="tw-mb-4">Die Tabelle ist leer.</h3>-->
<!--                        </template>-->
<!--                        <template v-else>-->
<!--                            <h3 class="tw-mb-4">Die Tabelle ist leer. Bitte setzen Sie die Suchfilter zurück.</h3>-->

<!--                            <v-btn color="primary" @click="goToPage({ page: 1, itemsPerPage: perPage, clearFilters: true })">Reset</v-btn>-->
<!--                        </template>-->
<!--                    </div>-->
<!--                </template>-->

<!--            </v-data-table-server>-->
<!--        </div>-->
    </AuthenticatedLayout>
</template>
