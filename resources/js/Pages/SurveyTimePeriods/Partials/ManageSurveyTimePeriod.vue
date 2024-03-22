<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import { computed, ref, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, useForm, usePage, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    surveyTimePeriod: Object,
    errors: Object,
    roles: Array,
    users: Array,
});


/*
 * Inertia events handling
 */
// For page reloading on pagination or searching
Inertia.on('success', (event) => {
    let newProps = event.detail.page.props;
    let pageType = event.detail.page.component;

    if (pageType === 'Kitas/Partials/ManageKita' && newProps) {
        editedKita.value = newProps.kita;
    }
});


/*
 * Main data
 */
const currentUser = usePage().props.auth.user ?? {};  // Global info about user

const editedKita = ref(props.kita);
const errors = ref(props.errors || {});
const loading = ref(false);
const deletingItemName = ref(null);
const dialog = ref(false);
const connectUserDialog = ref(false);
const dialogDeleteKitaUser = ref(false);

const headers = [
    {title: 'Status', key: 'is_online', width: '5%', sortable: false, align: 'center'},
    {title: 'Vorname', key: 'first_name', width: '20%', sortable: false},
    {title: 'Nachname', key: 'last_name', width: '25%', sortable: false},
    {title: 'E-Mail', key: 'email', width: '20%', sortable: false},
    {title: 'Rolle', key: 'primary_role_name', width: '20%', sortable: false},
    {title: 'Aktion', key: 'actions', width: '10%', sortable: false, align: 'center'},
];

// Computed
const modifiedItems = computed(() => {
    return props.kita.users.map(item => {
        const modifiedItem = {...item};
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

// Methods
const close = () => {
    dialog.value = false;
    connectUserDialog.value = false;
    dialogDeleteKitaUser.value = false;
    manageCreateKitaUserForm.reset();
    manageCreateKitaUserForm.clearErrors();
    manageConnectKitaUserForm.reset();
    manageConnectKitaUserForm.clearErrors();
    errors.value = {};
};

const clear = () => {
    manageCreateKitaUserForm.reset();
    manageCreateKitaUserForm.clearErrors();
    manageConnectKitaUserForm.reset();
    manageConnectKitaUserForm.clearErrors();

    manageForm.reset();
    manageForm.clearErrors();
    manageForm.zip_code = false
    manageForm.name = null
};

const openDeleteUserFromKitaDialog = (item) => {
    deletingItemName.value = item.full_name
    deleteForm.user = item.id;
    dialogDeleteKitaUser.value = true
};

const deleteForm = useForm({
    user: null,
});

const deleteUserFromKita = async () => {
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

    deleteForm.post(route('kitas.disconnect_user', {id: props.kita.id}), formOptions);
};

const manageForm = useForm({
    id: editedKita.value.id,
    name: editedKita.value.name,
    zip_code: editedKita.value.zip_code,
});

const manageKita = async () => {
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

    manageForm.put(route('kitas.update', {kita: manageForm.id}), formOptions);
};


const manageCreateKitaUserForm = useForm({
    first_name: null,
    last_name: null,
    email: null,
    role: null,
    two_factor_auth_enabled: false,
    kitas: [editedKita.value.id]
});

const manageCreateKitaUser = async () => {
    manageCreateKitaUserForm.processing = true;

    manageCreateKitaUserForm.post(route('users.store_from_kita'), {
        onSuccess: (page) => {
            close();
        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            manageCreateKitaUserForm.processing = false;
        },
    });
};


const manageConnectKitaUserForm = useForm({
    users: null,
    kitas: [editedKita.value.id]
});

const manageConnectKitaUser = async () => {
    manageConnectKitaUserForm.processing = true;

    manageConnectKitaUserForm.post(route('kitas.connect_users', {kita: manageCreateKitaUserForm.kitas}), {
        onSuccess: (page) => {
            close();
        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            manageConnectKitaUserForm.processing = false;
        },
    });
};
</script>

<template>
    <Head :title="`Verwalte Einrichtung ${kita.name}`"/>

    <AuthenticatedLayout :errors="errors">
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">Verwalte Einrichtung {{ kita.name }}</h2>
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
                    <v-col cols="12" sm="9">
                        <v-text-field v-model="manageForm.name" :error-messages="errors.name"
                                      label="Name der Einrichtung / Einrichtung*" required></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="3">
                        <v-text-field v-model="manageForm.zip_code" :error-messages="errors.zip_code"
                                      label="Postleitzahl*" type="number" required></v-text-field>
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
                            <Link :href="route('kitas.index')">
                                <v-btn class="mr-2" variant="text" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Zurück</v-btn>
                            </Link>
                        </v-hover>
                        <v-hover v-slot:default="{ isHovering, props }">
                            <v-btn-primary @click="manageKita" v-bind="props"
                                           :color="isHovering ? 'accent' : 'primary'">Speichern
                            </v-btn-primary>
                        </v-hover>
                    </v-col>

                </v-row>
            </v-container>

            <v-container>
                <v-row class="tw-border-t-8 tw-mt-8 tw-pt-8">
                    <v-col cols="12" sm="6">
                        <h3>Zugeordnete Benutzer</h3>
                    </v-col>

                    <v-col cols="12" sm="6">
                        <div class="tw-flex tw-items-center tw-justify-end">
                            <v-hover v-if="$page.props.auth.user.is_super_admin || $page.props.auth.user.is_admin" v-slot:default="{ isHovering, props }">
                                <v-btn v-bind="props" :color="isHovering ? 'accent' : 'primary'" dark class="mr-2">
                                    Benutzer verbinden

                                    <v-dialog v-model="connectUserDialog" activator="parent" width="80vw">
                                        <v-card height="80vh">
                                            <v-card-title>
                                                <span class="tw-text-h5">Verbinden Benutzer</span>
                                            </v-card-title>

                                            <v-card-text>
                                                <v-container>
                                                    <v-row>
                                                        <v-col cols="12">
                                                            <p>Wählen Sie die Benutzer aus, die Sie diesem Einrichtung hinzufügen möchten</p>
                                                        </v-col>
                                                    </v-row>
                                                    <v-row>
                                                        <v-col cols="12">
                                                            <v-autocomplete
                                                                v-model="manageConnectKitaUserForm.users"
                                                                :items="users"
                                                                :error-messages="errors.users"
                                                                item-title="full_name"
                                                                item-value="id"
                                                                label="User"
                                                                multiple
                                                                required
                                                            ></v-autocomplete>
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
                                                    <v-btn-primary @click="manageConnectKitaUser" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Speichern</v-btn-primary>
                                                </v-hover>
                                            </v-card-actions>
                                        </v-card>
                                    </v-dialog>
                                </v-btn>
                            </v-hover>

                            <v-hover v-slot:default="{ isHovering, props }">
                                <v-btn v-bind="props" :color="isHovering ? 'accent' : 'primary'" dark>
                                    Benutzer hinzufügen

                                    <v-dialog v-model="dialog" activator="parent" width="80vw">
                                        <v-card height="80vh">
                                            <v-card-title>
                                                <span class="tw-text-h5">Neue Benutzer</span>
                                            </v-card-title>

                                            <v-card-text>
                                                <v-container>
                                                    <v-row>
                                                        <v-col cols="12" sm="6">
                                                            <v-text-field v-model="manageCreateKitaUserForm.first_name" :error-messages="errors.first_name" label="Vorname" required></v-text-field>
                                                        </v-col>
                                                        <v-col cols="12" sm="6">
                                                            <v-text-field v-model="manageCreateKitaUserForm.last_name" :error-messages="errors.last_name" label="Nachname" required></v-text-field>
                                                        </v-col>
                                                    </v-row>


                                                    <v-row>
                                                        <v-col cols="12" sm="6">
                                                            <v-text-field v-model="manageCreateKitaUserForm.email" :error-messages="errors.email" label="Email" required></v-text-field>
                                                        </v-col>
                                                        <v-col cols="12" sm="6">
                                                            <v-select
                                                                v-model="manageCreateKitaUserForm.role"
                                                                :items="roles"
                                                                :error-messages="errors.role"
                                                                item-title="human_name"
                                                                item-value="id"
                                                                label="Role"
                                                                required
                                                            ></v-select>
                                                        </v-col>
                                                    </v-row>

                                                    <v-row>
                                                        <v-col cols="12" md="4" sm="6">
                                                            <v-checkbox
                                                                v-model="manageCreateKitaUserForm.two_factor_auth_enabled"
                                                                label="Zwei-Faktor-Authentifizierung"
                                                                :value="true"
                                                            ></v-checkbox>
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
                                                    <v-btn-primary @click="manageCreateKitaUser" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Speichern</v-btn-primary>
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
                <v-dialog v-model="dialogDeleteKitaUser" width="20vw">
                    <v-card height="30vh">
                        <v-card-text>
                            <v-container>
                                <v-row>
                                    <v-col cols="12">
                                        <p>Sind Sie sicher, dass Sie den Benutzer {{deletingItemName}} aus Einrichtung Liste löschen möchten? (Der Benutzer wird vom aktuellen Einrichtung getrennt)</p>
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
                                <v-btn-primary @click="deleteUserFromKita" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Löschen</v-btn-primary>
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
                                    <td align="center">
                                        <v-icon size="medium" :class="{ active: item.selectable.is_online }">mdi-circle</v-icon>
                                    </td>

                                    <td>{{item.selectable.first_name}}</td>

                                    <td>{{item.selectable.last_name}}</td>

                                    <td>{{item.selectable.email}}</td>

                                    <td>{{item.selectable.primary_role_human_name}}</td>

                                    <td align="center">
                                        <v-tooltip location="top">
                                            <template v-slot:activator="{ props }">
                                                <Link :href="`${route('users.edit', { id: item.selectable.id })}?from=kitas.show;${kita.id}`">
                                                    <v-icon v-bind="props" size="small" class="tw-me-2">mdi-pencil</v-icon>
                                                </Link>
                                            </template>
                                            <span>Benutzer bearbeiten</span>
                                        </v-tooltip>

                                        <v-tooltip location="top">
                                            <template v-slot:activator="{ props }">
                                                <v-icon v-bind="props" size="small" class="tw-me-2"
                                                        @click="openDeleteUserFromKitaDialog(item.raw)">mdi-delete
                                                </v-icon>
                                            </template>
                                            <span>Benutzer löschen</span>
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
