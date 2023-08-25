<template>
    <div
        v-if="selectedAssessment"
        class="flex w-11/12 flex-col items-center justify-center space-y-4 rounded-md p-3"
    >
        <Header
            :title="selectedAssessment.title"
            class="w-full text-center"
            :formatted-date="
                String(
                    moment(selectedAssessment.due_date).format(
                        'dddd MMMM DD YYYY'
                    )
                )
            "
        />
        <p class="rounded px-2" :class="statusClass">
            {{ selectedAssessment.status }}
        </p>
        <div class="flex w-full flex-col justify-between lg:flex-row">
            <div class="mt-6 w-full">
                <AssessmentTypeDetails
                    :assessment="selectedAssessment"
                    @update="showUpdateForm = true"
                />
            </div>
        </div>

        <div class="flex w-full">
            <Details :assessment="selectedAssessment" />
        </div>
    </div>
    <Modal v-model:view="showUpdateForm">
        <UpdateForm
            v-if="selectedAssessment.status !== 'completed'"
            :assessment="selectedAssessment"
            @success="showUpdateForm = false"
        />
    </Modal>
</template>
<script setup>
import { computed, ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import Header from "@/Views/Teacher/Views/Assessments/Details/Views/Header.vue";
import moment from "moment/moment";
import Details from "@/Views/Admin/Assessments/Details.vue";
import Modal from "@/Components/Modal.vue";
import UpdateForm from "@/Views/Teacher/Views/Assessments/AssessmentForm.vue";
import AssessmentTypeDetails from "@/Views/Admin/Assessments/AssessmentTypeDetails.vue";

const showUpdateForm = ref(false);

const selectedAssessment = computed(() => usePage().props.assessment);

const statusClass = computed(() => {
    switch (selectedAssessment.value.status) {
        case "marking":
            return "bg-emerald-500";
        case "completed":
            return "bg-yellow-400";
        case "published":
            return "bg-emerald-400 text-black";
        default:
            return "bg-brand-300";
    }
});
</script>
<style scoped></style>
