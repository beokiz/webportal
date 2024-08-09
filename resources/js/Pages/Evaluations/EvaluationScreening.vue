<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import { computed, ref } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { Head, usePage, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';


const props = defineProps({
    domains: Array,
    errors: Object,
});

/*
 * Inertia events handling
 */
// For page reloading on pagination or searching
Inertia.on('success', (event) => {
    let newProps = event.detail.page.props;
    let pageType = event.detail.page.component;

    if (pageType === 'Evaluations/EvaluationDomains' && newProps) {
        //
    }
});

/*
 * Main data
 */
const currentUser = usePage().props.auth.user ?? {};  // Global info about user

const errors = ref(props.errors || {});

const loading = ref(false);

const headers = [
    { title: 'Name', key: 'uuid', width: '95%', sortable: false},
    { title: 'Aktion', key: 'actions', width: '5%', sortable: false, align: 'center'},
];

// Computed
const modifiedItems = computed(() => {
    return props.domains.map(item => {
        const modifiedItem = { ...item };
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
    <Head title="Domäne für Prüfung auswählen" />

    <AuthenticatedLayout :errors="errors">
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">Domäne für Prüfung auswählen</h2>
        </template>

        <div class="tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8">
            <v-data-table-server
                :items-per-page="-1"
                :headers="headers"
                :items="modifiedItems"
                :loading="loading"
                class="data-table-container data-table-container-hide-footer elevation-1"
                item-value="name">

                <template v-slot:item="{ item }">
                    <tr :data-id="item.selectable.id" :data-order="item.selectable.order">
                        <td>{{item.selectable.name}}</td>

                        <td align="center">
                            <v-tooltip location="top">
                                <template v-slot:activator="{ props }">
                                    <Link :href="route('screening.show', { id: item.selectable.id })">
                                        <v-icon v-bind="props" size="small" class="tw-me-2">mdi-arrow-right-bold</v-icon>
                                    </Link>
                                </template>
                                <span>Domäne prüfen</span>
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
    </AuthenticatedLayout>
</template>
