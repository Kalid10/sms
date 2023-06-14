<template>
    <div class="flex w-full flex-col space-y-2">
        <div class="flex w-full justify-between space-x-6 bg-gray-50">
            <!--        Left Side-->
            <div class="flex w-8/12 flex-col space-y-2 py-5 pl-5">
                <Header
                    title="My Classes"
                    :select-input-options="batchSubjectOptions"
                    :selected-input="batchSubject.id"
                    @change="updateBatchInfo"
                />

                <div class="pt-4">
                    <PerformanceHighlight />
                </div>
                <StudentsTable
                    :title="tableTitle"
                    :table-model-value="batchSubject.id"
                    @search="updateBatchInfo"
                    @click="fetchStudent"
                />
            </div>

            <!--        Right side-->
            <div
                class="flex w-4/12 flex-col items-center space-y-6 border-l bg-gray-50 px-3 py-5 pl-5"
            >
                <div>
                    <CurrentClass view="absentee" />
                </div>
                <div class="w-full rounded-lg bg-white p-2 shadow-sm">
                    <Assessment
                        class=""
                        title="Recent Assessments"
                        :assessments="assessments"
                        view="class"
                    />
                </div>
                <div class="rounded-lg bg-white px-3 pt-2 shadow-sm">
                    <LessonPlan
                        title="Recent LessonPlans"
                        :props-lesson-plans="lessonPlans"
                        view="class"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import PerformanceHighlight from "@/Views/Teacher/Views/Batches/PerformanceHighlights/Index.vue";
import { router, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import LessonPlan from "@/Views/Teacher/Views/Home/LessonPlans.vue";
import Assessment from "@/Views/Teacher/Views/Home/Assessments.vue";
import Header from "@/Views/Teacher/Views/Header.vue";
import CurrentClass from "@/Views/Teacher/Views/Batches/CurrentClass.vue";
import StudentsTable from "@/Views/Teacher/Views/StudentsTable.vue";

const schedule = usePage().props.schedule;
const batchSubjects = usePage().props.batch_subjects;

const students = computed(() => {
    return usePage().props.students;
});

const lessonPlans = computed(() => {
    return usePage().props.lesson_plans;
});

const assessments = computed(() => {
    return usePage().props.assessments;
});
const batchSubject = computed(() => {
    return usePage().props.batch_subject;
});

const batchSubjectOptions = computed(() => {
    return batchSubjects.map((batchSubject) => {
        return {
            value: batchSubject.id,
            label:
                batchSubject.batch.level.name +
                " " +
                batchSubject.batch.section +
                " " +
                batchSubject.subject.full_name,
        };
    });
});

const tableTitle = computed(() => {
    return `${batchSubject.value.batch.level.name} ${batchSubject.value.batch.section} - ${batchSubject.value.subject.full_name} Students List`;
});

const selectedBatchSubject = ref(batchSubject.value.id);
const updateBatchInfo = (batchSubjectId, search) => {
    if (batchSubjectId !== null) selectedBatchSubject.value = batchSubjectId;
    router.visit(
        "/teacher/class?batch_subject_id=" +
            selectedBatchSubject.value +
            "&search=" +
            search,
        {
            preserveState: true,
        }
    );
};

function fetchStudent(studentId) {
    router.get(
        "/teacher/students/" +
            studentId +
            "?batch_subject_id=" +
            selectedBatchSubject.value
    );
}
</script>

<style scoped></style>
