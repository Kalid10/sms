<template>
    <div
        v-if="student"
        class="flex w-full flex-col items-center justify-center space-y-8 pb-5"
    >
        <div class="flex w-full flex-col space-y-2">
            <div
                v-if="showInfo"
                class="flex bg-gray-50 px-4 py-2 text-center text-[0.6rem] font-light"
            >
                <InformationCircleIcon class="mr-2 w-7 text-gray-500" />
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
            <div class="-skew-x-3 px-3 py-1 text-center text-4xl font-semibold">
                {{ student.user.name }}
            </div>
        </div>
        <div
            class="flex w-full items-center justify-between divide-x divide-gray-300"
        >
            <div class="w-4/12 text-center">
                <div class="text-2xl font-bold">
                    {{ 100 - student.absentee_percentage }}%
                </div>
                <div class="text-xs font-light">Attendance</div>
            </div>
            <div class="w-4/12 text-center">
                <div class="text-2xl font-bold">90</div>
                <div class="text-xs font-light">Total Result</div>
            </div>
            <div class="w-4/12 text-center">
                <div class="text-2xl font-bold">27</div>
                <div class="text-[0.65rem] font-light">Rank</div>
            </div>
        </div>

        <div
            class="flex w-full flex-col items-center space-y-4 rounded-md px-2 py-5"
        >
            <div
                class="flex w-fit -skew-x-3 bg-yellow-400 px-2 text-xl font-semibold"
            >
                <ChartBarIcon class="mr-1 w-5 text-black" />
                Assessment BreakDown
            </div>
            <div
                v-for="(item, index) in student.assessment_quarter_grade"
                :key="index"
                class="flex w-full items-center justify-between space-x-2 rounded-md py-3"
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
                        {{ item.score.toFixed(2) }}%
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
                v-if="student.assessment_quarter_grade.length === 0"
                class="px-4 text-xs font-light lg:text-xs"
            >
                At present,
                <span
                    class="cursor-pointer font-medium underline-offset-2 hover:font-semibold hover:underline"
                    >{{ student.user.name }}</span
                >
                has no associated grades. As grades are recorded and registered,
                they will become visible in this space, segregated based on the
                type of assessment.
            </div>
        </div>
    </div>
</template>
<script setup>
import { usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import { ChartBarIcon } from "@heroicons/vue/24/solid";
import { InformationCircleIcon, XMarkIcon } from "@heroicons/vue/24/outline";

const student = computed(() => {
    return usePage().props.student;
});

const showInfo = ref(true);
const assessment = computed(() => {
    return usePage().props.assessment;
});
</script>
<style scoped></style>
