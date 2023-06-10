<template>
    <div class="flex w-full flex-col items-center space-y-5 bg-white px-4 py-5">
        <div
            class="flex w-fit -skew-x-3 bg-yellow-400 px-2 text-xl font-semibold"
        >
            <ChartBarIcon class="mr-1 w-5 text-black" />

            <div>
                <span v-if="student">{{ student.user.name }}'s</span>
                Assessment BreakDown
            </div>
        </div>
        <div
            v-for="(item, index) in assessmentGrade"
            :key="index"
            class="flex w-full items-center justify-between space-x-2 py-3"
            :class="{
                'bg-gray-100': index % 2 === 0,
            }"
        >
            <div class="w-7/12 text-center text-xs font-medium">
                {{ item.assessment_type.name }}

                {{ item.assessment_type.percentage }}%
            </div>

            <div class="flex w-5/12 justify-center space-x-0.5 font-light">
                <span class="w-5/12 text-center text-sm">
                    {{ item.score?.toFixed(2) }}%
                </span>
                <span
                    class="flex h-5 w-5 items-center justify-center rounded-full text-center text-xs font-bold text-white"
                    :class="{
                        'bg-emerald-500': item.grade_scale.label === 'A',
                        'bg-emerald-400': item.grade_scale.label === 'B',
                        'bg-yellow-400': item.grade_scale.label === 'C',
                        'bg-yellow-300': item.grade_scale.label === 'D',
                        'bg-red-700': item.grade_scale.label === 'F',
                    }"
                >
                    {{ item.grade_scale.label }}
                </span>
            </div>
        </div>
        <div
            v-if="assessmentGrade.length === 0"
            class="w-9/12 px-4 text-xs font-light lg:text-xs"
        >
            At present,
            <span
                v-if="student"
                class="cursor-pointer font-medium underline-offset-2 hover:font-semibold hover:underline"
                >{{ student.user.name }}</span
            >
            has no associated grades. As grades are recorded and registered,
            they will become visible in this space, segregated based on the type
            of assessment.
        </div>
    </div>
</template>
<script setup>
import { ChartBarIcon } from "@heroicons/vue/24/solid";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const props = defineProps({
    assessmentGrade: {
        type: Array,
        default: null,
    },
    student: {
        type: Array,
        default: null,
    },
});
const assessmentGrade =
    props.assessmentGrade ??
    computed(() => usePage().props.student.assessment_quarter_grade);
</script>
<style scoped></style>
