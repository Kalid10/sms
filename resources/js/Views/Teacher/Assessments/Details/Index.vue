<template>
    <div v-if="assessment" class="flex h-full w-full justify-center">
        <div class="flex w-full flex-col space-y-8 lg:w-10/12 2xl:w-9/12">
            <Header
                :title="title"
                class="w-full text-center"
                :formatted-date="
                    String(
                        moment(assessment.due_date).format('dddd MMMM DD YYYY')
                    )
                "
            />

            <AssessmentTypeDetailInfo
                :assessment="assessment"
                @update="showUpdateForm = true"
            />

            <Draft
                v-if="assessment.status === 'draft'"
                :assessment="assessment"
            />
            <Published
                v-if="assessment.status === 'published'"
                :assessment="assessment"
            />
            <Completed
                v-if="assessment.status === 'completed'"
                :assessment="assessment"
            />
            <Marking
                v-if="assessment.status === 'marking'"
                :assessment="assessment"
            />

            <Modal v-model:view="showUpdateForm">
                <UpdateAssessmentForm
                    v-if="assessment.status !== 'completed'"
                    :assessment="assessment"
                />
            </Modal>
        </div>
    </div>
    <div
        v-else
        class="flex h-2/3 w-full items-center justify-center px-2 text-center text-4xl font-bold italic"
    >
        Select any assessment for a detailed view!
    </div>
</template>
<script setup>
import { computed, ref } from "vue";
import { router } from "@inertiajs/vue3";
import Draft from "@/Views/Teacher/Assessments/Details/Draft.vue";
import UpdateAssessmentForm from "@/Views/Teacher/Assessments/AssessmentForm.vue";
import AssessmentTypeDetailInfo from "@/Views/Teacher/Assessments/Details/Views/AssessmenTypeDetailInfo.vue";
import Header from "@/Views/Teacher/Assessments/Details/Views/Header.vue";
import moment from "moment";
import Modal from "@/Components/Modal.vue";
import Published from "@/Views/Teacher/Assessments/Details/Published.vue";
import Completed from "@/Views/Teacher/Assessments/Details/Completed.vue";
import Marking from "@/Views/Teacher/Assessments/Details/Marking.vue";

const props = defineProps({
    assessment: {
        type: Object,
        required: true,
    },
});
const showUpdateForm = ref(false);

const title = computed(
    () =>
        props.assessment.title +
        " " +
        props.assessment.batch_subject.batch.level.name +
        props.assessment.batch_subject.batch.section
);

function startMarking() {
    router.get("/teacher/assessments/mark/" + props.assessment.id, {
        student_id: props.assessment.id,
    });
}
</script>
<style scoped></style>
