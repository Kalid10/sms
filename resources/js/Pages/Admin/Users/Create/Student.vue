<template>
    <div class="flex h-screen flex-col px-5 pt-10 pl-10">
        <div class="grid-rows-12 grid sm:grid-cols-12">
            <div
                class="col-span-4 col-start-1 mb-6 flex shrink-0 flex-col px-3 md:mb-0 md:w-full"
            >
                <Heading :value="$t('createStudent.headingOne')" />
                <Heading
                    :value="$t('createStudent.headingTwo')"
                    size="sm"
                    class="text-xs !font-light text-brand-text-300"
                />
            </div>
            <div class="col-span-7 col-start-5">
                <div class="w-full max-w-4xl rounded-lg bg-white">
                    <GuardianFormElement
                        :title="$t('createStudent.guardianFormElementTitle')"
                        @submit="submit"
                    >
                        <div class="flex gap-3">
                            <GuardianTextInput
                                v-model="form.name"
                                class="w-full"
                                :label="$t('createStudent.nameLabel')"
                                :placeholder="$t('common.name')"
                                :error="form.errors.name"
                                required
                            />
                        </div>
                        <div class="flex gap-3">
                            <GuardianSelectInput
                                v-model="form.level_id"
                                class="w-full cursor-pointer"
                                :options="levelOptions"
                                :label="$t('createStudent.levelIdLabel')"
                                :placeholder="
                                    $t('createStudent.levelIdPlaceholder')
                                "
                                required
                            />
                            <GuardianSelectInput
                                v-model="form.gender"
                                class="w-full cursor-pointer"
                                :options="genderOptions"
                                :label="$t('createStudent.genderLabel')"
                                :placeholder="
                                    $t('createStudent.genderPlaceholder')
                                "
                                required
                            />
                        </div>

                        <div class="flex gap-3">
                            <GuardianDatePicker
                                v-model="form.date_of_birth"
                                :label="$t('createStudent.studentDateOfBirth')"
                                class="w-full cursor-pointer"
                            />
                            <GuardianSelectInput
                                v-model="form.guardian_relation"
                                class="w-full cursor-pointer"
                                :options="relationOptions"
                                :label="
                                    $t('createStudent.guardianRelationLabel')
                                "
                                :placeholder="
                                    $t(
                                        'createStudent.guardianRelationPlaceholder'
                                    )
                                "
                                required
                            />
                        </div>

                        <div class="flex gap-3">
                            <GuardianTextInput
                                v-model="form.guardian_name"
                                class="w-full"
                                :label="$t('createStudent.guardianName')"
                                :placeholder="$t('common.name')"
                                :error="form.errors.guardian_name"
                                required
                            />
                            <GuardianTextInput
                                v-model="form.guardian_phone_number"
                                class="w-full"
                                :label="
                                    $t('createStudent.guardianPhoneNumberLabel')
                                "
                                :placeholder="
                                    $t(
                                        'createStudent.guardianPhoneNumberPlaceholder'
                                    )
                                "
                                type="number"
                                :error="form.errors.guardian_phone_number"
                                required
                            />
                        </div>
                        <div class="flex gap-3">
                            <GuardianTextInput
                                v-model="form.guardian_email"
                                type="email"
                                class="w-full"
                                :label="$t('createStudent.guardianEmailLabel')"
                                :placeholder="$t('common.email')"
                                :error="form.errors.guardian_email"
                            />
                            <GuardianSelectInput
                                v-model="form.guardian_gender"
                                class="w-full cursor-pointer"
                                :options="genderOptions"
                                :label="$t('createStudent.guardianGenderLabel')"
                                :placeholder="
                                    $t(
                                        'createStudent.guardianGenderPlaceholder'
                                    )
                                "
                                required
                            />
                        </div>
                    </GuardianFormElement>
                </div>
            </div>
        </div>

        <div class="grid-rows-12 mt-10 mb-4 grid sm:grid-cols-12 md:w-full">
            <div
                class="col-span-4 col-start-1 mb-6 flex shrink-0 flex-col md:mb-0 md:w-full"
            >
                <Heading :value="$t('createStudent.headingThree')" />
                <Heading
                    :value="$t('createStudent.headingFour')"
                    size="sm"
                    class="text-xs !font-light text-brand-text-300"
                />
            </div>
            <div class="col-span-7 col-start-5">
                <div
                    class="relative w-full max-w-4xl flex-col rounded-lg bg-white"
                >
                    <GuardianFileInput
                        max-file-size="10000000"
                        @file-uploaded="handleFileUploaded"
                    />

                    <div class="absolute right-0 mt-4">
                        <GuardianPrimaryButton title="Submit" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import GuardianFormElement from "@/Components/FormElement.vue";
import GuardianTextInput from "@/Components/TextInput.vue";
import GuardianFileInput from "@/Components/FileInput.vue";
import Heading from "@/Components/Heading.vue";
import { useForm } from "@inertiajs/vue3";
import GuardianPrimaryButton from "@/Components/PrimaryButton.vue";
import GuardianDatePicker from "@/Components/DatePicker.vue";
import GuardianSelectInput from "@/Components/SelectInput.vue";
import { value } from "lodash/seq";
import { useI18n } from "vue-i18n";

const { t } = useI18n();
defineEmits(["file-uploaded"]);

const genderOptions = [
    { value: "male", label: t("common.male") },
    { value: "female", label: t("common.female") },
];

const handleFileUploaded = (file) => {
    // Todo: Remove this console.log when notification is implemented
    console.log("File uploaded:", file);
};

const levelOptions = [
    { value: "KG-1", label: "KG 1" },
    { value: "KG-2", label: "KG 2" },
    { value: "KG-3", label: "KG 3" },
    { value: "1", label: "Grade 1" },
    { value: "2", label: "Grade 2" },
    { value: "3", label: "Grade 3" },
    { value: "4", label: "Grade 4" },
    { value: "5", label: "Grade 5" },
    { value: "6", label: "Grade 6" },
    { value: "7", label: "Grade 7" },
    { value: "8", label: "Grade 8" },
    { value: "9", label: "Grade 9" },
    { value: "10", label: "Grade 10" },
    { value: "11", label: "Grade 11" },
    { value: "12", label: "Grade 12" },
];

const relationOptions = [
    { value: "father", label: t("createStudent.father") },
    { value: "mother", label: t("createStudent.mother") },
    { value: "other", label: t("createStudent.other") },
];

const form = useForm({
    name: "",
    gender: "",
    date_of_birth: null,
    type: "student",
    guardian_name: "",
    guardian_email: "",
    guardian_phone_number: "",
    guardian_gender: "",
    level_id: "",
    guardian_relation: "",
});

const bulkForm = useForm({
    file: "",
});

const submit = () => {
    form.post(route("register.guardian"), {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>
