<template>
    <div class="flex w-full flex-col space-y-3">
        <div class="flex w-full items-center">
            <StudentsScoreSection
                :label="$t('studentScoreList.exemplary')"
                :icon="ArrowTrendingUpIcon"
                icon-color="text-emerald-500"
                :students="exemplaryStudents"
                @student-clicked="
                    (student) => $emit('student-clicked', student)
                "
            />
            <div class="h-full w-[0.01rem] bg-brand-150"></div>
            <StudentsScoreSection
                :label="$t('studentScoreList.underAchievers')"
                :icon="ArrowTrendingDownIcon"
                icon-color="text-red-600"
                :students="underAchievingStudents"
                @student-clicked="
                    (student) => $emit('student-clicked', student)
                "
            />
        </div>
        <div class="flex w-full justify-center pt-4">
            <LinkCell
                v-if="view === 'detail'"
                :href="'/teacher/assessments/mark/' + assessment.id"
                class="w-fit rounded-lg bg-yellow-400 px-6 py-1 text-end font-medium no-underline"
                :value="$t('studentScoreList.viewAllResults')"
            />
        </div>
    </div>
</template>

<script setup>
import StudentsScoreSection from "@/Views/Teacher/Views/Assessments/Details/Views/StudentsScoreSection.vue";
import {
    ArrowTrendingDownIcon,
    ArrowTrendingUpIcon,
} from "@heroicons/vue/24/outline";
import LinkCell from "@/Components/LinkCell.vue";
import { watch } from "vue";

defineEmits(["student-clicked"]);
const props = defineProps({
    assessment: {
        type: Object,
        required: true,
    },
    view: {
        type: String,
        default: null,
    },
});

let exemplaryStudents = [];
let underAchievingStudents = [];

const mapStudentData = (students) => {
    if (!students) return [];
    const studentArray = Object.values(students);
    return studentArray.map((student) => ({
        id: student.student_id,
        name: student.student?.user?.name,
        score: student.point,
        total: props.assessment.maximum_point,
    }));
};

watch(
    () => props.assessment.top_students,
    (top_students) => {
        exemplaryStudents = mapStudentData(top_students);
    },
    { immediate: true }
);

watch(
    () => props.assessment.bottom_students,
    (bottom_students) => {
        underAchievingStudents = mapStudentData(bottom_students);
    },
    { immediate: true }
);
</script>
