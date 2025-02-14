<template>
    <div class="flex h-screen w-full flex-col px-5 pl-10 pt-10">
        <div class="flex w-full">
            <div class="mb-6 flex flex-col md:mb-0 md:w-3/12">
                <Heading :value="$t('createAdmin.headingOne')" />
                <Heading
                    :value="$t('createAdmin.headingTwo')"
                    size="sm"
                    class="text-xs !font-light text-zinc-700"
                />
            </div>
            <div class="w-8/12 rounded-lg bg-white">
                <AdminFormElement
                    :title="$t('createAdmin.adminFormElementTitle')"
                    @submit="submit"
                    @cancel="form.reset()"
                >
                    <AdminTextInput
                        v-model="form.name"
                        class="w-full"
                        :label="$t('common.name')"
                        :placeholder="$t('createAdmin.namePlaceholder')"
                        :error="form.errors.name"
                        required
                    />

                    <div class="flex gap-3">
                        <AdminTextInput
                            v-model="form.email"
                            class="w-full"
                            :label="$t('common.email')"
                            type="email"
                            :error="form.errors.email"
                            :placeholder="$t('common.email')"
                            required
                        />
                        <AdminTextInput
                            v-model="form.phone_number"
                            class="w-full"
                            :placeholder="$t('createAdmin.phoneNumber')"
                            :label="$t('createAdmin.phoneNumber')"
                            :error="form.errors.phone_number"
                            required
                        />
                    </div>
                    <AdminTextInput
                        v-model="form.username"
                        class="w-full"
                        :placeholder="$t('createAdmin.usernamePlaceholder')"
                        :label="$t('createAdmin.usernameLabel')"
                        :error="form.errors.username"
                        required
                    />

                    <div class="flex gap-3">
                        <AdminTextInput
                            v-model="form.position"
                            class="w-full"
                            :placeholder="$t('createAdmin.position')"
                            :label="$t('createAdmin.position')"
                            :error="form.errors.position"
                            required
                        />
                        <AdminSelectInput
                            v-model="form.gender"
                            class="w-full cursor-pointer"
                            :options="genderOptions"
                            :placeholder="$t('createAdmin.genderPlaceholder')"
                            :label="$t('createAdmin.genderLabel')"
                            required
                        />
                    </div>
                </AdminFormElement>
            </div>
        </div>
        <div class="mt-10 flex md:w-full">
            <div class="mb-6 flex shrink-0 md:mb-0 md:w-3/12">
                <div class="flex flex-col space-y-10">
                    <span>
                        <Heading :value="$t('createAdmin.headingThree')" />
                        <Heading
                            :value="$t('createStudent.headingFour')"
                            size="sm"
                            class="text-xs !font-light text-zinc-700"
                        />
                    </span>
                    <a
                        class="flex items-center justify-center space-x-2 rounded-md border border-black bg-brand-400 px-3 py-1.5 text-center text-xs text-white opacity-100 transition-opacity duration-150 disabled:opacity-50"
                        href="/assets/bulk_registration_samples/admin_bulk_registration_sample.xlsx"
                        download
                    >
                        <ArrowDownCircleIcon class="w-4" />
                        <span> Download Sample </span>
                    </a>
                </div>

                <div
                    v-if="!showManual"
                    class="flex w-6/12 items-start justify-center py-4"
                >
                    <QuestionMarkCircleIcon
                        class="h-10 cursor-pointer text-zinc-700 hover:scale-125"
                        @click="showManual = !showManual"
                    />
                </div>
            </div>
            <div class="flex w-8/12 items-center justify-center">
                <div class="relative w-full flex-col rounded-lg bg-white">
                    <GuardianFileInput
                        max-file-size="10000000"
                        @file-uploaded="handleFileUploaded"
                    />

                    <div class="absolute right-0 mt-4">
                        <PrimaryButton title="Submit" class="bg-brand-450" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <Modal v-model:view="showManual" class-style="max-w-5xl">
        <AdminSample />
    </Modal>
</template>

<script setup>
import AdminFormElement from "@/Components/FormElement.vue";
import AdminTextInput from "@/Components/TextInput.vue";
import Heading from "@/Components/Heading.vue";
import { router, useForm } from "@inertiajs/vue3";
import AdminSelectInput from "@/Components/SelectInput.vue";
import { useI18n } from "vue-i18n";
import {
    ArrowDownCircleIcon,
    QuestionMarkCircleIcon,
} from "@heroicons/vue/20/solid";
import AdminSample from "@/Views/Admin/Users/Samples/Admin.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import GuardianFileInput from "@/Components/FileInput.vue";
import { inject, ref } from "vue";
import Modal from "@/Components/Modal.vue";

const { t } = useI18n();
defineEmits(["file-uploaded"]);

const showManual = ref(false);

const genderOptions = [
    { value: "male", label: t("common.male") },
    { value: "female", label: t("common.female") },
];

const showNotification = inject("showNotification");
const handleFileUploaded = (file) => {
    router.post(
        "/register-bulk",
        {
            user_file: file,
            user_type: "admin",
        },
        {
            onError: (error) => {
                showNotification({
                    type: "error",
                    message: error.headers,
                    position: "top-center",
                });
            },
        }
    );
};

const form = useForm({
    name: "",
    username: "",
    type: "admin",
    email: "",
    phone_number: "",
    position: "",
    gender: "",
});

const submit = () => {
    form.post(route("register.admin"), {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>
