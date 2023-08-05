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
                    class="text-xs !font-light text-zinc-700"
                />
            </div>
            <div class="col-span-7 col-start-5">
                <div class="w-full max-w-4xl rounded-lg bg-white">
                    <GuardianFormElement
                        :title="$t('createStudent.guardianFormElementTitle')"
                        @cancel="form.reset()"
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
                        <StudentSample />
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
import GuardianFormElement from "@/Components/FormElement.vue";
import GuardianTextInput from "@/Components/TextInput.vue";
import GuardianFileInput from "@/Components/FileInput.vue";
import Heading from "@/Components/Heading.vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import GuardianDatePicker from "@/Components/DatePicker.vue";
import GuardianSelectInput from "@/Components/SelectInput.vue";
import StudentSample from "@/Views/Admin/Users/Samples/Student.vue";
import { QuestionMarkCircleIcon } from "@heroicons/vue/20/solid";
import { value } from "lodash/seq";
import { useI18n } from "vue-i18n";
import { computed, ref } from "vue";

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
        user_type: "student",
    });
};
const levels = computed(() => usePage().props.levels);

const levelOptions = computed(() => {
    return levels.value.map((level) => {
        return {
            value: level.id,
            label: `Grade ${level.name}`,
        };
    });
});

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
