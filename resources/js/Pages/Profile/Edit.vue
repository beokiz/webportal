<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import { computed, ref } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
// import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue';
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue';

const props = defineProps({
    mustVerifyEmail: Boolean,
    status: String,
    kitas: Array,
});


const user = usePage().props.auth.user;

const loading = ref(false);

const headers = [
    {title: 'Name', key: 'name', width: '75%', sortable: false},
    {title: 'Postleitzahl', key: 'zip_code', width: '25%', sortable: false},
];

const modifiedItems = computed(() => {
    return props.kitas.map(item => {
        const modifiedItem = {...item};
        for (const key in modifiedItem) {
            if (modifiedItem[key] === null || modifiedItem[key] === undefined) {
                modifiedItem[key] = '-';
            }
        }
        return modifiedItem;
    });
});
</script>

<template>
    <Head title="Profil"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">Profil</h2>
        </template>

        <v-container v-if="kitas.length > 0">
            <v-row>
                <v-col cols="12">
                    <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">Ihre Einrichtungen</h2>
                </v-col>
                <v-col cols="12"></v-col>
            </v-row>
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
                            <tr :data-id="item.selectable.id" :data-order="item.selectable.order">
                                <td>{{ item.selectable.name }}</td>

                                <td>{{ item.selectable.zip_code }}</td>
                            </tr>
                        </template>

                        <template v-slot:no-data>
                            <div class="tw-py-6">
                                <template v-if="allFiltersEmpty">
                                    <h3 class="tw-mb-4">Die Tabelle ist leer.</h3>
                                </template>
                                <template v-else>
                                    <h3 class="tw-mb-4">Die Tabelle ist leer. Bitte setzen Sie die Suchfilter
                                        zurück.</h3>

                                    <v-btn color="primary"
                                           @click="goToPage({ page: 1, itemsPerPage: perPage, clearFilters: true })">
                                        Reset
                                    </v-btn>
                                </template>
                            </div>
                        </template>

                    </v-data-table-server>
                </v-col>
            </v-row>
        </v-container>

        <div class="tw-py-12">
            <div class="tw-max-w-full tw-mx-auto sm:tw-px-6 lg:tw-px-8 tw-space-y-6">
                <div class="p-4 sm:tw-p-8 tw-bg-white tw-shadow">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="tw-max-w-xl"
                    />
                </div>

                <div class="tw-p-4 sm:tw-p-8 tw-bg-white tw-shadow">
                    <UpdatePasswordForm class="tw-max-w-xl"/>
                </div>

                <!--                <div class="tw-p-4 sm:tw-p-8 tw-bg-white tw-shadow">-->
                <!--                    <DeleteUserForm class="tw-max-w-xl" />-->
                <!--                </div>-->
            </div>
        </div>
    </AuthenticatedLayout>
</template>
