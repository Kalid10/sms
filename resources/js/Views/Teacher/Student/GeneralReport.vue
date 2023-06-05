<template>
    <div class="flex w-full flex-col justify-between space-y-4">
        <div
            class="flex flex-col justify-center space-y-4 rounded-lg py-5 text-center shadow-sm"
            :class="{
                'bg-positive-100 text-white': grade?.grade_scale.label === 'A',
                'bg-yellow-400': grade?.grade_scale.label === 'B',
                'bg-yellow-100': grade?.grade_scale.label === 'C',
                'bg-negative-50': grade?.grade_scale.label === 'D',
                'bg-negative-100 text-white': grade?.grade_scale.label === 'F',
                'bg-white': !grade,
            }"
        >
            <div v-if="grade">
                <span class="text-4xl font-semibold">
                    {{ grade?.score }}
                    <span class="text-xl font-normal">{{
                        grade?.grade_scale.label
                    }}</span>
                </span>
            </div>
            <div v-else>
                <span class="text-4xl font-semibold"> - </span>
            </div>
            <span class="text-xs font-light">
                {{ batchSubject.subject.short_name }} GRADE
            </span>
        </div>

        <div
            class="flex flex-col justify-center space-y-4 rounded-lg bg-white py-5 text-center text-4xl font-bold shadow-sm"
        >
            <div>{{ attendancePercentage }}%</div>
            <span class="text-xs font-light">
                {{ batchSubject.subject.short_name }} ATTENDANCE
            </span>
        </div>
        <div
            class="flex flex-col justify-center space-y-4 rounded-lg bg-white py-5 text-center text-4xl font-bold shadow-sm"
        >
            <div>B</div>
            <span class="text-xs font-light"> CONDUCT </span>
        </div>
    </div>
</template>
<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const attendancePercentage = computed(
    () => usePage().props.attendance_percentage
);

const grade = computed(() => usePage().props.batch_subject_grade);

const batchSubject = computed(() => usePage().props.batch_subject);
</script>

<style scoped></style>
