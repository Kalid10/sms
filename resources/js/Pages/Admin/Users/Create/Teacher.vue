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
            <div
                class="flex w-8/12 flex-col space-y-4 divide-y divide-black rounded-lg bg-white"
            >
                <TeacherFormElement
                    :title="$t('createTeacher.registerTeacher')"
                    @submit="submit"
                    @cancel="form.reset()"
                >
                    <div class="flex items-center justify-between">
                        <TeacherTextInput
                            v-model="form.name"
                            :label="$t('common.name')"
                            :placeholder="$t('createTeacher.namePlaceholder')"
                            :error="form.errors.name"
                            class="w-5/12"
                            required
                        />

                        <TeacherSelectInput
                            v-model="form.gender"
                            class="w-5/12"
                            :label="$t('common.gender')"
                            :placeholder="$t('createTeacher.genderPlaceholder')"
                            :error="form.errors.gender"
                            :options="genderOptions"
                            required
                        />
                    </div>

                    <div class="flex items-center justify-between">
                        <TeacherTextInput
                            v-model="form.phone_number"
                            class="w-5/12"
                            :label="$t('createTeacher.phoneNumberLabel')"
                            :placeholder="
                                $t('createTeacher.phoneNUmberPlaceholder')
                            "
                            :error="form.errors.phone_number"
                            type="number"
                            required
                        />
                        <TeacherTextInput
                            v-model="form.email"
                            class="w-5/12"
                            :label="$t('common.email')"
                            :placeholder="$t('common.email')"
                            type="email"
                            :error="form.errors.email"
                        />
                    </div>

                    <div class="flex flex-col space-y-2 py-4">
                        <span
                            class="py-3 text-center text-xl font-semibold capitalize"
                        >
                            Assign Class & Subject
                        </span>

                        <div class="flex justify-between space-x-2">
                            <TeacherSelectInput
                                v-model="selectedBatch"
                                :options="batchesOptions"
                                class="w-5/12"
                                placeholder="Select Class"
                                label="Select Class"
                            />
                            <span
                                v-if="selectedBatch"
                                class="flex w-5/12 flex-col space-y-5"
                            >
                                <TeacherSelectInput
                                    v-model="form.batch_subject"
                                    :options="batchSubjectOptions"
                                    class="w-full"
                                    placeholder="Select Subject"
                                    label="Select Subject"
                                    :disabled="form.teaches_all_batch_subjects"
                                />
                                <span class="flex items-center space-x-2">
                                    <Checkbox
                                        v-model="
                                            form.teaches_all_batch_subjects
                                        "
                                        label="Teaches All Subjects?"
                                        name="asf"
                                    />
                                    <span class="text-sm font-medium capitalize"
                                        >{{ form.name }} Teaches All Class
                                        Subjects?</span
                                    >
                                </span>
                            </span>
                        </div>
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
                <GuardianFileInput
                    max-file-size="10000000"
                    @file-uploaded="handleFileUploaded"
                />
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
import { router, useForm, usePage } from "@inertiajs/vue3";
import { useI18n } from "vue-i18n";
import { QuestionMarkCircleIcon } from "@heroicons/vue/20/solid";
import GuardianFileInput from "@/Components/FileInput.vue";
import TeacherSample from "@/Views/Admin/Users/Samples/Teacher.vue";
import { computed, inject, ref, watch } from "vue";
import Modal from "@/Components/Modal.vue";
import Checkbox from "@/Components/Checkbox.vue";

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
            user_type: "teacher",
        },
        {
            onError: (error) => {
                const message = ref("Something went wrong! Please try again.");

                if (error?.user_file) message.value = error.user_file;

                if (error?.header) message.value = error.header;

                showNotification({
                    type: "error",
                    message: message,
                    position: "top-center",
                });
            },
        }
    );
};

const form = useForm({
    name: "",
    type: "teacher",
    email: "",
    phone_number: "",
    username: "",
    gender: "",
    batch_id: "",
    batch_subject: "",
    teaches_all_batch_subjects: false,
});

const submit = () => {
    form.post(route("register.admin"), {
        onSuccess: () => {
            form.reset();
        },
    });
};

// Assign class and subject section
const batches = computed(() => usePage().props.batches);

const selectedBatch = ref();
const batchesOptions = computed(() => {
    return batches.value.map((batch) => {
        return {
            value: batch.id,
            label: batch.level.name + "-" + batch.section,
        };
    });
});

const batchSubjectOptions = ref();
watch(selectedBatch, () => {
    if (selectedBatch.value) {
        form.batch_id = selectedBatch.value;

        // Get subjects for selected batch
        batchSubjectOptions.value = batches.value
            .find((batch) => batch.id === selectedBatch.value)
            .subjects.map((batchSubject) => {
                return {
                    value: batchSubject.id,
                    label: batchSubject.subject.full_name,
                };
            });
    }
});
</script>
