<template>
    <div
        class="flex min-h-screen w-full justify-between space-x-6 divide-x divide-gray-200 bg-gray-50"
    >
        <!--        Left Side-->
        <div class="flex w-8/12 flex-col space-y-5 py-5 pl-6 pr-5">
            <Header
                title="My Students"
                :batch-subject-rank="batchSubjectGrade?.rank"
                :total-batches-count="totalBatchesCount"
                :select-input-options="batchSubjectOptions"
                :selected-input="batchSubject.id"
                @change="updateBatchInfo"
            />
            <StudentsTable
                :table-model-value="batchSubject.id"
                @search="updateBatchInfo"
                @click="fetchStudent"
            />
        </div>

        <div class="flex w-4/12 flex-col space-y-10 bg-gray-50 py-5 pl-5">
            <div class="flex w-full justify-evenly">
                <div
                    class="flex w-5/12 flex-col justify-center space-y-4 rounded-lg bg-positive-100 py-5 text-center text-5xl font-bold text-white shadow-sm"
                >
                    <div>99%</div>
                    <span class="text-xs font-light">CLASS ATTENDANCE </span>
                </div>
                <div
                    :class="{
                        'bg-positive-100 text-white':
                            batchSubjectGrade?.conduct === 'A',
                        'bg-yellow-400': batchSubjectGrade?.conduct === 'B',
                        'bg-amber-300': batchSubjectGrade?.conduct === 'C',
                        'bg-red-500 text-white':
                            batchSubjectGrade?.conduct === 'D',
                        'bg-negative-100 text-white':
                            batchSubjectGrade?.conduct === 'F',
                        'bg-zinc-700 text-white': !batchSubjectGrade?.conduct,
                    }"
                    class="flex w-5/12 flex-col justify-center space-y-4 rounded-lg py-5 text-center text-5xl font-bold shadow-sm"
                >
                    <div>{{ batchSubjectGrade?.conduct ?? "-" }}</div>
                    <span class="text-xs font-light">CLASS CONDUCT </span>
                </div>
            </div>
            <div class="w-11/12 rounded-lg bg-white py-2 shadow-sm">
                <StudentsList
                    progress-type="up"
                    title="Top Students"
                    :icon="ArrowTrendingUpIcon"
                    :students="topStudents"
                />
            </div>

            <div class="w-11/12 rounded-lg bg-white shadow-sm">
                <StudentsList
                    progress-type="down"
                    title="Students Falling Behind"
                    :icon="ArrowTrendingDownIcon"
                    :students="bottomStudents"
                />
            </div>
        </div>
    </div>
</template>
<script setup>
import { computed, ref } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import {
    ArrowTrendingDownIcon,
    ArrowTrendingUpIcon,
} from "@heroicons/vue/24/outline";
import StudentsList from "@/Views/Teacher/Views/Batches/PerformanceHighlights/StudentsList.vue";
import Header from "@/Views/Teacher/Views/Header.vue";
import StudentsTable from "@/Views/Teacher/Views/StudentsTable.vue";
import { isAdmin } from "@/utils";

const students = computed(() => usePage().props.students);
const batchSubjectGrade = computed(() => usePage().props.batch_subject_grade);
const batchSubjects = usePage().props.batch_subjects;
const totalBatchesCount = ref(usePage().props.total_batches_count);
const topStudents = computed(() => usePage().props.top_students);
const bottomStudents = computed(() => usePage().props.bottom_students);

const batchSubject = computed(() => {
    return usePage().props.batch_subject;
});

const selectedBatchSubject = ref(batchSubject.value.id);

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

const updateBatchInfo = (batchSubjectId, search) => {
    if (batchSubjectId !== null) selectedBatchSubject.value = batchSubjectId;
    router.visit(
        isAdmin()
            ? "/admin/teachers/students?teacher_id=" +
                  usePage().props.teacher.id +
                  "&batch_subject_id=" +
                  selectedBatchSubject.value +
                  "&search=" +
                  search
            : "/teacher/students?batch_subject_id=" +
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
