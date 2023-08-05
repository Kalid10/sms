<template>
    <div
        class="flex min-h-screen w-full flex-col justify-between divide-x divide-gray-200 bg-brand-50 px-3 lg:flex-row lg:space-x-6 lg:px-0"
    >
        <!--        Left Side-->
        <div class="flex w-full flex-col space-y-5 py-5 pl-6 pr-5 lg:w-8/12">
            <Header
                :title="$t('teacherStudents.myStudents')"
                :batch-subject-rank="batchSubjectGrade?.rank"
                :total-batches-count="totalBatchesCount"
                :select-input-options="batchSubjectOptions"
                :selected-input="batchSubject.id"
                :show-current-class="true"
                @change="updateBatchInfo"
            />
            <div class="flex">
                <SecondaryButton
                    :title="$t('teacherStudents.filterStudents')"
                    class="!rounded-2xl bg-brand-450 text-white"
                    @click="showFilter = true"
                />
            </div>

            <StudentsTable
                :table-model-value="batchSubject.id"
                @search="updateBatchInfo"
                @click="fetchStudent"
            />
        </div>

        <div
            class="flex w-full flex-col space-y-10 bg-brand-50 py-5 lg:w-4/12 lg:pl-5"
        >
            <div
                class="flex w-full flex-col justify-evenly space-y-3 lg:flex-row lg:space-y-0"
            >
                <div
                    class="flex w-full flex-col justify-center space-y-4 rounded-lg bg-positive-100 py-5 text-center text-5xl font-bold text-white shadow-sm lg:w-5/12"
                >
                    <div>
                        {{ 100 - parseFloat(batch_absentees_percentage) }}%
                    </div>
                    <span class="text-xs font-light"
                        >{{ $t("teacherStudents.classAttendance") }}(attendance)
                    </span>
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
                        'bg-brand-400 text-white': !batchSubjectGrade?.conduct,
                    }"
                    class="flex w-full flex-col justify-center space-y-4 rounded-lg py-5 text-center text-5xl font-bold shadow-sm lg:w-5/12"
                >
                    <div>{{ batchSubjectGrade?.conduct ?? "-" }}</div>
                    <span class="text-xs font-light">{{
                        $t("teacherStudents.classConduct")
                    }}</span>
                </div>
            </div>
            <div class="w-11/12 rounded-lg bg-white py-2 shadow-sm">
                <StudentsList
                    progress-type="up"
                    :title="$t('teacherStudents.topStudents')"
                    :icon="ArrowTrendingUpIcon"
                    :students="topStudents"
                />
            </div>

            <div class="w-11/12 rounded-lg bg-white shadow-sm">
                <StudentsList
                    progress-type="down"
                    :title="$t('teacherStudents.studentsFallingBehind')"
                    :icon="ArrowTrendingDownIcon"
                    :students="bottomStudents"
                />
            </div>
        </div>
    </div>

    <Modal v-model:view="showFilter">
        <Filters @filter="applyFilters" />
    </Modal>
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
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Filters from "@/Views/Filters.vue";
import Modal from "@/Components/Modal.vue";

const showFilter = ref(false);

const students = computed(() => usePage().props.students);
const batchSubjectGrade = computed(() => usePage().props.batch_subject_grade);
const batchSubjects = usePage().props.batch_subjects;
const totalBatchesCount = ref(usePage().props.total_batches_count);
const topStudents = computed(() => usePage().props.top_students);
const bottomStudents = computed(() => usePage().props.bottom_students);
const batch_absentees_percentage = computed(
    () => usePage().props.batch_absentees_percentage
);

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

const schoolYears = computed(() => usePage().props.school_years);

function applyFilters(params) {
    params.teacher_id = usePage().props.teacher.id;

    router.get(
        isAdmin() ? "/admin/teachers/students" : "/teacher/students",
        params,
        {
            onSuccess: () => {
                showFilter.value = false;
            },
            preserveState: true,
        }
    );
}
</script>
<style scoped></style>
