<template>
    <div
        v-if="assessment"
        class="flex h-full w-full flex-col items-center space-y-8"
    >
        <Header
            :title="title"
            class="w-full text-start"
            :formatted-date="
                String(moment(assessment.due_date).format('dddd MMMM DD YYYY'))
            "
        />
        <GeneralInfo
            v-if="
                assessment.status === 'completed' ||
                assessment.status === 'published'
            "
            teacher-name="Bereket Gobeze"
            :count="assessment.total_students"
            :status="capitalize(assessment.status)"
        />

        <AssessmentTypeDetailInfo
            :assessment="assessment"
            @update="showUpdateForm = true"
        />

        <Draft v-if="assessment.status === 'draft'" :assessment="assessment" />

        <div
            v-if="assessment.status === 'completed'"
            class="flex w-full flex-col space-y-2"
        >
            <ResultStatistics :assessment="assessment" />

            <StudentScoreList :assessment="assessment" />
        </div>

        <div
            v-if="
                assessment.status === 'published' ||
                assessment.status === 'marking'
            "
            class="flex w-full items-center justify-center"
        >
            <SecondaryButton
                :title="
                    assessment.status === 'marking'
                        ? 'CONTINUE MARKING'
                        : 'START MARKING'
                "
                class="w-44 rounded-xl bg-emerald-400 font-semibold"
                @click="startMarking"
            />
        </div>

        <Modal v-model:view="showUpdateForm">
            <UpdateAssessmentForm
                v-if="assessment.status !== 'completed'"
                :assessment="assessment"
            />
        </Modal>
    </div>
    <div
        v-else
        class="flex h-72 w-full items-center justify-center text-xs font-light"
    >
        Click on a ny assessment to check details
    </div>
</template>
<script setup>
import ResultStatistics from "@/Views/Teacher/Assessments/Details/ResultStatistics.vue";
import StudentScoreList from "@/Views/Teacher/Assessments/Details/StudentScoreList.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { capitalize, computed, ref } from "vue";
import { router } from "@inertiajs/vue3";
import Draft from "@/Views/Teacher/Assessments/Details/Draft/Index.vue";
import UpdateAssessmentForm from "@/Views/Teacher/Assessments/AssessmentForm.vue";
import AssessmentTypeDetailInfo from "@/Views/Teacher/Assessments/Details/AssessmenTypeDetailInfo.vue";
import Header from "@/Views/Teacher/Assessments/Details/Header.vue";
import GeneralInfo from "@/Views/Teacher/Assessments/Details/Info.vue";
import moment from "moment";
import Modal from "@/Components/Modal.vue";

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
