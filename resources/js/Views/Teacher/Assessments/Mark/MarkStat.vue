<template>
    <div class="flex w-full flex-col items-center space-y-8 py-2">
        <div class="flex w-full items-center justify-between">
            <div
                class="w-6/12 rounded-r py-1 text-center text-5xl font-semibold"
            >
                {{ assessment.students.length
                }}<span class="pl-0.5 text-xs font-light">Students</span>
            </div>
            <div class="w-6/12 py-1 text-center text-5xl font-semibold">
                {{ assessment.maximum_point
                }}<span class="pl-0.5 text-xs font-light">Max Points</span>
            </div>
        </div>

        <div
            class="flex w-full justify-evenly divide-x divide-gray-200 rounded-b-md"
        >
            <div class="w-6/12 text-center">
                <div
                    class="mb-2 flex items-center justify-center space-x-1 font-semibold italic"
                >
                    <ArrowTrendingUpIcon class="w-4 text-positive-100" />
                    <div>Highest Score</div>
                </div>
                <div class="mt-1 cursor-pointer text-xs">
                    <span
                        class="underline-offset-2 hover:underline"
                        @click="
                            $emit('updateStudent', highestScoringStudent.id)
                        "
                    >
                        {{ highestScoringStudent.name }}
                    </span>
                    -
                    <span class="underline-none font-semibold">{{
                        highestScoringStudent.point
                    }}</span>
                </div>
            </div>
            <div class="w-6/12 text-center">
                <div
                    class="mb-2 flex items-center justify-center space-x-0.5 font-semibold italic"
                >
                    <ArrowSmallDownIcon class="w-4 text-negative-100" />
                    <div>Lowest Score</div>
                </div>
                <div class="mt-1 cursor-pointer text-xs">
                    <span
                        class="underline-offset-2 hover:underline"
                        @click="$emit('updateStudent', lowestScoringStudent.id)"
                        >{{ lowestScoringStudent.name }}</span
                    >
                    -
                    <span class="underline-none font-semibold">{{
                        lowestScoringStudent.point
                    }}</span>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import {
    ArrowSmallDownIcon,
    ArrowTrendingUpIcon,
} from "@heroicons/vue/24/solid";
import { usePage } from "@inertiajs/vue3";
import { ref, watch } from "vue";

const assessment = usePage().props.assessment;
const props = defineProps({
    points: {
        type: Array,
        required: true,
    },
});

defineEmits(["updateStudent"]);
const highestScoringStudent = ref({});
const lowestScoringStudent = ref({});
const studentMap = new Map();
assessment.students.forEach((s) => studentMap.set(s.student.id, s));

watch(
    () => props.points,
    (newPoints) => {
        let highestScoringStudentPoint = -Infinity;
        let lowestScoringStudentPoint = Infinity;
        let highestScoringStudentTemp = {};
        let lowestScoringStudentTemp = {};

        for (let p of newPoints) {
            const currentStudent = studentMap.get(p.student_id);
            if (p.point === null) continue;
            if (+p.point > highestScoringStudentPoint) {
                highestScoringStudentPoint = +p.point;
                highestScoringStudentTemp = {
                    name: currentStudent.student.user.name,
                    id: currentStudent.student.id,
                    point: p.point,
                };
            }
            if (+p.point < lowestScoringStudentPoint) {
                lowestScoringStudentPoint = +p.point;
                lowestScoringStudentTemp = {
                    name: currentStudent.student.user.name,
                    id: currentStudent.student.id,
                    point: p.point,
                };
            }
        }

        highestScoringStudent.value = highestScoringStudentTemp;
        lowestScoringStudent.value = lowestScoringStudentTemp;
    },
    { deep: true }
);
</script>

<style scoped></style>
