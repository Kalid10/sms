<template>
    <div class="flex h-screen w-full flex-col px-5 pl-10 pt-10">
        <div class="flex w-full">
            <div class="mb-6 flex flex-col md:mb-0 md:w-3/12">
                <Heading :value="$t('createTeacher.headerOne')" />
                <Heading
                    :value="$t('createTeacher.headerTwo')"
                    size="sm"
                    class="text-xs !font-light text-zinc-700"
                />
            </div>
            <div class="w-8/12 rounded-lg bg-white">
                <TeacherFormElement
                    :title="$t('createTeacher.registerTeacher')"
                    @submit="submit"
                    @cancel="form.reset()"
                >
                    <TeacherTextInput
                        v-model="form.name"
                        :label="$t('common.name')"
                        :placeholder="$t('createTeacher.namePlaceholder')"
                        :error="form.errors.name"
                        required
                    />

                    <div class="flex gap-3">
                        <TeacherTextInput
                            v-model="form.username"
                            class="w-full"
                            :label="$t('createTeacher.usernameLabel')"
                            :placeholder="
                                $t('createTeacher.usernamePlaceholder')
                            "
                            :error="form.errors.username"
                            required
                        />

                        <TeacherTextInput
                            v-model="form.phone_number"
                            class="w-full"
                            :label="$t('createTeacher.phoneNumberLabel')"
                            :placeholder="
                                $t('createTeacher.phoneNUmberPlaceholder')
                            "
                            :error="form.errors.phone_number"
                            required
                        />
                    </div>

                    <div class="flex gap-3">
                        <TeacherTextInput
                            v-model="form.email"
                            class="w-full"
                            :label="$t('common.email')"
                            :placeholder="$t('common.email')"
                            type="email"
                            :error="form.errors.email"
                            required
                        />
                        <TeacherSelectInput
                            v-model="form.gender"
                            class="w-full cursor-pointer"
                            :label="$t('common.gender')"
                            :placeholder="$t('createTeacher.genderPlaceholder')"
                            :error="form.errors.gender"
                            :options="genderOptions"
                            required
                        />
                    </div>
                </TeacherFormElement>
            </div>
        </div>
        <div class="mt-10 flex md:w-full">
            <div class="mb-6 flex shrink-0 md:mb-0 md:w-3/12">
                <div class="">
                    <Heading :value="$t('createTeacher.headerThree')" />
                    <Heading
                        :value="$t('createStudent.headingFour')"
                        size="sm"
                        class="text-xs !font-light text-zinc-700"
                    />
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
        <TeacherSample />
    </Modal>
</template>

<script setup>
import TeacherFormElement from "@/Components/FormElement.vue";
import TeacherTextInput from "@/Components/TextInput.vue";
import TeacherSelectInput from "@/Components/SelectInput.vue";
import Heading from "@/Components/Heading.vue";
import { router, useForm } from "@inertiajs/vue3";
import { useI18n } from "vue-i18n";
import { QuestionMarkCircleIcon } from "@heroicons/vue/20/solid";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import GuardianFileInput from "@/Components/FileInput.vue";
import TeacherSample from "@/Views/Admin/Users/Samples/Teacher.vue";
import { ref } from "vue";
import Modal from "@/Components/Modal.vue";

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
        user_type: "teacher",
    });
};

const form = useForm({
    name: "",
    type: "teacher",
    email: "",
    phone_number: "",
    username: "",
    gender: "",
});

const bulkForm = useForm({
    file: "",
});

const submit = () => {
    form.post(route("register.admin"));
};
</script>
