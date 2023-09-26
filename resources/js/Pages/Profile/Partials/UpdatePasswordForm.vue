<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <header>
            <h2 class="tw-text-lg tw-font-medium tw-text-gray-900">Update Password</h2>

            <p class="tw-mt-1 tw-text-sm tw-text-gray-600">
                Ensure your account is using a long, random password to stay secure.
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="tw-mt-6 tw-space-y-6">
            <div>
                <v-text-field type="password"
                              autocomplete="current-password"
                              v-model="form.current_password"
                              :error-messages="form.errors.current_password"
                              label="Current Password"></v-text-field>
            </div>

            <div>
                <v-text-field type="password"
                              autocomplete="new-password"
                              v-model="form.password"
                              :error-messages="form.errors.password"
                              label="New Password"></v-text-field>
            </div>

            <div>
                <v-text-field type="password"
                              autocomplete="new-password"
                              v-model="form.password_confirmation"
                              :error-messages="form.errors.password_confirmation"
                              label="Confirm Password"></v-text-field>
            </div>

            <div class="tw-flex tw-items-center tw-gap-4">
                <v-btn-primary type="submit" class="tw-ml-4" :disabled="form.processing">Save</v-btn-primary>

                <Transition enter-from-class="tw-opacity-0" leave-to-class="tw-opacity-0" class="tw-transition ease-in-out">
                    <p v-if="form.recentlySuccessful" class="tw-text-sm tw-text-gray-600">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
