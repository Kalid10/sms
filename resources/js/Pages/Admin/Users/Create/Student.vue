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
                class="col-span-4 col-start-1 mb-6 flex shrink-0 flex-col md:mb-0 md:w-full"
            >
                <Heading :value="$t('createStudent.headingThree')" />
                <Heading
                    :value="$t('createStudent.headingFour')"
                    size="sm"
                    class="text-xs !font-light text-zinc-700"
                />
            </div>
            <div class="col-span-7 col-start-5">
                <div
                    class="relative w-full max-w-4xl flex-col rounded-lg bg-white"
                >
                    <div v-if="showManual">
                        <h1 class="mb-4 text-center text-lg font-bold">
                            Bulk Registration Process
                        </h1>

                        <div
                            class="mb-4 rounded-lg border border-brand-550 p-4"
                        >
                            <h2 class="text-md mb-2 font-semibold">
                                Step 1: Prepare Your File
                            </h2>
                            <p class="mb-2 text-sm">
                                Prepare a CSV file with the following columns:
                                Name, Email, Username, Grade Level. Ensure all
                                data is accurate and correctly spelled.
                            </p>
                            <p class="mb-2 text-sm">
                                Know the directory where you have saved your CSV
                                file on your computer. You'll need to navigate
                                to this location during the upload process.
                            </p>
                        </div>
                        <div
                            class="mb-4 rounded-lg border border-brand-550 p-4"
                        >
                            <h2 class="text-md mb-2 font-semibold">
                                Step 2: Go to Registration and Upload the File
                            </h2>
                            <p class="mb-2 text-sm">
                                After preparing your CSV file, click on the<span
                                    class="font-semibold"
                                >
                                    Go to registration
                                </span>
                                button. It is located at the bottom right of
                                this section.
                            </p>
                            <p class="mb-2 text-sm">
                                On the registration section, click "Upload File"
                                to open a dialogue box. Navigate to the
                                directory of your CSV file, select it, and click
                                "Select".
                            </p>
                        </div>

                        <div
                            class="mb-4 rounded-lg border border-brand-550 p-4"
                        >
                            <h2 class="text-md mb-2 font-semibold">
                                Step 3: Submit the File
                            </h2>
                            <p class="mb-2 text-sm">
                                After your file is selected, it should appear in
                                the "Upload File" section. Verify that the
                                correct file is selected, then click the
                                "Upload" button to begin the bulk registration
                                process.
                            </p>
                        </div>

                        <div class="flex justify-end" @click="upload">
                            <PrimaryButton title="Go to registration" />
                        </div>
                    </div>
                    <div v-if="showUpload">
                        <GuardianFileInput
                            max-file-size="10000000"
                            @file-uploaded="handleFileUploaded"
                        />
                    </div>

                    <div class="absolute left-0 mt-4">
                        <PrimaryButton
                            v-if="showUpload"
                            title="Go back"
                            @click="manual"
                        />
                        <GuardianFileInput
                            max-file-size="10000000"
                            @file-uploaded="handleFileUploaded"
                        />

                        <div class="absolute right-0 mt-4">
                            <PrimaryButton
                                title="Submit"
                                class="bg-brand-450"
                            />
                        </div>
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
import { value } from "lodash/seq";
import { useI18n } from "vue-i18n";
import { computed, ref } from "vue";

const { t } = useI18n();
defineEmits(["file-uploaded"]);

const showManual = ref(true);
const showUpload = ref(false);

function upload() {
    showUpload.value = true;
    showManual.value = false;
}

function manual() {
    showUpload.value = false;
    showManual.value = true;
}

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
