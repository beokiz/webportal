<!--
  - GorKa Team
  - Copyright (c) 2024  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import { onMounted, computed, ref, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, useForm, usePage, router, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ages, prepareDate, formatDate, formatDateTime } from '@/Composables/common.js';
import Sortable from "sortablejs";
import TiptapEditor from "@/Components/TiptapEditor.vue";

const props = defineProps({
    surveyTimePeriods: Array,
    downloadableFiles: Array,
    emailSettings: Object,
    loginSettings: Object,
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

    if (pageType === 'SurveyTimePeriods/SurveyTimePeriods' && newProps) {
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
const isMenuOpen = ref(false);
const isMenu2Open = ref(false);

const dialogManageSurveyTimePeriod = ref(false);
const dialogManageDownloadableFile = ref(false);
const dialogDeleteSurveyTimePeriod = ref(false);
const dialogDeleteDownloadableFile = ref(false);

const deletingSurveyTimePeriodName = ref(null);
const deletingDownloadableFileName = ref(null);

const rawSurveyStart = ref(null);
const rawSurveyEnd = ref(null);
const surveyStart = ref();
const surveyEnd = ref();

const settingsLabel = ref({
    send_yearly_evaluation_reminder_ntf_before_days: "Anzahl der Tage vor Ende des Bewertungszeitraums für den Versand der Erinnerungsmails",
});

const surveyTimePeriodsHeaders = [
    { title: 'Jahr', key: 'year', width: '10%', sortable: true },
    { title: 'Erhebungsbeginn', key: 'survey_start_date', width: '37.5%', sortable: true },
    { title: 'Erhebungsende', key: 'survey_end_date', width: '37.5%', sortable: true },
    { title: 'Aktion', key: 'actions', width: '15%', sortable: false, align: 'center' },
];

const downloadableFileHeaders = [
    { title: 'Name', key: 'name', width: '42.5%', sortable: true },
    { title: 'Hinzugefügt am', key: 'created_at', width: '42.5%', sortable: true },
    { title: 'Aktion', key: 'actions', width: '15%', sortable: false, align: 'center' },
];

onMounted(() => {
    setDefaultSurveyDates();
});


// Computed
const modifiedSurveyTimePeriodItems = computed(() => {
    return modifyTableItems(props.surveyTimePeriods);
});

const modifiedDownloadableFileItems = computed(() => {
    return modifyTableItems(props.downloadableFiles);
});

// Watch
watch(dialogManageSurveyTimePeriod, (val) => {
    if (!val) {
        close();
    }
});

watch(dialogManageDownloadableFile, (val) => {
    if (!val) {
        close();
    }
});

/*
 * Common methods
 */
const modifyTableItems = (tableItems) => {
    return tableItems.map(item => {
        const modifiedItem = { ...item };
        for (const key in modifiedItem) {
            if (modifiedItem[key] === null || modifiedItem[key] === undefined) {
                modifiedItem[key] = '-';
            }
        }
        return modifiedItem;
    });
};

const setDefaultSurveyDates = () => {
    rawSurveyStart.value = prepareDate(new Date(new Date().getFullYear(), 4, 1));
    rawSurveyEnd.value = prepareDate(new Date(new Date().getFullYear(), 5, 30));
    surveyStart.value = new Date(new Date().getFullYear(), 4, 1);
    surveyEnd.value = new Date(new Date().getFullYear(), 5, 30);
};

const close = () => {
    dialogManageSurveyTimePeriod.value = false;
    dialogManageDownloadableFile.value = false;
    dialogDeleteSurveyTimePeriod.value = false;
    dialogDeleteDownloadableFile.value = false;

    clear();

    errors.value = {};
};

const clear = () => {
    manageSurveyTimePeriodForm.reset();
    manageSurveyTimePeriodForm.clearErrors();
    manageDownloadableFileForm.reset();
    manageDownloadableFileForm.clearErrors();

    setDefaultSurveyDates();
};

const reloadPage = async ({ page, itemsPerPage, sortBy }) => {
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
};

/*
 * Survey time periods methods
 */
watch(surveyStart, (val) => {
    rawSurveyStart.value = prepareDate(val);
});

watch(surveyEnd, (val) => {
    rawSurveyEnd.value = prepareDate(val);
});

const openDeleteSurveyTimePeriodDialog = (item) => {
    deleteSurveyTimePeriodForm.id = item.id;
    deletingSurveyTimePeriodName.value = item.year;
    dialogDeleteSurveyTimePeriod.value = true;
};

const manageSurveyTimePeriodForm = useForm({
    year: null,
    survey_start_date: null,
    survey_end_date: null,
});

const deleteSurveyTimePeriodForm = useForm({
    id: null,
});

const manageSurveyTimePeriod = async () => {
    manageSurveyTimePeriodForm.processing = true;

    manageSurveyTimePeriodForm.survey_start_date = new Date(surveyStart.value).toLocaleString()
    manageSurveyTimePeriodForm.survey_end_date = new Date(surveyEnd.value).toLocaleString()

    manageSurveyTimePeriodForm.post(route('survey_time_periods.store'), {
        onSuccess: (page) => {
            close();
        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            manageSurveyTimePeriodForm.processing = false;
        },
    });
};

const deleteSurveyTimePeriod = async () => {
    deleteSurveyTimePeriodForm.processing = true;

    let formOptions = {
        onSuccess: (page) => {
            close();
        },
        onError: (err) => {
            close();
            errors.value = err;
        },
        onFinish: () => {
            deleteSurveyTimePeriodForm.processing = false;
        },
    };

    deleteSurveyTimePeriodForm.delete(route('downloadable_files.destroy', { id: deleteSurveyTimePeriodForm.id }), formOptions);
};

/*
 * Settings methods
 */
const manageEmailSettingsForm = useForm({
    settings: props.emailSettings,
});

const manageLoginSettingsForm = useForm({
    settings: props.loginSettings,
});

const manageSettings = async (type) => {
    let manageForm = type === 'login' ? manageLoginSettingsForm : manageEmailSettingsForm;

    manageForm.processing = true;

    let formOptions = {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (page) => {
            //
        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            manageForm.processing = false;
        },
    };

    manageForm.post(route('settings.update'), formOptions);
};

/*
 * Downloadable files methods
 */
const openDeleteDownloadableFileDialog = (item) => {
    deleteDownloadableFileForm.id = item.id;
    deletingDownloadableFileName.value = item.name;
    dialogDeleteDownloadableFile.value = true;
};

const manageDownloadableFileForm = useForm({
    name: null,
    file: null,
});

const deleteDownloadableFileForm = useForm({
    id: null,
});

const manageDownloadableFile = async () => {
    deleteDownloadableFileForm.processing = true;

    manageDownloadableFileForm.file = manageDownloadableFileForm.file?.length ? manageDownloadableFileForm.file[0] : null;

    manageDownloadableFileForm.post(route('downloadable_files.store'), {
        forceFormData: true,
        onSuccess: (page) => {
            close();
        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            manageDownloadableFileForm.processing = false;
        },
    });
};

const deleteDownloadableFile = async () => {
    deleteDownloadableFileForm.processing = true;

    let formOptions = {
        onSuccess: (page) => {
            close();
        },
        onError: (err) => {
            close();
            errors.value = err;
        },
        onFinish: () => {
            deleteDownloadableFileForm.processing = false;
        },
    };

    deleteDownloadableFileForm.delete(route('downloadable_files.destroy', { id: deleteDownloadableFileForm.id }), formOptions);
};
</script>

<template>
    <Head title="Einstellungen" />

    <AuthenticatedLayout :errors="errors">
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">Einstellungen</h2>
        </template>

        <!-- Survey time period block -->
        <div class="tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8 tw-mb-8">
            <!-- New survey time period button & popup -->
            <div class="tw-flex tw-items-center tw-justify-between tw-max-w-full tw-mx-auto tw-pt-6 tw-px-4 sm:tw-px-6 lg:tw-px-8 tw-mb-4">
                <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">Rückmeldungszeiträume</h2>

                <div class="tw-flex tw-items-center tw-justify-end">

                    <v-hover v-slot:default="{ isHovering, props }">
                        <v-btn v-bind="props" :color="isHovering ? 'accent' : 'primary'" dark>
                            ZEITRAUM HINZUFÜGEN

                            <v-dialog v-model="dialogManageSurveyTimePeriod" activator="parent" width="80vw">
                                <v-card height="80vh">
                                    <v-card-title>
                                        <span class="tw-text-h5">Neuer Rückmeldezeitraum</span>
                                    </v-card-title>

                                    <v-card-text>
                                        <v-container>
                                            <v-row>
                                                <v-col cols="12" sm="4">
                                                    <v-text-field v-model="manageSurveyTimePeriodForm.year" :error-messages="errors.year"
                                                                  label="Jahr*" required></v-text-field>
                                                </v-col>

                                                <v-col cols="12" sm="4">
                                                    <v-locale-provider locale="de">
                                                        <v-menu v-model="isMenuOpen"
                                                                :return-value.sync="surveyStart"
                                                                :close-on-content-click="false">
                                                            <template v-slot:activator="{ props }">
                                                                <v-text-field
                                                                    label="Erhebungsbeginn*"
                                                                    class="tw-cursor-pointer"
                                                                    :model-value="rawSurveyStart"
                                                                    prepend-icon="mdi-calendar"
                                                                    readonly
                                                                    v-bind="props"
                                                                    :error-messages="errors.survey_start_date"
                                                                ></v-text-field>
                                                            </template>
                                                            <v-date-picker @update:modelValue="isMenuOpen = false" v-model="surveyStart"></v-date-picker>
                                                        </v-menu>
                                                    </v-locale-provider>
                                                </v-col>

                                                <v-col cols="12" sm="4">
                                                    <v-locale-provider locale="de">
                                                        <v-menu v-model="isMenu2Open"
                                                                :return-value.sync="surveyEnd"
                                                                :close-on-content-click="false">
                                                            <template v-slot:activator="{ props }">
                                                                <v-text-field
                                                                    label="Erhebungsende*"
                                                                    class="tw-cursor-pointer"
                                                                    :model-value="rawSurveyEnd"
                                                                    prepend-icon="mdi-calendar"
                                                                    readonly
                                                                    v-bind="props"
                                                                    :error-messages="errors.survey_end_date"
                                                                ></v-text-field>
                                                            </template>
                                                            <v-date-picker @update:modelValue="isMenu2Open = false" v-model="surveyEnd"></v-date-picker>
                                                        </v-menu>
                                                    </v-locale-provider>
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
                                            <v-btn-primary @click="manageSurveyTimePeriod" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Speichern</v-btn-primary>
                                        </v-hover>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>
                        </v-btn>
                    </v-hover>
                </div>
            </div>

            <!-- Delete survey time period popup -->
            <v-dialog v-model="dialogDeleteSurveyTimePeriod" width="20vw">
                <v-card height="30vh">
                    <v-card-text>
                        <v-container>
                            <v-row>
                                <v-col cols="12">
                                    <p>Sind Sie sicher, dass Sie die Einstellungen {{deletingSurveyTimePeriodName}} löschen möchten?</p>
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
                            <v-btn-primary @click="deleteSurveyTimePeriod" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Löschen</v-btn-primary>
                        </v-hover>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <!-- Survey time period table -->
            <v-data-table-server
                v-model:items-per-page="perPage"
                :headers="surveyTimePeriodsHeaders"
                :items="modifiedSurveyTimePeriodItems"
                :search="search"
                :loading="loading"
                class="data-table-container elevation-1"
                item-value="name"
                @update:options="reloadPage"
            >
                <template v-slot:item="{ item }">
                    <tr :data-id="item.id" :data-order="item.order">
                        <td>{{item.year}}</td>

                        <td>{{ formatDate(item.survey_start_date, 'de-DE') }}</td>

                        <td>{{ formatDate(item.survey_end_date, 'de-DE') }}</td>

                        <td align="center">
                            <v-tooltip location="top">
                                <template v-slot:activator="{ props }">
                                    <Link :href="route('survey_time_periods.show', { id: item.id })">
                                        <v-icon v-bind="props" size="small" class="tw-me-2">mdi-pencil</v-icon>
                                    </Link>
                                </template>
                                <span>Einstellungen bearbeiten</span>
                            </v-tooltip>

                            <v-tooltip location="top">
                                <template v-slot:activator="{ props }">
                                    <v-icon v-bind="props" size="small" class="tw-me-2" @click="openDeleteSurveyTimePeriodDialog(item)">mdi-delete</v-icon>
                                </template>
                                <span>Einstellungen löschen</span>
                            </v-tooltip>
                        </td>
                    </tr>
                </template>

                <template v-slot:no-data>
                    <div class="tw-py-6">
                        <h3 class="tw-mb-4">Die Tabelle ist leer.</h3>
                    </div>
                </template>

                <template #bottom></template>
            </v-data-table-server>
        </div>

        <!-- E-mail settings block -->
        <div class="tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8 tw-mb-8">
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight tw-mb-4">E-Mail Einstellungen</h2>

            <v-row>
                <v-col cols="12" md="6">
                    <template v-for="(value, name) in emailSettings">
                        <div class="tw-flex tw-items-center tw-justify-start">
                            <v-text-field v-model="manageEmailSettingsForm.settings[name]"
                                          :error-messages="errors?.settings ? errors.settings[name] : false"
                                          :label="`${settingsLabel[name]}*`" required></v-text-field>
                        </div>
                    </template>
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="12" class="tw-flex tw-items-center tw-justify-start">
                    <v-hover v-slot:default="{ isHovering, props }">
                        <v-btn-primary @click="manageSettings('email')" v-bind="props"
                                       :color="isHovering ? 'accent' : 'primary'">Speichern
                        </v-btn-primary>
                    </v-hover>
                </v-col>
            </v-row>
        </div>

        <!-- Downloadable files block -->
        <div class="tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8 tw-mb-8">
            <!-- New downloadable files button & popup -->
            <div class="tw-flex tw-items-center tw-justify-between tw-max-w-full tw-mx-auto tw-pt-6 tw-px-4 sm:tw-px-6 lg:tw-px-8 tw-mb-4">
                <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">Dateien für den Downloadbereich</h2>

                <div class="tw-flex tw-items-center tw-justify-end">
                    <v-hover v-slot:default="{ isHovering, props }">
                        <v-btn v-bind="props" :color="isHovering ? 'accent' : 'primary'" dark>
                            DATEI HINZUFÜGEN

                            <v-dialog v-model="dialogManageDownloadableFile" activator="parent" width="80vw">
                                <v-card height="80vh">
                                    <v-card-title>
                                        <span class="tw-text-h5">Neue Datei zum Downloadbereich hinzufügen</span>
                                    </v-card-title>

                                    <v-card-text>
                                        <v-container>
                                            <v-row>
                                                <v-col cols="12" sm="6">
                                                    <v-text-field
                                                        class="required"
                                                        type="text"
                                                        v-model="manageDownloadableFileForm.name"
                                                        :error-messages="errors.name"
                                                        label="Name"
                                                        required
                                                        :disabled="manageDownloadableFileForm.processing"
                                                    ></v-text-field>
                                                </v-col>

                                                <v-col cols="12" sm="6">
                                                    <v-file-input
                                                        v-model="manageDownloadableFileForm.file"
                                                        :error-messages="errors.file"
                                                        label="File"
                                                        accept="*/*"
                                                        prepend-icon="mdi-upload"
                                                        :disabled="manageDownloadableFileForm.processing"
                                                    ></v-file-input>
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
                                            <v-btn-primary @click="manageDownloadableFile" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Speichern</v-btn-primary>
                                        </v-hover>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>
                        </v-btn>
                    </v-hover>
                </div>
            </div>

            <!-- Delete downloadable file popup -->
            <v-dialog v-model="dialogDeleteDownloadableFile" width="20vw">
                <v-card height="30vh">
                    <v-card-text>
                        <v-container>
                            <v-row>
                                <v-col cols="12">
                                    <p>Sind Sie sicher, dass Sie die Einstellungen {{deletingDownloadableFileName}} löschen möchten?</p>
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
                            <v-btn-primary @click="deleteDownloadableFile" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Löschen</v-btn-primary>
                        </v-hover>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <!-- Downloadable files table -->
            <v-data-table-server
                v-model:items-per-page="perPage"
                :headers="downloadableFileHeaders"
                :items="modifiedDownloadableFileItems"
                :search="search"
                :loading="loading"
                class="data-table-container elevation-1"
                item-value="name"
                @update:options="reloadPage"
            >
                <template v-slot:item="{ item }">
                    <tr :data-id="item.id" :data-order="item.order">
                        <td>{{item.name}}</td>

                        <td>{{formatDateTime(item.created_at, 'de-DE')}}</td>

                        <td align="center">
                            <v-tooltip v-if="item?.path" location="top">
                                <template v-slot:activator="{ props }">
                                    <a :href="item.path" download>
                                        <v-icon v-bind="props" size="small" class="tw-me-2">mdi-download</v-icon>
                                    </a>
                                </template>
                                <span>Datei herunterladen</span>
                            </v-tooltip>

                            <v-tooltip location="top">
                                <template v-slot:activator="{ props }">
                                    <Link :href="route('downloadable_files.show', { id: item.id })">
                                        <v-icon v-bind="props" size="small" class="tw-me-2">mdi-pencil</v-icon>
                                    </Link>
                                </template>
                                <span>Datei bearbeiten</span>
                            </v-tooltip>

                            <v-tooltip location="top">
                                <template v-slot:activator="{ props }">
                                    <v-icon v-bind="props" size="small" class="tw-me-2" @click="openDeleteDownloadableFileDialog(item)">mdi-delete</v-icon>
                                </template>
                                <span>Datei löschen</span>
                            </v-tooltip>
                        </td>
                    </tr>
                </template>

                <template v-slot:no-data>
                    <div class="tw-py-6">
                        <h3 class="tw-mb-4">Die Tabelle ist leer.</h3>
                    </div>
                </template>

                <template #bottom></template>
            </v-data-table-server>
        </div>

        <!-- Login form settings block -->
        <div class="tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8 tw-mb-8">
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight tw-mb-4">Text auf Login Seite</h2>

            <v-row>
                <v-col cols="12">
                    <template v-for="(value, name) in loginSettings">
                        <div class="tw-flex tw-items-center tw-justify-start">
                            <template v-if="name === 'login_form_additional_html'">
                                <TiptapEditor v-model="manageLoginSettingsForm.settings[name]" :minHeight="300"/>
                            </template>
                            <template v-else>
                                <v-text-field v-model="manageLoginSettingsForm.settings[name]"
                                              :error-messages="errors?.settings ? errors.settings[name] : false"
                                              :label="`${settingsLabel[name]}*`" required></v-text-field>
                            </template>
                        </div>
                    </template>
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="12" class="tw-flex tw-items-center tw-justify-start">
                    <v-hover v-slot:default="{ isHovering, props }">
                        <v-btn-primary @click="manageSettings('login')" v-bind="props"
                                       :color="isHovering ? 'accent' : 'primary'">Speichern
                        </v-btn-primary>
                    </v-hover>
                </v-col>
            </v-row>
        </div>
    </AuthenticatedLayout>
</template>
