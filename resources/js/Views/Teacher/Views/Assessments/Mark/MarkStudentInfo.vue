<template>
    <div
        v-if="student"
        class="flex w-full flex-col items-center justify-center space-y-8 pb-5"
    >
        <div class="flex w-full flex-col space-y-2">
            <div
                v-if="showInfo"
                class="flex bg-brand-50 px-4 py-2 text-center text-[0.6rem] font-light"
            >
                <InformationCircleIcon class="mr-2 w-7 text-brand-text-300" />
                <div>
                    These statistics are specifically related to the student's
                    performance in
                    <span class="font-semibold">{{
                        assessment.batch_subject.subject.full_name
                    }}</span
                    >. If you would like to see the student's overall
                    performance, you can click

                    <span
                        class="cursor-pointer font-medium underline-offset-2 hover:underline"
                        >here.</span
                    >

                    .
                </div>
                <div
                    class="h-full cursor-pointer hover:scale-125"
                    @click="showInfo = false"
                >
                    <XMarkIcon class="h-fit w-3 text-red-600" />
                </div>
            </div>
            <div
                class="-skew-x-3 cursor-pointer px-3 py-1 text-center text-4xl font-semibold underline-offset-2 hover:underline"
                @click="studentDetail(student.id)"
            >
                {{ student.user.name }}
                <span class="text-2xl">({{ student.user?.username }})</span>
            </div>
        </div>
        <div
            class="flex w-full items-center justify-between divide-x divide-gray-300 bg-brand-400 text-white"
        >
            <div class="w-3/12 text-center">
                <div class="text-2xl font-bold">
                    {{ 100 - student.absentee_percentage }}%
                </div>
                <div class="text-xs font-light">
                    {{ assessment.batch_subject.subject.short_name }} Attendance
                </div>
            </div>
            <div class="w-3/12 text-center">
                <div class="text-2xl font-bold">
                    <span v-if="student.total_batch_subject_grade">
                        {{ student.total_batch_subject_grade?.toFixed(1) }}
                    </span>
                    <span v-else> - </span>
                </div>
                <div class="text-xs font-light">
                    Total
                    {{ assessment.batch_subject.subject.short_name }} Result
                </div>
            </div>
            <div class="w-3/12 text-center">
                <div class="text-2xl font-bold">
                    <span v-if="student.batch_subject_rank">
                        {{ numberWithOrdinal(student.batch_subject_rank) }}
                    </span>
                    <span v-else> - </span>
                </div>
                <div class="text-[0.65rem] font-light">
                    {{ assessment.batch_subject.subject.short_name }} Rank
                </div>
            </div>
            <div class="w-3/12 py-2 text-center">
                <div class="text-2xl font-bold">
                    <span v-if="student.quarterly_grade">
                        {{ numberWithOrdinal(student.quarterly_grade.rank) }}
                    </span>
                    <span v-else> - </span>
                </div>
                <div class="text-[0.65rem] font-light">Quarter Rank</div>
            </div>
        </div>

        <AssessmentBreakDown />
    </div>
</template>
<script setup>
import { usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import { InformationCircleIcon, XMarkIcon } from "@heroicons/vue/24/outline";
import { Inertia } from "@inertiajs/inertia";
import { numberWithOrdinal } from "../../../../../utils";
import AssessmentBreakDown from "@/Views/Teacher/Views/Assessments/AssessmentBreakDown.vue";

const student = computed(() => {
    return usePage().props.student;
});

const showInfo = ref(true);
const assessment = computed(() => {
    return usePage().props.assessment;
});

const studentDetail = (studentId) => {
    Inertia.get(
        "/teacher/students/" +
            studentId +
            "?batch_subject_id=" +
            assessment.value.batch_subject.id,
        {},
        {
            preserveState: true,
        }
    );
};
</script>
<style scoped></style>
