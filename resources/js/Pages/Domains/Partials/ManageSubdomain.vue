<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, useForm, usePage, Link } from '@inertiajs/vue3';
import { ages } from '@/Composables/common';
import Sortable from 'sortablejs';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    subdomain: Object,
    errors: Object,
});


/*
 * Inertia events handling
 */
// For page reloading on pagination or searching
Inertia.on('success', (event) => {
    let newProps = event.detail.page.props;
    let pageType = event.detail.page.component;

    if (pageType === 'Domains/Partials/ManageSubdomain' && newProps) {
        editedSubdomain.value = newProps.subdomain;
    }
});


/*
 * Main data
 */
const currentUser = usePage().props.auth.user ?? {};  // Global info about user

const editedSubdomain = ref(props.subdomain);
const errors = ref(props.errors || {});
const loading = ref(false);
const dialogDeleteMilestone = ref(false);
const draggableItem = ref(null);
const dialog = ref(false);
const deletingItemName = ref(null);

const headers = [
    {title: 'Kürzel', key: 'abbreviation', width: '10%', sortable: false},
    {title: 'Titel', key: 'title', width: '20%', sortable: false},
    {title: 'Beschreibungstext', key: 'text', width: '60%', sortable: false},
    {title: 'Aktion', key: 'actions', width: '10%', sortable: false, align: 'center'},
];

// Computed
const modifiedItems = computed(() => {
    return props.subdomain.milestones.map(item => {
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
    dialogDeleteMilestone.value = false;
    manageCreateMilestoneForm.reset();
    manageCreateMilestoneForm.clearErrors();
    errors.value = {};
};

const clear = () => {
    manageCreateMilestoneForm.reset();
    manageCreateMilestoneForm.clearErrors();

    manageForm.reset();
    manageForm.clearErrors();
    manageForm.name = null
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

    reorderForm.post(route('milestones.reorder'), {
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
    deletingItemName.value = item.title
    deleteForm.id = item.id;
    dialogDeleteMilestone.value = true
};

const deleteForm = useForm({
    id: null,
});

const deleteMilestone = async () => {
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

    deleteForm.delete(route('milestones.destroy', { id: deleteForm.id }), formOptions);
};

const manageForm = useForm({
    id: editedSubdomain.value.id,
    name: editedSubdomain.value.name,
});

const manageSubdomain = async () => {
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

    manageForm.put(route('subdomains.update', {subdomain: manageForm.id}), formOptions);
};


const manageCreateMilestoneForm = useForm({
    subdomain: editedSubdomain.value.id,
    abbreviation: null,
    title: null,
    text: null,
    emphasis: null,
    emphasis_daz: null,
    age: null,
});

const manageCreateMilestone = async () => {
    manageCreateMilestoneForm.processing = true;

    manageCreateMilestoneForm.post(route('milestones.store'), {
        onSuccess: (page) => {
            close();
        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            manageCreateMilestoneForm.processing = false;
        },
    });
};
</script>

<template>
    <Head :title="`Verwalte Subdomäne ${subdomain.name}`"/>

    <AuthenticatedLayout :errors="errors">
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">{{ `Verwalte Subdomäne ${subdomain.name}` }}</h2>
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
                        <v-text-field v-model="manageForm.name" :error-messages="errors.name"
                                      label="Name der Subdomäne*" required></v-text-field>
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
                            <Link :href="route('domains.show', { id: editedSubdomain.domain_id })">
                                <v-btn class="mr-2" variant="text" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Zurück</v-btn>
                            </Link>
                        </v-hover>
                        <v-hover v-slot:default="{ isHovering, props }">
                            <v-btn-primary @click="manageSubdomain" v-bind="props"
                                           :color="isHovering ? 'accent' : 'primary'">Speichern
                            </v-btn-primary>
                        </v-hover>
                    </v-col>

                </v-row>
            </v-container>

            <v-container>
                <v-row class="tw-border-t-8 tw-mt-8 tw-pt-8">
                    <v-col cols="12" sm="6">
                        <h3>Zugeordnete Meilensteine</h3>
                    </v-col>
                    <v-col cols="12" sm="6">
                        <div class="tw-flex tw-items-center tw-justify-end">
                            <v-hover v-slot:default="{ isHovering, props }">
                                <v-btn v-bind="props" :color="isHovering ? 'accent' : 'primary'" dark>
                                    Meilenstein hinzufügen

                                    <v-dialog v-model="dialog" activator="parent" width="80vw">
                                        <v-card height="80vh">
                                            <v-card-title>
                                                <span class="tw-text-h5">Neuer Meilenstein</span>
                                            </v-card-title>

                                            <v-card-text>
                                                <v-container>
                                                    <v-row>
                                                        <v-col cols="12" sm="3">
                                                            <v-text-field v-model="manageCreateMilestoneForm.abbreviation" :error-messages="errors.abbreviation"
                                                                          label="Kürzel*" required></v-text-field>
                                                        </v-col>
                                                        <v-col cols="12" sm="3">
                                                            <v-select
                                                                v-model="manageCreateMilestoneForm.age"
                                                                :items="ages"
                                                                :error-messages="errors.age"
                                                                item-title="age_name"
                                                                item-value="age_number"
                                                                label="Altersgruppe"
                                                            ></v-select>
                                                        </v-col>
                                                        <v-col cols="12" sm="3">
                                                            <v-text-field v-model="manageCreateMilestoneForm.emphasis" :error-messages="errors.emphasis"
                                                                          type="number" label="Gewichtung*" required></v-text-field>
                                                        </v-col>
                                                        <v-col cols="12" sm="3">
                                                            <v-text-field v-model="manageCreateMilestoneForm.emphasis_daz" :error-messages="errors.emphasis_daz"
                                                                          type="number" label="Gewichtung mit Daz*" required></v-text-field>
                                                        </v-col>
                                                    </v-row>


                                                    <v-row>
                                                        <v-col cols="12">
                                                            <v-text-field v-model="manageCreateMilestoneForm.title" :error-messages="errors.title"
                                                                          label="Titel*" required></v-text-field>
                                                        </v-col>
                                                    </v-row>

                                                    <v-row>
                                                        <v-col cols="12">
                                                            <v-textarea v-model="manageCreateMilestoneForm.text" :error-messages="errors.text"
                                                                          label="Beschreibungstext*" required></v-textarea>
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
                                                    <v-btn-primary @click="manageCreateMilestone" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Speichern</v-btn-primary>
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
                <v-dialog v-model="dialogDeleteMilestone" width="20vw">
                    <v-card height="30vh">
                        <v-card-text>
                            <v-container>
                                <v-row>
                                    <v-col cols="12">
                                        <p>Sind Sie sicher, dass Sie den Meilenstein {{deletingItemName}} löschen möchten?</p>
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
                                <v-btn-primary @click="deleteMilestone" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Löschen</v-btn-primary>
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
                                <tr :data-id="item.selectable.id" :data-order="item.selectable.order">
                                    <td>{{ item.selectable.abbreviation }}</td>
                                    <td>{{ item.selectable.title }}</td>
                                    <td>{{ item.selectable.text }}</td>

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
                                                <Link :href="route('milestones.show', { id: item.selectable.id })">
                                                    <v-icon v-bind="props" size="small" class="tw-me-2">mdi-pencil</v-icon>
                                                </Link>
                                            </template>
                                            <span>Meilenstein bearbeiten</span>
                                        </v-tooltip>

                                        <v-tooltip location="top">
                                            <template v-slot:activator="{ props }">
                                                <v-icon v-bind="props" size="small" class="tw-me-2"
                                                        @click="openDeleteSubdomainDialog(item.raw)">mdi-delete
                                                </v-icon>
                                            </template>
                                            <span>Meilenstein löschen</span>
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
