<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import { computed, ref, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, useForm, usePage, router, Link } from '@inertiajs/vue3';
import { formatDateTime } from '@/Composables/common';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import EvaluationDomainsList from "@/Components/EvaluationDomainsList.vue";

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
const evaluationResultState = ref(false);
const evaluationResultItem = ref(null);
const evaluationResultDomains = ref(null);

const headers = [
    { title: 'ID', key: 'id', width: '40%', sortable: false },
    { title: 'Zuletzt bearbeitet', key: 'updated_at', width: '15%', sortable: false },
    { title: 'Abgegeben am', key: 'finished_at', width: '15%', sortable: false },
    { title: 'Bearbeitungszeit endet', key: 'not_editable_at', width: '20%', sortable: false },
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

const preparedEvaluationResultDomains = computed(() => {
    if (evaluationResultItem.value.age) {
        return evaluationResultDomains.value.map(domain => ({
            ...domain,
            subdomains: domain.subdomains.map(subdomain => ({
                ...subdomain,
                milestones: subdomain.milestones.filter(milestone => {
                    return parseFloat(milestone.age) === parseFloat(evaluationResultItem.value.age);
                }),
            })).filter(subdomain => subdomain.milestones.length > 0),
        })).filter(domain => domain.subdomains.length > 0);
    } else {
        return [];
    }
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
        page !== currentPage.value ||
        page === currentPage.value && clearFilters ||
        [page, itemsPerPage, sortBy] !== [currentPage.value, perPage.value, orderBy.value]
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

        await router.reload(options);

        currentPage.value = page;
        perPage.value = itemsPerPage;
        loading.value = false;
    }
};

const openDeleteEvaluationDialog = (item) => {
    deletingItemName.value = item.custom_unique_id
    deleteForm.id = item.id;
    dialogDeleteEvaluation.value = true
};

const manageEvaluationInfoForm = useForm({
    id: null,
});

const openEvaluationInfo = async (item) => {
    manageEvaluationInfoForm.id = item.id

    manageEvaluationInfoForm.processing = true;

    manageEvaluationInfoForm.post(route('evaluations.show_popup', { id: manageEvaluationInfoForm.id }), {
        onSuccess: (page) => {
            // Clear errors & reset form data
            manageEvaluationInfoForm.clearErrors();
            errors.value = {};
            evaluationResultState.value = true

            evaluationResultItem.value = page.props.data.item;
            evaluationResultDomains.value = page.props.data.domains;
        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            manageEvaluationInfoForm.processing = false;
        },
    });
};

const close = () => {
    dialog.value = false;
    dialogDeleteEvaluation.value = false;
    evaluationResultState.value = false;

    errors.value = {};
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

const unfinishedForm = useForm({
  //
});

const unfinishedEvaluation = async (id) => {
  unfinishedForm.processing = true;

  unfinishedForm.post(route('evaluations.unfinished', { id: id }), {
    preserveState: false,
    onSuccess: (page) => {
      // Clear errors & reset form data
      unfinishedForm.clearErrors();
      errors.value = {};
      close();
    },
    onError: (err) => {
      errors.value = err;
    },
    onFinish: () => {
      unfinishedForm.processing = false;
    },
  });
};
</script>

<template>
    <Head title="Einschätzungen"/>

    <AuthenticatedLayout :errors="errors">
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">Einschätzungen</h2>

            <div v-if="$page.props.auth.user.is_manager || $page.props.auth.user.is_employer" class="tw-flex tw-items-center tw-justify-end">
                <Link :href="route('evaluations.create')">
                    <v-hover v-slot:default="{ isHovering, props }">
                        <v-btn-primary v-bind="props" :color="isHovering ? 'accent' : 'primary'">
                            Anlegen
                        </v-btn-primary>
                    </v-hover>
                </Link>
            </div>

            <v-dialog v-model="dialogDeleteEvaluation" width="20vw">
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
                            <v-btn-primary @click="deleteEvaluation" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Löschen</v-btn-primary>
                        </v-hover>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </template>

        <div class="tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8">
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
                        <td>{{`${item.selectable.kita.formatted_name}_${item.selectable.custom_unique_id}`}}</td>

                        <td>{{formatDateTime(item.selectable.updated_at, 'sv-SE')}}</td>

                        <td>{{formatDateTime(item.selectable.finished_at, 'sv-SE')}}</td>

                        <td>{{formatDateTime(item.selectable.not_editable_at, 'sv-SE')}}</td>

                        <td align="center">
                            <v-tooltip v-if="item.selectable.editable && ($page.props.auth.user.is_manager || $page.props.auth.user.is_employer)" location="top">
                                <template v-slot:activator="{ props }">
                                    <Link :href="route('evaluations.edit', { id: item.selectable.id })">
                                        <v-icon v-bind="props" size="small" class="tw-me-2">mdi-pencil</v-icon>
                                    </Link>
                                </template>
                                <span>Einschätzung bearbeiten</span>
                            </v-tooltip>

                            <v-tooltip v-if="item.selectable.finished" location="top">
                                <template v-slot:activator="{ props }">
                                    <v-icon v-bind="props" size="small" class="tw-me-2"
                                            @click="openEvaluationInfo(item.raw)">mdi-eye</v-icon>
                                </template>
                                <span>Einschätzung ansehen</span>
                            </v-tooltip>

                            <v-tooltip location="top">
                                <template v-slot:activator="{ props }">
                                    <v-icon v-bind="props" size="small" class="tw-me-2" @click="openDeleteEvaluationDialog(item.raw)">mdi-delete</v-icon>
                                </template>
                                <span>Einschätzung löschen</span>
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
        </div>

        <v-dialog v-model="evaluationResultState" width="95vw">
            <v-card height="95vh">
                <v-card-text>
                    <v-container>
                        <v-row class="result-evaluation-domains">
                            <v-col cols="12">
                                <v-hover v-slot:default="{ isHovering, props }">
                                    <div class="tw-text-right">
                                        <a :href="route('evaluations.pdf', { id: evaluationResultItem.id })" @click="close" title="Fenster schließen">
                                            <v-icon v-bind="props" size="small" class="tw-me-2" @click="">mdi-close</v-icon>
                                        </a>
                                    </div>
                                </v-hover>
                            </v-col>
                        </v-row>

                        <v-row class="result-evaluation-domains">
                            <v-col cols="8" offset="2">
                                <div class="tw-text-center">
                                    <h1 class="tw-uppercase text-primary tw-font-black tw-text-xl tw-mb-8">
                                        Screening wurde eingereicht
                                    </h1>

                                    <p class="tw-mb-8">
                                        Folgendes Screening wurde eingereicht und kann nur bis 15 Minuten nach Einreichung bearbeitet werden. Danach verschwindet es aus Ihrer Übersicht. Sollten Sie es zurückziehen oder bearbeiten wollen, klicke Sie auf 'Abgabe zurückziehen. Nachfolgend erhalten Sie eine Übersicht des eingereichten Screenings, welches Sie über den Download-Button als PDF herunterladen können.
                                    </p>

                                    <v-hover v-slot:default="{ isHovering, props }">
                                        <v-btn :href="route('evaluations.pdf', { id: evaluationResultItem.id })" class="tw-px-2 tw-py-3 tw-mb-4 tw-mr-4 tw-normal-case" :color="isHovering ? 'primary' : 'accent'">
                                          Screening als PDF downloaden
                                        </v-btn>
                                    </v-hover>

                                    <v-hover v-slot:default="{ isHovering, props }">
                                        <v-btn @click="unfinishedEvaluation(evaluationResultItem.id)" class="tw-px-2 tw-py-3 tw-mb-4 tw-normal-case" :color="isHovering ? 'accent' : 'primary'">
                                          Abgabe zurückziehen
                                        </v-btn>
                                    </v-hover>
                                </div>
                            </v-col>

                            <v-col cols="12">
                                <p>
                                    <span class="tw-font-black">Bezeichner des Screenings</span>:
                                    {{`${evaluationResultItem.kita.formatted_name}_${evaluationResultItem.custom_unique_id}`}}
                                </p>
                            </v-col>

                            <v-col cols="12">
                                <EvaluationDomainsList
                                    :ratings="evaluationResultItem.data"
                                    :domains="preparedEvaluationResultDomains"
                                    :disabled="true"/>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-hover v-slot:default="{ isHovering, props }">
                        <v-btn-primary @click="close" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Abbrechen</v-btn-primary>
                    </v-hover>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </AuthenticatedLayout>
</template>
