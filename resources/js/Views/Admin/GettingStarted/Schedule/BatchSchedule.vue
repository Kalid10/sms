<template>
    <div
        class="container mx-auto flex h-full max-h-full w-10/12 flex-col space-y-10 px-2 pt-6 md:px-6 md:pt-6"
    >
        <div class="w-11/12 text-4xl font-semibold">Import Class Schedules</div>

        <span class="flex h-fit w-full items-center justify-between">
            <span
                class="flex h-fit w-4/12 flex-col justify-evenly space-y-12 py-10"
            >
                <span>
                    <Heading value="Class Schedule Importer" />
                    <Heading
                        :value="$t('createStudent.headingFour')"
                        size="sm"
                        class="text-xs !font-light text-zinc-700"
                    />
                </span>

                <span class="flex flex-col space-y-4">
                    <span class="text-sm font-light">
                        Here is a sample file for your reference. Please make
                        sure to follow the format of the sample file.
                    </span>
                    <a
                        class="flex items-center justify-center space-x-2 rounded-md border border-black bg-brand-400 px-3 py-1.5 text-center text-xs text-white opacity-100 transition-opacity duration-150 disabled:opacity-50"
                        href="/assets/bulk_registration_samples/admin_bulk_registration_sample.xlsx"
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
import Heading from "@/Components/Heading.vue";
import TertiaryButton from "@/Components/TertiaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { ArrowDownCircleIcon } from "@heroicons/vue/20/solid";
import FileInput from "@/Components/FileInput.vue";
import { router } from "@inertiajs/vue3";
import { inject, ref } from "vue";
import Loading from "@/Components/Loading.vue";

const emit = defineEmits(["success"]);
const showLoading = ref(false);
const showNotification = inject("showNotification");

const handleFileUploaded = (file) => {
    showLoading.value = true;
    router.post(
        "/getting-started/batch-schedule/import",
        {
            file: file,
        },
        {
            onError: (error) => {
                showNotification({
                    type: "error",
                    message: error,
                    position: "top-center",
                });
            },
            onFinish: () => {
                showLoading.value = false;
            },
        }
    );
};
</script>

<style scoped></style>
