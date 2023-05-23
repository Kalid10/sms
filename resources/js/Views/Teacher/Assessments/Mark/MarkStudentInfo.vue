<template>
    <div
        class="flex w-full flex-col items-center justify-center space-y-8 py-5"
    >
        <div
            v-if="student"
            class="-skew-x-3 px-3 py-1 text-center text-4xl font-semibold"
        >
            {{ student.user.name }}
        </div>
        <div
            class="flex w-full items-center justify-between divide-x divide-gray-300"
        >
            <div class="w-4/12 text-center">
                <div class="text-2xl font-bold">
                    {{ 100 - student.absentee_percentage }}%
                </div>
                <div class="text-xs font-light">A
            </div>
            <div class="w-4/12 text-center">
                <div class="text-2xl font-bold">90</div>
                <div class="text-xs font-light">Total Result</div>
            </div>
            <div class="w-4/12 text-center">
                <div class="text-2xl font-bold">B</div>
                <div class="text-xs font-light">Conduct</div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const student = computed(() => {
    return usePage().props.student;
});
</script>
<style scoped></style>
