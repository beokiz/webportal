<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import DashboardMessagesAndErrors from '@/Components/DashboardMessagesAndErrors.vue';

const props = defineProps({
    errors: Object,
    successes: Object,
});

const appVersion = usePage().props.appVersion ?? '1.0.0';
const currentUser = usePage().props.auth.user ?? {};
const isSurveyPeriod = usePage().props.isSurveyPeriod ?? false;

const topBar = ref(null);

let menuItemsList = ref({});
let menuGroupsList = ref({});

onMounted(() => {
    if (currentUser.is_super_admin || currentUser.is_admin || (currentUser.is_manager && isSurveyPeriod)) {
        menuItemsList.value['yearly_evaluations.index'] = 'Jährliche Rückmeldung';
        menuGroupsList.value['yearly_evaluations.index'] = 'yearly_evaluations.*';
    }

    if (currentUser.is_super_admin || currentUser.is_manager || currentUser.is_employer) {
        menuItemsList.value['evaluations.index'] = 'Einschätzen';
        menuGroupsList.value['evaluations.index'] = 'evaluations.*';
    }

    if (currentUser.is_manager || currentUser.is_employer) {
        menuItemsList.value['screening.index'] = 'Prüfen';
        menuGroupsList.value['screening.index'] = 'screening.*';
    }

    if (currentUser.is_super_admin || currentUser.is_admin) {
        menuItemsList.value['users.index'] = 'Benutzer';
        menuGroupsList.value['users.index'] = 'users.*';

        menuItemsList.value['domains.index'] = 'Meilensteinliste';
        menuGroupsList.value['domains.index'] = 'domains.*';
    }

    if (
        currentUser.is_super_admin ||
        currentUser.is_admin ||
        currentUser.is_manager ||
        (currentUser.is_user_multiplier && currentUser.has_self_training_operator)
    ) {
        menuItemsList.value['kitas.index'] = 'Einrichtungen';
        menuGroupsList.value['kitas.index'] = 'kitas.*';
    }

    if (currentUser.is_super_admin || currentUser.is_monitor || currentUser.is_monitor_oe) {
        menuItemsList.value['export.index'] = 'Exportieren';
        menuGroupsList.value['export.index'] = 'export.*';
    }

    if (currentUser.is_manager || currentUser.is_employer) {
        menuItemsList.value['download_area.index'] = 'Downloadbereich';
        menuGroupsList.value['download_area.index'] = 'download_area.*';
    }

    if (currentUser.is_super_admin || currentUser.is_admin) {
        menuItemsList.value['operators.index'] = 'Träger';
        menuGroupsList.value['operators.index'] = 'operators.*';
    }

    if (currentUser.is_super_admin || currentUser.is_admin || currentUser.is_user_multiplier) {
        menuItemsList.value['trainings.index'] = 'Schulungen';
        menuGroupsList.value['trainings.index'] = 'trainings.*';
    }

    if (currentUser.is_super_admin || currentUser.is_admin || currentUser.is_user_multiplier) {
        menuItemsList.value['training_proposals.index'] = 'Terminvorschläge';
        menuGroupsList.value['training_proposals.index'] = 'training_proposals.*';
    }

    if (currentUser.is_super_admin || currentUser.is_admin) {
        menuItemsList.value['settings.index'] = 'Einstellungen';
        menuGroupsList.value['settings.index'] = 'settings.*';
    }

    menuItemsList.value['profile.edit'] = 'Profil';
    menuGroupsList.value['profile.edit'] = 'profile.*';
});

const errors = computed(() => props.errors ?? usePage().props.errors);
const successes = computed(() => props.successes ?? usePage().props.successes);

const clearErrorsAndSuccesses = () => {
    //
};
</script>

<template>
    <v-responsive>
        <v-layout class="rounded rounded-md">
            <v-navigation-drawer v-model="topBar">
                <v-list>
                    <Link :href="route('main.index')">
                        <ApplicationLogo class="tw-px-6 tw-my-5"/>
                    </Link>

                    <template v-for="(title, name) in menuItemsList">
                        <Link :href="route(name)" :active="route().current(name)">
                            <v-list-item :to="route(name)" :active="route().current(menuGroupsList[name])" :title="title"></v-list-item>
                        </Link>
                    </template>
                </v-list>

                <!--Bottom sidebar side-->
                <template v-slot:append>
                  <div class="my-1 text-center">
                    <span>App Version: {{ appVersion }}</span>
                  </div>
                    <div class="pa-2">
                       <Link :href="route('auth.logout')" method="post">
                           <v-btn-primary block>Abmelden</v-btn-primary>
                       </Link>
                    </div>
                </template>
            </v-navigation-drawer>

            <v-app-bar>
                <div class="tw-max-w-full tw-flex tw-w-full tw-justify-between tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8">
                    <div class="full-name-head">
                        {{ $page.props.auth.user.full_name }}
                    </div>
                </div>

                <template v-slot:append>
                    <v-app-bar-nav-icon class="hidden-lg-and-up" @click.stop="topBar = !topBar"></v-app-bar-nav-icon>
                </template>
            </v-app-bar>

            <v-main style="min-height: 100vh;">
                <!-- Page Heading -->
                <header class="tw-bg-white tw-shadow" v-if="$slots.header">
                    <div class="tw-flex tw-items-center tw-justify-between tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8">
                        <slot name="header" />
                    </div>
                </header>

                <DashboardMessagesAndErrors :errors="errors"
                                            :successes="successes"
                                            @childClick="clearErrorsAndSuccesses"/>

                <slot />
            </v-main>
        </v-layout>
    </v-responsive>
</template>
