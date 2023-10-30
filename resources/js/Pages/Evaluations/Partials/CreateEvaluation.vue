<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import {computed, onMounted, ref, watch} from "vue";
import {Inertia} from "@inertiajs/inertia";
import {Head, useForm, usePage, router, Link} from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ages } from "@/Composables/common"
import {v4 as uuidv4} from "uuid";


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
const loading = ref(false);

// onMounted
onMounted(() => {
    generatedUUID.value = uuidv4();
    manageForm.uuid = generatedUUID.value;

    // Prepare evaluation data
    let ratingsData = [];

    props.domains.forEach(function(item1, index1) {
        ratingsData[index1] = {
            domain: item1.id,
            milestones: [],
        }

        item1.subdomains.forEach(function(item2, index2) {
            item2.milestones.forEach(function(item3, index3) {
                ratingsData[index1].milestones.push({ id: item3.id, value: null });
            });
        });
    });

    manageForm.ratings = ratingsData;
});


const updateRatingData = (domainId, milestoneId, value) => {
    let domainIndex = manageForm.ratings.findIndex(function(obj) {
        return obj.domain === domainId;
    });

    if (domainIndex !== -1) {
        let milestoneIndex = manageForm.ratings[domainIndex].milestones.findIndex(function(obj) {
            return obj.id === milestoneId;
        });

        if (milestoneIndex !== -1) {
            manageForm.ratings[domainIndex].milestones[milestoneIndex].value = value;
        } else {
            manageForm.ratings[domainIndex].milestones.push({ id: milestoneId, value: value });
        }
    } else {
        manageForm.ratings.push({
            domain: domainId,
            milestones: [
                { id: milestoneId, value: value },
            ],
        });
    }
};


const manageForm = useForm({
    age: null,
    uuid: null,
    is_daz: false,
    user_id: currentUser.id,
    kita_id: null,
    ratings: []
});

const manageEvaluation = async () => {
    manageForm.processing = true;

    manageForm.post(route('evaluations.store'), {
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
                        <v-checkbox label="Ist Daz"></v-checkbox>
                    </v-col>
                </v-row>

                <v-row>
                    <div class="domains-list-container"
                         v-for="(domain, domainIndex) in domains"
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
                                 v-for="(milestone, milestoneIndex) in subdomain.milestones"
                                 :key="milestone.id">
                                <h5>{{milestone.abbreviation}}</h5>
                                <div class="milestone-list-text">
                                    <span>{{milestone.title}}</span>
                                    <p>{{milestone.text}}</p>
                                </div>

                                <fieldset :class="{ error: errors[`ratings.${domainIndex}.milestones.${milestoneIndex}.value`] }">
                                    <div class="radio-wrap radio-content">
                                        {{``}}
                                        <input type="radio" :name="milestone.id + 'check-radio'" value="1" @click="updateRatingData(domain.id, milestone.id, 1)"/>
                                    </div>
                                    <div class="radio-wrap radio-content">
                                        <input type="radio" :name="milestone.id + 'check-radio'" value="2" @click="updateRatingData(domain.id, milestone.id, 2)"/>
                                    </div>
                                    <div class="radio-wrap radio-content">
                                        <input type="radio" :name="milestone.id + 'check-radio'" value="3" @click="updateRatingData(domain.id, milestone.id, 3)"/>
                                    </div>
                                    <div class="radio-wrap radio-content">
                                        <input type="radio" :name="milestone.id + 'check-radio'" value="4" @click="updateRatingData(domain.id, milestone.id, 4)"/>
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
    </AuthenticatedLayout>
</template>
