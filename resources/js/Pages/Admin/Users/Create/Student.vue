<template>
    <div class="flex h-screen w-full flex-col px-5 pl-10 pt-10">
        <div class="flex w-full">
            <div class="mb-6 flex shrink-0 flex-col px-3 md:mb-0 md:w-3/12">
                <Heading :value="$t('createStudent.headingOne')" />
                <Heading
                    :value="$t('createStudent.headingTwo')"
                    size="sm"
                    class="text-xs !font-light text-zinc-700"
                />
            </div>
            <div class="w-8/12 rounded-lg bg-white">
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
                            v-model="form.batch_id"
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
                            :placeholder="$t('createStudent.genderPlaceholder')"
                            required
                        />
                    </div>

                    <div class="flex gap-3">
                        <GuardianDatePicker
                            v-model="form.date_of_birth"
                            :label="$t('createStudent.studentDateOfBirth')"
                            class="w-full cursor-pointer"
                        />
                    </div>

                    <Toggle
                        v-model="linkToExistingGuardian"
                        label-location="top"
                        :label="$t('createStudent.linkToAnExistingParent')"
                        class="my-2 mb-5 min-w-fit"
                    />
                    <div v-if="linkToExistingGuardian" class="">
                        <GuardianSearch
                            @select-guardian="handleSelectedGuardian"
                        />
                    </div>
                    <div v-if="!linkToExistingGuardian">
                        <div class="flex gap-3">
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
                    </div>
                </GuardianFormElement>
            </div>
        </div>

        <div class="mt-10 flex md:w-full">
            <div class="mb-6 flex shrink-0 md:mb-0 md:w-3/12">
                <div class="">
                    <Heading :value="$t('createStudent.headingThree')" />
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
        <StudentSample />
    </Modal>
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
import { computed, inject, ref } from "vue";
import Modal from "@/Components/Modal.vue";
import Toggle from "@/Components/Toggle.vue";
import GuardianSearch from "@/Views/Admin/Users/GuardianSearch.vue";

const { t } = useI18n();
defineEmits(["file-uploaded"]);

const showManual = ref(false);
const linkToExistingGuardian = ref(false);

const handleSelectedGuardian = (guardian) => {
    form.existing_guardian_id = guardian.id;
};

const genderOptions = [
    { value: "male", label: t("common.male") },
    { value: "female", label: t("common.female") },
];

const handleFileUploaded = (file) => {
    router.post(
        "/register-bulk",
        {
            user_file: file,
            user_type: "student",
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
const levels = computed(() => usePage().props.levels);

const levelOptions = computed(() => {
    return levels.value.flatMap((level) => {
        return level.batches.map((batch) => {
            return {
                value: batch.id,
                label: ` Grade ${level.name} ${batch.section} - ${batch.students.length} / ${batch.max_students}`,
            };
        });
    });
});

const relationOptions = [
    { value: "father", label: t("createStudent.father") },
    { value: "mother", label: t("createStudent.mother") },
    { value: "other", label: t("createStudent.other") },
];

const guardianOptions = computed(() => {
    return usePage().props.guardians.map((guardian) => {
        return {
            value: guardian.id,
            label: guardian.name,
        };
    });
});

const form = useForm({
    name: "",
    gender: "",
    date_of_birth: null,
    type: "student",
    guardian_name: "",
    guardian_email: "",
    guardian_phone_number: "",
    guardian_gender: "",
    batch_id: "",
    guardian_relation: "",
    existing_guardian_id: "",
});

const bulkForm = useForm({
    file: "",
});

const showNotification = inject("showNotification");

const submit = () => {
    form.post(route("register.guardian"), {
        onSuccess: () => {
            form.reset();
        },
        onError: (error) => {
            if (error.date_of_birth)
                showNotification({
                    type: "error",
                    message: error.date_of_birth,
                    position: "top-center",
                });
        },
    });
};
</script>
