<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});

const user = usePage().props.auth.user;

const form = useForm({
    first_name: user.first_name,
    last_name: user.last_name,
    email: user.email,
});
</script>

<template>
    <section>
        <header>
            <h2 class="tw-text-lg tw-font-medium tw-text-gray-900">Profilinformationen</h2>

            <p class="tw-mt-1 tw-text-sm tw-text-gray-600">
                Aktualisieren Sie die Profilinformationen und die E-Mail-Adresse Ihres Kontos.
            </p>
        </header>

        <form @submit.prevent="form.patch(route('profile.update'))" class="tw-mt-6 tw-space-y-6">
            <div>
                <v-text-field type="text"
                              v-model="form.first_name"
                              :error-messages="form.errors.first_name"
                              label="Vorname"></v-text-field>
            </div>

            <div>
                <v-text-field type="text"
                              v-model="form.last_name"
                              :error-messages="form.errors.last_name"
                              label="Nachname"></v-text-field>
            </div>

            <div>
                <v-text-field type="email"
                              v-model="form.email"
                              :error-messages="form.errors.email"
                              label="Email"></v-text-field>
            </div>

            <div v-if="props.mustVerifyEmail && user.email_verified_at === null">
                <p class="tw-text-sm tw-mt-2 tw-text-gray-800">
                    Ihre E-Mail-Adresse ist nicht verifiziert.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="tw-underline tw-text-sm tw-text-gray-600 hover:tw-text-gray-900 tw-rounded-md focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-offset-2 focus:tw-ring-indigo-500"
                    >
                        Klicken Sie hier, um die Verifizierungs-E-Mail erneut zu senden.
                    </Link>
                </p>

                <div
                    v-show="props.status === 'verification-link-sent'"
                    class="tw-mt-2 tw-font-medium tw-text-sm tw-text-green-600"
                >
                    Ein neuer Bestätigungslink wurde an Ihre E-Mail-Adresse gesendet.
                </div>
            </div>

            <div class="tw-flex tw-items-center tw-gap-4">
                <v-btn-primary type="submit" class="tw-ml-4" :disabled="form.processing">Speichern</v-btn-primary>

                <Transition enter-from-class="tw-opacity-0" leave-to-class="tw-opacity-0" class="tw-transition tw-ease-in-out">
                    <p v-if="form.recentlySuccessful" class="tw-text-sm tw-text-gray-600">Gespeichert.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
