<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import {computed, onMounted, ref, watch} from "vue";
import {Inertia} from "@inertiajs/inertia";
import {Head, useForm, usePage, router, Link} from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Sortable from "sortablejs";

const props = defineProps({
    domain: Object,
    errors: Object,
});


/*
 * Inertia events handling
 */
// For page reloading on pagination or searching
Inertia.on('success', (event) => {
    let newProps = event.detail.page.props;
    let pageType = event.detail.page.component;

    if (pageType === 'Domains/Partials/ManageDomain' && newProps) {
        editedDomain.value = newProps.domain;
    }
});


/*
 * Main data
 */
const currentUser = usePage().props.auth.user ?? {};  // Global info about user

const editedDomain = ref(props.domain);
const errors = ref(props.errors || {});
const loading = ref(false);
const dialogDeleteSubdomain = ref(false);
const draggableItem = ref(null);
const dialog = ref(false);

const headers = [
    {title: 'Name', key: 'first_name', width: '90%', sortable: false},
    {title: 'Aktion', key: 'actions', width: '10%', sortable: false, align: 'center'},
];

// Computed
const modifiedItems = computed(() => {
    return props.domain.subdomains.map(item => {
        const modifiedItem = { ...item };
        for (const key in modifiedItem) {
            if (modifiedItem[key] === null || modifiedItem[key] === undefined) {
                modifiedItem[key] = '-';
            }
        }
        return modifiedItem;
    });
});

watch(dialog, (val) => {
    if (!val) {
        close();
    }
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

// Methods
const close = () => {
    dialog.value = false;
    dialogDeleteSubdomain.value = false;
    manageCreateSubdomainForm.reset();
    manageCreateSubdomainForm.clearErrors();
    errors.value = {};
};

const clear = () => {
    manageCreateSubdomainForm.reset();
    manageCreateSubdomainForm.clearErrors();
};

const reorderForm = useForm({
    items: [],
});

const saveNewOrder = (event) => {
    reorderForm.processing = true;

    let items = [];
    let pageIndex = 0;

    [].forEach.call(event.from.querySelectorAll('tr'), function (el,index) {
        el.setAttribute("data-order", pageIndex + index);

        items.push({
            id: el.getAttribute('data-id'),
            order: pageIndex + index,
        });
    });

    reorderForm.items = items;

    reorderForm.post(route('subdomains.reorder'), {
        preserveScroll: true,
        preserveState: false,
        // resetOnSuccess: false,
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

const openDeleteSubdomainDialog = (item) => {
    deleteForm.id = item.id;
    dialogDeleteSubdomain.value = true
};

const deleteForm = useForm({
    id: null,
});

const deleteSubdomain = async () => {
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

    deleteForm.delete(route('subdomains.destroy', { id: deleteForm.id }), formOptions);
};

const manageForm = useForm({
    id: editedDomain.value.id,
    name: editedDomain.value.name,
    abbreviation: editedDomain.value.abbreviation,
    age_2_red_threshold: editedDomain.value.age_2_red_threshold,
    age_2_red_threshold_daz: editedDomain.value.age_2_red_threshold_daz,
    age_2_yellow_threshold: editedDomain.value.age_2_yellow_threshold,
    age_2_yellow_threshold_daz: editedDomain.value.age_2_yellow_threshold_daz,
    age_4_red_threshold: editedDomain.value.age_4_red_threshold,
    age_4_red_threshold_daz: editedDomain.value.age_4_red_threshold_daz,
    age_4_yellow_threshold: editedDomain.value.age_4_yellow_threshold,
    age_4_yellow_threshold_daz: editedDomain.value.age_4_yellow_threshold_daz,
});

const manageDomain = async () => {
    manageForm.processing = true;

    let formOptions = {
        // preserveScroll: true,
        preserveState: false,
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

    manageForm.put(route('domains.update', {domain: manageForm.id}), formOptions);
};


const manageCreateSubdomainForm = useForm({
    name: null,
    domain: editedDomain.value.id
});

const manageCreateSubdomain = async () => {
    manageCreateSubdomainForm.processing = true;

    manageCreateSubdomainForm.post(route('subdomains.store'), {
        // preserveScroll: true,
        preserveState: false,
        // resetOnSuccess: false,
        onSuccess: (page) => {
            close();
        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            manageCreateSubdomainForm.processing = false;
        },
    });
};
</script>

<template>
    <Head title="Manage Domain"/>

    <AuthenticatedLayout :errors="errors">
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">Manage Domain</h2>
        </template>

        <div class="tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8">
            <v-container>
                <v-row>
                    <v-col cols="12" md="3" sm="4">
                        <div class="tw-flex tw-justify-between">
                            <v-hover v-slot:default="{ isHovering, props }">
                                <Link :href="route('domains.index')">
                                    <v-btn v-bind="props" :color="isHovering ? 'primary' : 'accent'">Zurück</v-btn>
                                </Link>
                            </v-hover>
                            <v-hover v-slot:default="{ isHovering, props }">
                                <v-btn-primary @click="manageDomain" v-bind="props"
                                               :color="isHovering ? 'accent' : 'primary'">Speichern
                                </v-btn-primary>
                            </v-hover>
                        </div>
                    </v-col>
                </v-row>
            </v-container>

            <v-container>
                <v-row>
                    <v-col cols="12" sm="3">
                        <v-text-field v-model="manageForm.abbreviation" :error-messages="errors.abbreviation"
                                      label="Kürzel*" required></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="9">
                        <v-text-field v-model="manageForm.name" :error-messages="errors.name"
                                      label="Name der Domäne*" required></v-text-field>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12">
                        <p>Schwellenwerte für Altersgruppe bis 2,5 Jahre</p>
                    </v-col>
                    <v-col cols="12" sm="3">
                        <v-text-field v-model="manageForm.age_2_red_threshold"
                                      :error-messages="errors.age_2_red_threshold"
                                      label="Schwellwert Rot*"
                                      placeholder="z.B. 5"
                                      type="number"
                                      required></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="3">
                        <v-text-field v-model="manageForm.age_2_red_threshold_daz"
                                      :error-messages="errors.age_2_red_threshold_daz"
                                      label="Schwellwert Rot mit Daz*"
                                      placeholder="z.B. 3"
                                      type="number"
                                      required></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="3">
                        <v-text-field v-model="manageForm.age_2_yellow_threshold"
                                      :error-messages="errors.age_2_yellow_threshold"
                                      label="Schwellwert Gelb*"
                                      placeholder="z.B. 10"
                                      type="number"
                                      required></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="3">
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
                    <v-col cols="12" sm="3">
                        <v-text-field v-model="manageForm.age_4_red_threshold"
                                      :error-messages="errors.age_4_red_threshold"
                                      label="Schwellwert Rot*"
                                      placeholder="z.B. 5"
                                      type="number"
                                      required></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="3">
                        <v-text-field v-model="manageForm.age_4_red_threshold_daz"
                                      :error-messages="errors.age_4_red_threshold_daz"
                                      label="Schwellwert Rot mit Daz*"
                                      placeholder="z.B. 3"
                                      type="number"
                                      required></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="3">
                        <v-text-field v-model="manageForm.age_4_yellow_threshold"
                                      :error-messages="errors.age_4_yellow_threshold"
                                      label="Schwellwert Gelb*"
                                      placeholder="z.B. 10"
                                      type="number"
                                      required></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="3">
                        <v-text-field v-model="manageForm.age_4_yellow_threshold_daz"
                                      :error-messages="errors.age_4_yellow_threshold_daz"
                                      label="Schwellwert Gelb mit Daz*"
                                      placeholder="z.B. 8"
                                      type="number"
                                      required></v-text-field>
                    </v-col>
                </v-row>
            </v-container>

            <v-container>
                <v-row>
                    <v-col cols="12">
                        <div class="tw-flex tw-items-center tw-justify-end">
                            <v-hover v-slot:default="{ isHovering, props }">
                                <v-btn v-bind="props" :color="isHovering ? 'accent' : 'primary'" dark>
                                    Anlegen

                                    <v-dialog v-model="dialog" activator="parent" width="80vw">
                                        <v-card height="80vh">
                                            <v-card-title>
                                                <span class="tw-text-h5">Neue Subdomäne</span>
                                            </v-card-title>

                                            <v-card-text>
                                                <v-container>
                                                    <v-row>
                                                        <v-col cols="12" sm="12">
                                                            <v-text-field v-model="manageCreateSubdomainForm.name" :error-messages="errors.name"
                                                                          label="Name der Subdomäne*" required></v-text-field>
                                                        </v-col>
                                                    </v-row>
                                                </v-container>
                                            </v-card-text>

                                            <v-card-actions>
                                                <v-hover v-slot:default="{ isHovering, props }">
                                                    <v-btn-primary @click="clear" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Clear</v-btn-primary>
                                                </v-hover>
                                                <v-spacer></v-spacer>
                                                <v-hover v-slot:default="{ isHovering, props }">
                                                    <v-btn @click="close" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Cancel</v-btn>
                                                </v-hover>
                                                <v-hover v-slot:default="{ isHovering, props }">
                                                    <v-btn-primary @click="manageCreateSubdomain" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Save</v-btn-primary>
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
                <v-dialog v-model="dialogDeleteSubdomain" width="20vw">
                    <v-card height="30vh">
                        <v-card-text>
                            <v-container>
                                <v-row>
                                    <v-col cols="12">
                                        <p>Sind Sie sicher, dass Sie den aktuellen Subdomain löschen möchten?</p>
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
                                <v-btn-primary @click="deleteSubdomain" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Löschen</v-btn-primary>
                            </v-hover>
                        </v-card-actions>
                    </v-card>
                </v-dialog>

                <v-row>
                    <v-col cols="12">
                        <v-data-table-server
                            :items-per-page="-1"
                            :headers="headers"
                            :items="modifiedItems"
                            v-sortable-data-table
                            :loading="loading"
                            class="data-table-container data-table-container-hide-footer elevation-1"
                            item-value="name">

                            <template v-slot:item="{ item }">
                                <tr :data-id="item.selectable.id" :data-order="item.selectable.order"
                                    @click="navigateToSubdomain(item.selectable.id)">
                                    <td>{{ item.selectable.name }}</td>
                                    <td>
                                        <v-tooltip location="top">
                                            <template v-slot:activator="{ props }">
                                                <v-icon
                                                    draggable="true"
                                                    @dragstart="draggableItem = item.raw"
                                                    color="primary" v-bind="props" size="small"
                                                    class="tw-me-2 glyphicon-move">mdi-arrow-collapse-vertical
                                                </v-icon>
                                            </template>
                                            <span>neu anordnen</span>
                                        </v-tooltip>

                                        <v-tooltip location="top">
                                            <template v-slot:activator="{ props }">
                                                <v-icon v-bind="props" size="small" class="tw-me-2"
                                                        @click="openDeleteSubdomainDialog(item.raw)">mdi-delete
                                                </v-icon>
                                            </template>
                                            <span>Subdomäne löschen</span>
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
