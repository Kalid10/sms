<template>
    <div class="flex h-screen w-full flex-col px-5 pt-10 pl-10">
        <div class="grid-rows-12 grid sm:grid-cols-12">
            <div
                class="col-span-4 col-start-1 mb-6 flex shrink-0 flex-col px-3 md:mb-0 md:w-full"
            >
                <Heading :value="$t('createAdmin.headingOne')" />
                <Heading
                    :value="$t('createAdmin.headingTwo')"
                    size="sm"
                    class="text-xs !font-light text-zinc-700"
                />
            </div>
            <div class="col-span-7 col-start-5">
                <div class="w-full max-w-4xl rounded-lg bg-white">
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
                                :placeholder="
                                    $t('createAdmin.genderPlaceholder')
                                "
                                :label="$t('createAdmin.genderLabel')"
                                required
                            />
                        </div>
                    </AdminFormElement>
                </div>
            </div>
        </div>
        <div class="grid-rows-12 mt-10 mb-4 grid sm:grid-cols-12 md:w-full">
            <div
                class="col-start-1 mb-6 flex shrink-0 flex-col md:mb-0 md:w-full"
                :class="showManual ? 'col-span-7' : 'col-span-4'"
            >
                <Heading :value="$t('createStudent.headingThree')" />
                <Heading
                    :value="$t('createStudent.headingFour')"
                    size="sm"
                    class="text-xs !font-light text-zinc-700"
                />
                <div
                    v-if="!showManual"
                    class="flex w-6/12 items-center justify-center py-4"
                >
                    <QuestionMarkCircleIcon
                        class="h-8 cursor-pointer text-zinc-700 hover:scale-125"
                        @click="showManual = !showManual"
                    />
                </div>
                <div class="flex w-11/12 flex-col space-y-2">
                    <div v-if="showManual" class="py-4">
                        <AdminSample />
                    </div>
                </div>
            </div>
            <div
                class="flex items-center justify-center"
                :class="
                    showManual
                        ? 'col-span-4 col-start-8'
                        : 'col-span-7 col-start-5'
                "
            >
                <div
                    class="relative w-full max-w-4xl flex-col rounded-lg bg-white"
                >
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
</template>

<script setup>
import AdminFormElement from "@/Components/FormElement.vue";
import AdminTextInput from "@/Components/TextInput.vue";
import Heading from "@/Components/Heading.vue";
import { router, useForm } from "@inertiajs/vue3";
import AdminSelectInput from "@/Components/SelectInput.vue";
import { useI18n } from "vue-i18n";
import { QuestionMarkCircleIcon } from "@heroicons/vue/20/solid";
import AdminSample from "@/Views/Admin/Users/Samples/Admin.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import GuardianFileInput from "@/Components/FileInput.vue";
import { ref } from "vue";

const { t } = useI18n();
defineEmits(["file-uploaded"]);

const showManual = ref(false);

const genderOptions = [
    { value: "male", label: t("common.male") },
    { value: "female", label: t("common.female") },
];

const handleFileUploaded = (file) => {
    router.post("/register-bulk", {
        user_file: file,
        user_type: "admin",
    });
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

const bulkForm = useForm({
    file: "",
});

const submit = () => {
    form.post(route("register.admin"), {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>
