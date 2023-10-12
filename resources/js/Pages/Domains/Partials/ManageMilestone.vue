<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import { computed, ref, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { Head, useForm, usePage, router, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ages } from "@/Composables/common"

const props = defineProps({
    milestone: Object,
    errors: Object,
});


/*
 * Inertia events handling
 */
// For page reloading on pagination or searching
Inertia.on('success', (event) => {
    let newProps = event.detail.page.props;
    let pageType = event.detail.page.component;

    if (pageType === 'Domains/Partials/ManageMilestone' && newProps) {
        editedMilestone.value = newProps.milestone;
    }
});


/*
 * Main data
 */
const currentUser = usePage().props.auth.user ?? {};  // Global info about user

const editedMilestone = ref(props.milestone);
const errors = ref(props.errors || {});
const loading = ref(false);

// Methods
const close = () => {
    manageForm.reset();
    manageForm.clearErrors();

    errors.value = {};
};

const clear = () => {
    manageForm.reset();
    manageForm.clearErrors();
    manageForm.subdomain = null
    manageForm.abbreviation = null
    manageForm.title = null
    manageForm.text = null
    manageForm.emphasis = null
    manageForm.emphasis_daz = null
    manageForm.age = null
};

const manageForm = useForm({
    id: editedMilestone.value.id,
    subdomain: editedMilestone.value.subdomain_id,
    abbreviation: editedMilestone.value.abbreviation,
    title: editedMilestone.value.title,
    text: editedMilestone.value.text,
    emphasis: editedMilestone.value.emphasis,
    emphasis_daz: editedMilestone.value.emphasis_daz,
    age: editedMilestone.value.age,
});

const manageMilestone = async () => {
    manageForm.processing = true;

    manageForm.put(route('milestones.update', { milestone: manageForm.id }), {
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
    });
};
</script>

<template>
    <Head title="Meilenstein verwalten" />

    <AuthenticatedLayout :errors="errors">
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">Meilenstein verwalten</h2>
        </template>

        <div class="tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8">
            <v-container>
                <v-row>
                    <v-col cols="12" sm="3">
                        <v-text-field v-model="manageForm.abbreviation" :error-messages="errors.abbreviation"
                                      label="Kürzel*" required></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="3">
                        <v-select
                            v-model="manageForm.age"
                            :items="ages"
                            :error-messages="errors.age"
                            item-title="age_name"
                            item-value="age_number"
                            label="Altersgruppe"
                        ></v-select>
                    </v-col>
                    <v-col cols="12" sm="3">
                        <v-text-field v-model="manageForm.emphasis" :error-messages="errors.emphasis"
                                      type="number" label="Gewichtung*" required></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="3">
                        <v-text-field v-model="manageForm.emphasis_daz" :error-messages="errors.emphasis_daz"
                                      type="number" label="Gewichtung mit Daz*" required></v-text-field>
                    </v-col>
                </v-row>


                <v-row>
                    <v-col cols="12">
                        <v-text-field v-model="manageForm.title" :error-messages="errors.title"
                                      label="Titel*" required></v-text-field>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12">
                        <v-textarea v-model="manageForm.text" :error-messages="errors.text"
                                      label="Subtext*" required></v-textarea>
                    </v-col>
                </v-row>
            </v-container>


            <v-container>
                <v-row>
                    <v-col cols="12" sm="6">
                        <v-hover v-slot:default="{ isHovering, props }">
                            <v-btn @click="clear" v-bind="props" :color="isHovering ? 'primary' : 'accent'">Zurücksetzen</v-btn>
                        </v-hover>
                    </v-col>
                    <v-col cols="12" sm="6" align="right">
                        <v-hover v-slot:default="{ isHovering, props }">
                            <Link :href="route('subdomains.show', { id: editedMilestone.subdomain_id })">
                                <v-btn class="mr-2" variant="text" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Zurück</v-btn>
                            </Link>
                        </v-hover>
                        <v-hover v-slot:default="{ isHovering, props }">
                            <v-btn-primary @click="manageMilestone" v-bind="props"
                                           :color="isHovering ? 'accent' : 'primary'">Speichern
                            </v-btn-primary>
                        </v-hover>
                    </v-col>

                </v-row>
            </v-container>
        </div>
    </AuthenticatedLayout>
</template>
