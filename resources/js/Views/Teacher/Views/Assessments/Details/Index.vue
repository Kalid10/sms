<template>
    <div v-if="assessment" class="flex h-full w-full justify-center">
        <div
            class="flex w-full flex-col items-center space-y-10 lg:w-11/12 2xl:w-10/12"
        >
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
                v-if="assessment.status === 'draft' && isTeacher()"
                :assessment="assessment"
            />
            <Scheduled
                v-if="assessment.status === 'scheduled' && isTeacher()"
                :assessment="assessment"
            />
            <Published
                v-if="assessment.status === 'published' && isTeacher()"
                :assessment="assessment"
            />
            <Completed
                v-if="assessment.status === 'completed' && isTeacher()"
                :assessment="assessment"
            />
            <Marking
                v-if="assessment.status === 'marking' && isTeacher()"
                :assessment="assessment"
            />

            <!--            <DeleteAssessment :assessment="assessment" />-->

            <Modal v-model:view="showUpdateForm">
                <UpdateAssessmentForm
                    v-if="assessment.status !== 'completed'"
                    :assessment="assessment"
                    @success="getAssessments"
                />
            </Modal>
        </div>
    </div>
    <div
        v-else
        class="flex h-2/3 w-full items-center justify-center px-5 text-center text-4xl font-bold italic"
    >
        {{ $t("detailsIndex.selectAnyAssessment") }}
    </div>
</template>
<script setup>
import { computed, ref } from "vue";
import Draft from "@/Views/Teacher/Views/Assessments/Details/Draft.vue";
import UpdateAssessmentForm from "@/Views/Teacher/Views/Assessments/AssessmentForm.vue";
import AssessmentTypeDetailInfo from "@/Views/Teacher/Views/Assessments/Details/Views/AssessmenTypeDetailInfo.vue";
import Header from "@/Views/Teacher/Views/Assessments/Details/Views/Header.vue";
import moment from "moment";
import Modal from "@/Components/Modal.vue";
import Published from "@/Views/Teacher/Views/Assessments/Details/Published.vue";
import Completed from "@/Views/Teacher/Views/Assessments/Details/Completed.vue";
import Marking from "@/Views/Teacher/Views/Assessments/Details/Marking.vue";
import Scheduled from "@/Views/Teacher/Views/Assessments/Details/Scheduled.vue";
import { isTeacher } from "@/utils";
import { router } from "@inertiajs/vue3";

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
        " (" +
        props.assessment.batch_subject.batch.level.name +
        props.assessment.batch_subject.batch.section +
        ")"
);

function getAssessments() {
    router.get(
        "/teacher/assessments",
        {
            teacher_id: props.teacherId ?? null,
        },
        {
            preserveScroll: true,
            preserveState: true,
            only: ["assessments", "filters", "teacher"],
            onFinish: () => {
                showUpdateForm.value = false;
            },
        }
    );
}
</script>
<style scoped></style>
