<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, useForm, usePage, Link } from '@inertiajs/vue3';
import Sortable from 'sortablejs';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    domain: Object,
    errors: Object,
    from: String,
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
const deletingItemName = ref(null);

const headers = [
    {title: 'Name', key: 'first_name', width: '90%', sortable: false},
    {title: 'Aktion', key: 'actions', width: '10%', sortable: false, align: 'center'},
];

const checkDazInputState = computed(() => {
    return !manageForm.daz_dependent ? 6 : 3;
});

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

    return route('domains.index');
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

    manageForm.reset();
    manageForm.clearErrors();
    manageForm.daz_dependent = false
    manageForm.name = null
    manageForm.abbreviation = null
    manageForm.age_2_red_threshold = null
    manageForm.age_2_red_threshold_daz = null
    manageForm.age_2_yellow_threshold = null
    manageForm.age_2_yellow_threshold_daz = null
    manageForm.age_4_red_threshold = null
    manageForm.age_4_red_threshold_daz = null
    manageForm.age_4_yellow_threshold = null
    manageForm.age_4_yellow_threshold_daz = null
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
    deletingItemName.value = item.name
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
    daz_dependent: editedDomain.value.daz_dependent,
});

const manageDomain = async () => {
    manageForm.processing = true;

    if(!manageForm.daz_dependent){
        manageForm.age_2_red_threshold_daz = manageForm.age_2_red_threshold
        manageForm.age_2_yellow_threshold_daz = manageForm.age_2_yellow_threshold
        manageForm.age_4_red_threshold_daz = manageForm.age_4_red_threshold
        manageForm.age_4_yellow_threshold_daz = manageForm.age_4_yellow_threshold
    }

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

    manageForm.put(route('domains.update', {domain: manageForm.id}), formOptions);
};


const manageCreateSubdomainForm = useForm({
    name: null,
    domain: editedDomain.value.id
});

const manageCreateSubdomain = async () => {
    manageCreateSubdomainForm.processing = true;

    manageCreateSubdomainForm.post(route('subdomains.store'), {
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
    <Head :title="`Verwalte Domäne ${domain.name}`"/>

    <AuthenticatedLayout :errors="errors">
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">{{ `Verwalte Domäne ${domain.name}` }}</h2>
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
                            :defaults-target="manageForm.daz_dependent"
                            hide-details
                            label="Einstellen mit Daz"
                        ></v-switch>
                    </v-col>
                </v-row>
                <v-row class="mt-10">
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

                <v-row>
                    <v-col cols="12" sm="6">
                        <v-hover v-slot:default="{ isHovering, props }">
                            <v-btn @click="clear" v-bind="props" :color="isHovering ? 'primary' : 'accent'">Zurücksetzen</v-btn>
                        </v-hover>
                    </v-col>

                    <v-col cols="12" sm="6" align="right">
                        <v-hover v-slot:default="{ isHovering, props }">
                            <Link :href="backRoute">
                                <v-btn class="mr-2" variant="text" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Zurück</v-btn>
                            </Link>
                        </v-hover>
                        <v-hover v-slot:default="{ isHovering, props }">
                            <v-btn-primary @click="manageDomain" v-bind="props"
                                           :color="isHovering ? 'accent' : 'primary'">Speichern
                            </v-btn-primary>
                        </v-hover>
                    </v-col>

                </v-row>
            </v-container>

            <v-container>
                <v-row class="tw-border-t-8 tw-mt-8 tw-pt-8">
                    <v-col cols="12" sm="6">
                        <h3>Zugeordnete Subdomänen</h3>
                    </v-col>
                    <v-col cols="12" sm="6">
                        <div class="tw-flex tw-items-center tw-justify-end">
                            <v-hover v-slot:default="{ isHovering, props }">
                                <v-btn v-bind="props" :color="isHovering ? 'accent' : 'primary'" dark>
                                    Subdomäne hinzufügen
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
                                                    <v-btn-primary @click="clear" v-bind="props" :color="isHovering ? 'primary' : 'accent'">Zurücksetzen</v-btn-primary>
                                                </v-hover>
                                                <v-spacer></v-spacer>
                                                <v-hover v-slot:default="{ isHovering, props }">
                                                    <v-btn @click="close" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Zurück</v-btn>
                                                </v-hover>
                                                <v-hover v-slot:default="{ isHovering, props }">
                                                    <v-btn-primary @click="manageCreateSubdomain" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Speichern</v-btn-primary>
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
                                        <p>Sind Sie sicher, dass Sie die Subdomäne {{deletingItemName}} löschen möchten?</p>
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
                            :loading="loading"
                            class="data-table-container data-table-container-hide-footer elevation-1"
                            item-value="name">

                            <template v-slot:item="{ item }">
                                <tr :data-id="item.id" :data-order="item.order">
                                    <td>{{ item.name }}</td>

                                    <td>
                                        <v-tooltip location="top">
                                            <template v-slot:activator="{ props }">
                                                <v-icon
                                                    draggable="true"
                                                    @dragstart="draggableItem = item"
                                                    color="primary" v-bind="props" size="small"
                                                    class="tw-me-2 glyphicon-move">mdi-arrow-collapse-vertical
                                                </v-icon>
                                            </template>
                                            <span>Neu anordnen</span>
                                        </v-tooltip>

                                        <v-tooltip location="top">
                                            <template v-slot:activator="{ props }">
                                                <Link :href="route('subdomains.show', { id: item.id })">
                                                    <v-icon v-bind="props" size="small" class="tw-me-2">mdi-pencil</v-icon>
                                                </Link>
                                            </template>
                                            <span>Subdomäne bearbeiten</span>
                                        </v-tooltip>

                                        <v-tooltip location="top">
                                            <template v-slot:activator="{ props }">
                                                <v-icon v-bind="props" size="small" class="tw-me-2"
                                                        @click="openDeleteSubdomainDialog(item)">mdi-delete
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
