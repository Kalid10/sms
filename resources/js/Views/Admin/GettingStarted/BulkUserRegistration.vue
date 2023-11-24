<template>
    <div
        class="container mx-auto flex h-full max-h-full w-9/12 flex-col space-y-10 px-2 pt-6 md:px-6 md:pt-6"
    >
        <span class="pt-5 text-4xl font-semibold"
            >Now Let's Register Users</span
        >

        <span class="flex h-fit w-full items-center justify-between">
            <span
                class="flex h-fit w-4/12 flex-col justify-evenly space-y-12 py-10"
            >
                <span>
                    <Heading :value="selectedUserType + ' Bulk Registration'" />
                    <Heading
                        :value="$t('createStudent.headingFour')"
                        size="sm"
                        class="text-xs !font-light text-zinc-700"
                    />
                </span>
                <SelectInput
                    v-model="selectedUserType"
                    :options="userTypeOptions"
                    placeholder="Select User Type"
                    class="w-full"
                    label="Choose User Type"
                ></SelectInput>

                <span class="flex flex-col space-y-4">
                    <span class="text-sm font-light">
                        Here is a sample file for your reference. Please make
                        sure to follow the format of the sample file.
                    </span>
                    <a
                        class="flex items-center justify-center space-x-2 rounded-md border border-black bg-brand-400 px-3 py-1.5 text-center text-xs text-white opacity-100 transition-opacity duration-150 disabled:opacity-50"
                        :href="getSelectedUserTypeSampleLocation"
                        download
                    >
                        <ArrowDownCircleIcon class="w-4" />
                        <span> Download Sample </span>
                    </a>
                </span>
            </span>

            <span class="w-5/12">
                <FileInput
                    max-file-size="10000000"
                    @file-uploaded="handleFileUploaded"
                />
            </span>
        </span>
        <div class="flex items-center justify-end gap-3">
            <TertiaryButton class="!bg-brand-100" @click="$emit('success')"
                >Skip
            </TertiaryButton>
            <PrimaryButton @click="$emit('success')"
                >{{ $t("registerSubjects.finish") }}
            </PrimaryButton>
        </div>
    </div>
    <Loading v-if="showLoading" is-full-screen />
</template>

<script setup>
import FileInput from "@/Components/FileInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import { computed, inject, ref } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TertiaryButton from "@/Components/TertiaryButton.vue";
import { router } from "@inertiajs/vue3";
import Heading from "@/Components/Heading.vue";
import { ArrowDownCircleIcon } from "@heroicons/vue/20/solid";
import Loading from "@/Components/Loading.vue";
import { useUIStore } from "@/Store/ui";

defineEmits(["success"]);
const userTypeOptions = computed(() => [
    { value: "admin", label: "Admin" },
    { value: "teacher", label: "Teacher" },
    { value: "student", label: "Student & Guardian" },
]);
const selectedUserType = ref(userTypeOptions.value[1].value);

const showNotification = inject("showNotification");
const showLoading = ref(false);

const uiStore = useUIStore();
const handleFileUploaded = (file) => {
    showLoading.value = true;
    router.post(
        "/register-bulk",
        {
            user_file: file,
            user_type: selectedUserType.value,
        },
        {
            onError: (error) => {
                showNotification({
                    type: "error",
                    message: error.headers || error.user_file,
                    position: "top-center",
                });
            },
            onSuccess: () => {
                uiStore.setLoading(true, "Registering users..");
            },
            onFinish: () => {
                showLoading.value = false;
            },
        }
    );
};

const getSelectedUserTypeSampleLocation = computed(() => {
    switch (selectedUserType.value) {
        case "admin":
            return "/assets/bulk_registration_samples/admin_bulk_registration_sample.xlsx";
        case "teacher":
            return "/assets/bulk_registration_samples/teacher_bulk_registration_sample.xlsx";
        case "student":
            return "/assets/bulk_registration_samples/student_bulk_registration_sample.xlsx";
        default:
            return "/assets/bulk_registration_samples/admin_bulk_registration_sample.xlsx";
    }
});
</script>
<style scoped></style>
