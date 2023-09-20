<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import DashboardMessagesAndErrors from '@/Components/DashboardMessagesAndErrors.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

const props = defineProps({
    errors: Object,
    successes: Object,
});

const currentUser = usePage().props.auth.user ?? {};

const showingNavigationDropdown = ref(false);

const menuItems = {
    'dashboard.index': 'Dashboard',
    'users.index': 'Users',
};

const errors = computed(() => props.errors ?? usePage().props.errors);
const successes = computed(() => props.successes ?? usePage().props.successes);

const clearErrorsAndSuccesses = () => {
    // TODO: Finish this!
    // props.errors = [];
    // props.successes = [];
};
</script>

<template>
    <div>
        <div class="tw-min-h-screen tw-bg-gray-100">
            <nav class="tw-bg-white tw-border-b tw-border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="tw-max-w-7xl tw-mx-auto tw-px-4 sm:tw-px-6 lg:tw-px-8">
                    <div class="tw-flex tw-justify-between tw-h-16">
                        <div class="tw-flex">
                            <!-- Logo -->
                            <div class="tw-shrink-0 tw-flex tw-items-center">
                                <Link :href="route('dashboard.index')">
                                    <ApplicationLogo
                                        class="tw-block tw-h-9 tw-w-auto tw-fill-current tw-text-gray-800"
                                    />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="tw-hidden tw-space-x-8 sm:tw--my-px sm:tw-ml-10 sm:tw-flex">
                                <template v-for="(title, name) in menuItems">
                                    <NavLink :href="route(name)" :active="route().current(name)">
                                        {{ title }}
                                    </NavLink>
                                </template>
                            </div>
                        </div>

                        <div class="tw-hidden sm:tw-flex sm:tw-items-center sm:tw-ml-6">
                            <!-- Settings Dropdown -->
                            <div class="tw-ml-3 tw-relative">
                                <v-menu>
                                    <template v-slot:activator="{ props }">
                                        <v-btn-dropdown v-bind="props">
                                            Activator slot
                                            {{ $page.props.auth.user.name }}
                                            <svg
                                                class="tw-ml-2 tw--mr-0.5 tw-h-4 tw-w-4"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                        </v-btn-dropdown>
                                    </template>
                                    <v-list>
                                        <v-list-item>
                                            <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
                                        </v-list-item>
                                        <v-list-item>
                                            <DropdownLink :href="route('auth.logout')" method="post" as="button">
                                                Log Out
                                            </DropdownLink>
                                        </v-list-item>
                                    </v-list>
                                </v-menu>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="tw--mr-2 tw-flex tw-items-center sm:tw-hidden">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="tw-inline-flex tw-items-center tw-justify-center tw-p-2 tw-rounded-md tw-text-gray-400 hover:tw-text-gray-500 hover:tw-bg-gray-100 focus:tw-outline-none focus:tw-bg-gray-100 focus:tw-text-gray-500 tw-transition tw-duration-150 tw-ease-in-out"
                            >
                                <svg class="tw-h-6 tw-w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{
                                            'tw-hidden': showingNavigationDropdown,
                                            'tw-inline-flex': !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            'tw-hidden': !showingNavigationDropdown,
                                            'tw-inline-flex': showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{ 'tw-block': showingNavigationDropdown, 'tw-hidden': !showingNavigationDropdown }"
                    class="sm:tw-hidden"
                >
                    <div class="tw-pt-2 tw-pb-3 tw-space-y-1">
                        <template v-for="(title, name) in menuItems">
                            <ResponsiveNavLink :href="route(name)" :active="route().current(name)">
                                {{ title }}
                            </ResponsiveNavLink>
                        </template>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="tw-pt-4 tw-pb-1 tw-border-t tw-border-gray-200">
                        <div class="tw-px-4">
                            <div class="tw-font-medium tw-text-base tw-text-gray-800">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="tw-font-medium tw-text-sm tw-text-gray-500">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="tw-mt-3 tw-space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')"> Profile </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('auth.logout')" method="post" as="button">
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="tw-bg-white tw-shadow" v-if="$slots.header">
                <div class="tw-max-w-7xl tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8">
                    <slot name="header" />
                </div>

                <DashboardMessagesAndErrors :errors="errors"
                                            :successes="successes"
                                            @childClick="clearErrorsAndSuccesses"/>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
