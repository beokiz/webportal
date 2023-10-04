<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.reset();
};
</script>

<template>
    <section class="tw-space-y-6">
        <header>
            <h2 class="tw-text-lg tw-font-medium tw-text-gray-900">Konto löschen</h2>

            <p class="tw-mt-1 tw-text-sm tw-text-gray-600">
                Sobald Ihr Konto gelöscht ist, werden alle zugehörigen Ressourcen und Daten dauerhaft gelöscht. Bevor
                Sie Ihr Konto löschen, laden Sie bitte alle Daten oder Informationen herunter, die Sie behalten möchten.
            </p>
        </header>

        <DangerButton @click="confirmUserDeletion">Konto löschen</DangerButton>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="tw-p-6">
                <h2 class="tw-text-lg tw-font-medium tw-text-gray-900">
                    Sind Sie sicher, dass Sie Ihr Konto löschen möchten?
                </h2>

                <p class="tw-mt-1 tw-text-sm tw-text-gray-600">
                    Sobald Ihr Konto gelöscht ist, werden alle zugehörigen Ressourcen und Daten dauerhaft gelöscht.
                    Bitte geben Sie Ihr Passwort ein, um zu bestätigen, dass Sie Ihr Konto dauerhaft löschen möchten.
                </p>

                <div class="tw-mt-6">
                    <InputLabel for="password" value="Password" class="tw-sr-only" />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="tw-mt-1 tw-block tw-w-3/4"
                        placeholder="Passwort"
                        @keyup.enter="deleteUser"
                    />

                    <InputError :message="form.errors.password" class="tw-mt-2" />
                </div>

                <div class="tw-mt-6 tw-flex tw-justify-end">
                    <SecondaryButton @click="closeModal"> Abbrechen </SecondaryButton>

                    <DangerButton
                        class="tw-ml-3"
                        :class="{ 'tw-opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        Konto löschen
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
