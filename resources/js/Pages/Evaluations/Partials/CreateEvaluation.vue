<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import {computed, onMounted, onBeforeMount, ref, watch} from "vue";
import {Inertia} from "@inertiajs/inertia";
import {Head, useForm, usePage, router, Link} from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ages } from "@/Composables/common"
import {v4 as uuidv4} from "uuid";

const evaluationResultState = ref(false);

const props = defineProps({
    errors: Object,
    kitas: Array,
    domains: Array,
});

/*
 * Inertia events handling
 */
// For page reloading on pagination or searching
Inertia.on('success', (event) => {
    let newProps = event.detail.page.props;
    let pageType = event.detail.page.component;

    if (pageType === 'Evaluations/Partials/CreateEvaluation' && newProps) {
        //
    }
});


/*
 * Main data
 */
const currentUser = usePage().props.auth.user ?? {};  // Global info about user

const generatedUUID = ref(null);
const errors = ref(props.errors || {});
const loading = ref(false);

const evaluationResult = ref(null);

// onMounted
onBeforeMount(() => {
    // Prepare evaluation data
    setInitialRatingData();
});

onMounted(() => {
    generatedUUID.value = uuidv4();
    manageForm.uuid = generatedUUID.value;
});

const setInitialRatingData = () => {
    let ratingsData = [];

    props.domains.forEach(function(item1, index1) {
        ratingsData[index1] = {
            domain: item1.id,
            milestones: [],
        };

        item1.subdomains.forEach(function(item2, index2) {
            item2.milestones.forEach(function(item3, index3) {
                ratingsData[index1].milestones.push({ id: item3.id, value: null });
            });
        });
    });

    manageForm.ratings = ratingsData;
};


const manageForm = useForm({
    age: null,
    uuid: null,
    is_daz: false,
    user_id: currentUser.id,
    kita_id: null,
    ratings: [],
});

const manageEvaluation = async () => {
    manageForm.processing = true;

    manageForm.post(route('evaluations.store'), {
        // preserveState: false,
        onSuccess: (page) => {
            manageForm.reset();
            manageForm.clearErrors();

            setInitialRatingData();
            evaluationResultState.value = true;

            evaluationResult.value = page.props.data;
            console.log(evaluationResult.value)
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
    <Head :title="`Create`"/>

    <AuthenticatedLayout :errors="errors">
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">{{ `Create` }}</h2>
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
                    <v-col cols="12" sm="4">
                        <v-text-field v-model="manageForm.uuid" :error-messages="errors.uuid"
                                      readonly
                                      label="Bezeichner der Einschatzung" required></v-text-field>
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
                        <v-select
                            v-model="manageForm.kita_id"
                            :items="kitas"
                            :error-messages="errors.kita_id"
                            item-title="name"
                            item-value="id"
                            label="Kita"
                        ></v-select>
                    </v-col>
                    <v-col cols="12" sm="2">
                        <v-checkbox
                            v-model="manageForm.is_daz"
                            label="Ist Daz"
                        ></v-checkbox>
                    </v-col>
                </v-row>

                <v-row>
                    <div class="domains-list-container"
                         v-for="domain in domains"
                         :key="domain.id">
                        <!--                                                green yellow-->
                        <h3>{{domain.name}}</h3>

                        <div class="subdomains-list-container"
                             v-for="subdomain in domain.subdomains"
                             :key="subdomain.id">
                            <div class="subdomains-list-head">
                                <h4>{{subdomain.name}}</h4>
                                <div class="radio-wrap radio-head">
                                    <span>Noch Nicht</span>
                                </div>
                                <div class="radio-wrap radio-head">
                                    <span>Ansatzweise</span>
                                </div>
                                <div class="radio-wrap radio-head">
                                    <span>Weitgehend</span>
                                </div>
                                <div class="radio-wrap radio-head">
                                    <span>Zuverlassig</span>
                                </div>
                            </div>

                            <div class="milestone-list-container"
                                 v-for="milestone in subdomain.milestones"
                                 :key="milestone.id">

                                <h5 :class="{ error: errors[`ratings.${milestone.domain_index}.milestones.${milestone.index}.value`] }">{{milestone.abbreviation}}</h5>
                                <div class="milestone-list-text"
                                     :class="{ error: errors[`ratings.${milestone.domain_index}.milestones.${milestone.index}.value`] }">
                                    <span>{{milestone.title}}</span>
                                    <p>{{milestone.text}}</p>
                                </div>

                                <fieldset :class="{ error: errors[`ratings.${milestone.domain_index}.milestones.${milestone.index}.value`] }">
                                    <div class="radio-wrap radio-content">
                                        <input type="radio" v-model="manageForm.ratings[milestone.domain_index].milestones[milestone.index].value" :name="milestone.id + 'check-radio'" value="1"/>
                                    </div>
                                    <div class="radio-wrap radio-content">
                                        <input type="radio" v-model="manageForm.ratings[milestone.domain_index].milestones[milestone.index].value" :name="milestone.id + 'check-radio'" value="2"/>
                                    </div>
                                    <div class="radio-wrap radio-content">
                                        <input type="radio" v-model="manageForm.ratings[milestone.domain_index].milestones[milestone.index].value" :name="milestone.id + 'check-radio'" value="3"/>
                                    </div>
                                    <div class="radio-wrap radio-content">
                                        <input type="radio" v-model="manageForm.ratings[milestone.domain_index].milestones[milestone.index].value" :name="milestone.id + 'check-radio'" value="4"/>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </v-row>
            </v-container>

            <v-container>
                <v-row>
                    <v-col cols="12" sm="6">
                        <v-hover v-slot:default="{ isHovering, props }">
                            <v-btn @click="clear" v-bind="props" :color="isHovering ? 'primary' : 'accent'">Back</v-btn>
                        </v-hover>
                    </v-col>
                    <v-col cols="12" sm="6" align="right">
                        <v-hover v-slot:default="{ isHovering, props }">
                            <v-btn class="mr-2" variant="text" v-bind="props" :color="isHovering ? 'accent' : 'primary'">Save</v-btn>
                        </v-hover>
                        <v-hover v-slot:default="{ isHovering, props }">
                            <v-btn-primary @click="manageEvaluation" v-bind="props"
                                           :color="isHovering ? 'accent' : 'primary'">Big save
                            </v-btn-primary>
                        </v-hover>
                    </v-col>

                </v-row>
            </v-container>
        </div>


        <v-dialog v-model="evaluationResultState" width="95vw">
            <v-card height="95vh">
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12">
                                <p>Sind Sie sicher, dass Sie die Einrichtung {{deletingItemName}} löschen möchten?</p>
                            </v-col>
                            <v-col cols="12">
                                {{evaluationResult}}
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
    </AuthenticatedLayout>
</template>
