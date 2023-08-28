<template>
    <div class="flex min-h-screen w-full justify-center">
        <div class="flex w-full flex-col space-y-6 lg:w-11/12">
            <Title class="w-5/12" :title="$t('common.assessments')" />

            <div
                class="flex h-full w-full flex-col lg:flex-row lg:justify-between lg:space-x-5"
            >
                <div
                    class="flex h-fit w-full flex-col space-y-6 rounded-lg bg-white p-5 shadow-sm lg:w-1/2"
                >
                    <div class="flex w-full justify-between">
                        <div class="text-2xl font-medium">
                            Scheduled Assessments
                        </div>
                        <PrimaryButton
                            class="rounded-full"
                            @click="showModal = true"
                        >
                            Create Assessment
                        </PrimaryButton>
                    </div>

                    <div
                        class="flex flex-col items-center justify-center space-y-2"
                    >
                        <div
                            v-for="(item, index) in mappedAssessments.data"
                            :key="index"
                            class="w-full border-b"
                        >
                            <div
                                class="group flex w-full cursor-pointer items-center justify-between py-4 text-sm hover:scale-105 hover:rounded-lg hover:bg-brand-450 hover:text-white"
                            >
                                <div
                                    class="w-2/12 border-l px-3 text-center text-xs font-light capitalize group-hover:border-none group-hover:text-center"
                                >
                                    {{ item.due_date }}
                                </div>
                                <div class="w-3/12 text-center">
                                    {{ item.level_category.name }} -
                                    {{ item.assessment_type.name }}
                                </div>
                                <div
                                    class="hidden w-3/12 text-center font-light lg:block"
                                >
                                    {{ item.user.name }}
                                </div>
                            </div>
                        </div>

                        <EmptyView
                            v-if="mappedAssessments?.data.length === 0"
                            title="No assessments found"
                        />
                    </div>

                    <Pagination
                        class="py-3"
                        position="center"
                        :links="mappedAssessments.links"
                    />
                </div>

                <div
                    class="flex h-fit w-full flex-col space-y-4 rounded-lg lg:w-5/12"
                >
                    <AssessmentTypes />
                </div>
            </div>
        </div>
    </div>

    <Modal v-model="showModal">
        <Details />
    </Modal>

    <Modal v-model:view="showModal">
        <Form class="border-none" @success="showModal = false" />
    </Modal>

    <Loading v-if="isLoading" is-full-screen />
</template>
<script setup>
import Title from "@/Views/Teacher/Views/Title.vue";
import { computed, ref } from "vue";
import AssessmentTypes from "@/Views/Admin/Assessments/AssessmentTypes/Table.vue";
import Modal from "@/Components/Modal.vue";
import Details from "@/Pages/Admin/Assessments/Details.vue";
import Form from "@/Views/Teacher/Views/Assessments/AssessmentForm.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { router, usePage } from "@inertiajs/vue3";
import EmptyView from "@/Views/EmptyView.vue";
import Pagination from "@/Components/Pagination.vue";
import Loading from "@/Components/Loading.vue";

const showModal = ref(false);
const isLoading = ref(false);

const mappedAssessments = computed(() => usePage().props.mapped_assessments);

const getMappedAssessments = () => {
    isLoading.value = true;
    router.visit("/admin/assessments", {
        only: ["mapped_assessments"],
        onFinish: () => {
            isLoading.value = false;
        },
    });
};

Echo.private("mass-assessment").listen(".mass-assessment", (e) => {
    if (e.type === "success") {
        getMappedAssessments();
    }
});
</script>
<style scoped></style>
