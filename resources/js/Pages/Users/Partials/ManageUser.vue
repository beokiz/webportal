<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import { computed, ref, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { Head, useForm, usePage, router, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    user: Object,
    errors: Object,
    roles: Array,
    from: String,
});


/*
 * Inertia events handling
 */
// For page reloading on pagination or searching
Inertia.on('success', (event) => {
    let newProps = event.detail.page.props;
    let pageType = event.detail.page.component;

    if (pageType === 'Users/Partials/ManageUser' && newProps) {
        editedUser.value = newProps.user;
    }
});


/*
 * Main data
 */
const currentUser = usePage().props.auth.user ?? {};  // Global info about user

const editedUser = ref(props.user);
const errors = ref(props.errors || {});
const loading = ref(false);

// Computed
const backRoute = computed(() => {
    if (props.from) {
        const routeParams = props.from.split(';');

        if (routeParams.length === 2) {
            return route(routeParams[0], { kita: routeParams[1] })
        }
    }

    return route('users.index');
});

// Methods
const close = () => {
    manageForm.reset();
    manageForm.clearErrors();

    errors.value = {};
};

const manageForm = useForm({
    id: editedUser.value.id,
    first_name: editedUser.value.first_name,
    last_name: editedUser.value.last_name,
    email: editedUser.value.email,
    password: null,
    password_confirmation: null,
    role: editedUser.value.primary_role_id,
    two_factor_auth_enabled: editedUser.value.two_factor_auth_enabled,
});

const manageUser = async () => {
    manageForm.processing = true;

    // if (!manageForm.password) {
    //     delete manageForm.password;
    // }
    //
    // if (!manageForm.password_confirmation) {
    //     delete manageForm.password_confirmation;
    // }

    manageForm.put(route('users.update', { user: manageForm.id }), {
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
    });
};
</script>

<template>
    <Head title="Benutzer verwalten" />

    <AuthenticatedLayout :errors="errors">
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">Benutzer verwalten</h2>
        </template>

        <div class="tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8">
            <v-container>
                <v-row>
                    <v-col cols="12" sm="4">
                        <v-text-field v-model="manageForm.first_name" :error-messages="errors.first_name" label="Vorname" required></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="4">
                        <v-text-field v-model="manageForm.last_name" :error-messages="errors.last_name" label="Nachname" required></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="4">
                        <v-text-field v-model="manageForm.email" :error-messages="errors.email" label="Email" required></v-text-field>
                    </v-col>
                </v-row>


                <v-row>
                    <v-col cols="12" sm="4">
                        <v-text-field type="password" autocomplete="new-password" v-model="manageForm.password" :error-messages="errors.password" label="Passwort" required></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="4">
                        <v-text-field type="password" v-model="manageForm.password_confirmation" :error-messages="errors.password_confirmation" label="Passwort Bestätigung" required></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="4">
                        <v-select
                            :disabled="$page.props.auth.user.id === editedUser.id"
                            v-model="manageForm.role"
                            :items="roles"
                            :error-messages="errors.role"
                            item-title="human_name"
                            item-value="id"
                            label="Rolle"
                            required
                        ></v-select>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12" sm="4">
                        <v-checkbox
                            v-model="manageForm.two_factor_auth_enabled"
                            label="Zwei-Faktor-Authentifizierung"
                            :value="true"
                        ></v-checkbox>
                    </v-col>
                </v-row>
            </v-container>


            <v-container>
                <v-row>
                    <v-col cols="12" md="3" sm="4">
                        <div class="tw-flex tw-justify-between">
                            <v-hover v-slot:default="{ isHovering, props }">
                                <Link :href="backRoute">
                                    <v-btn v-bind="props" :color="isHovering ? 'primary' : 'accent'">Zurück</v-btn>
                                </Link>
                            </v-hover>
                            <v-hover v-slot:default="{ isHovering, props }">
                                <v-btn-primary @click="manageUser" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Speichern</v-btn-primary>
                            </v-hover>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
        </div>
    </AuthenticatedLayout>
</template>
