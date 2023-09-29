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

const currentUser = usePage().props.auth.user ?? {};

const showingNavigationDropdown = ref(false);
const topBar = ref(null);

let menuItems = ref({});

onMounted(() => {
    if (currentUser.is_super_admin || currentUser.is_admin) {
        menuItems.value['users.index'] = 'Users';
    }

    menuItems.value['profile.edit'] = 'Profile';
});

const errors = computed(() => props.errors ?? usePage().props.errors);
const successes = computed(() => props.successes ?? usePage().props.successes);

const clearErrorsAndSuccesses = () => {
    // props.errors = [];
    // props.successes = [];
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

                    <template v-for="(title, name) in menuItems">
                        <v-list-item link :href="route(name)" :active="route().current(name)" :title="title"></v-list-item>
                    </template>
                </v-list>

                <!--Bottom sidebar side-->
                <template v-slot:append>
                    <div class="pa-2">
                       <Link :href="route('auth.logout')" method="post">
                         <v-btn block>Abmelden</v-btn>
                       </Link>
                    </div>
                </template>
            </v-navigation-drawer>

            <v-app-bar>
                <div class="tw-max-w-full tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8">
                    {{ $page.props.auth.user.full_name }}
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
