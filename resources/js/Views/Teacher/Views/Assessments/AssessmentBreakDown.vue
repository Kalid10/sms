<template>
    <div
        class="flex w-full flex-col items-center space-y-5 rounded-lg bg-white px-4 py-5 shadow-sm"
        :class="!assessmentGrade?.length ? 'h-full' : 'h-fit'"
    >
        <div
            v-if="assessmentGrade?.length"
            class="flex w-fit px-2 text-xl font-semibold"
        >
            <ChartBarIcon class="mr-1 w-4 text-black" />

            <div>
                <span v-if="student">{{ student.user.name }}'s</span>
                Assessment BreakDown
            </div>
        </div>
        <div
            v-for="(item, index) in assessmentGrade"
            :key="index"
            class="flex w-full items-center justify-between space-x-2 rounded-lg py-3"
            :class="{
                'bg-gray-50': index % 2 === 1,
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
            v-if="!assessmentGrade?.length"
            class="flex h-full w-9/12 items-center justify-center text-xs font-light lg:text-xs"
        >
            <EmptyView :title="'No Grades Recorded!'" />
        </div>
    </div>
</template>
<script setup>
import { ChartBarIcon } from "@heroicons/vue/24/solid";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import EmptyView from "@/Views/EmptyView.vue";

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
