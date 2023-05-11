<template>
    <div
        class="flex w-full flex-col items-center justify-between space-y-4 pb-3 pt-5 lg:w-5/12 lg:space-y-10 lg:py-0"
    >
        <div
            class="w-1/2 text-center text-xs font-light opacity-60 lg:text-base"
        >
            Last Assessment
        </div>
        <div class="font-medium">
            <span class="text-3xl font-bold lg:text-6xl xl:text-7xl">{{
                lastAssessment.maximum_point
            }}</span>
            Pts
        </div>
        <div class="text-xs font-medium lg:text-xl">
            {{ lastAssessment.assessment_type.name }}
        </div>
        <div class="text-sm lg:text-base">
            {{ lastAssessment.title }}
        </div>
        <div class="text-xs lg:text-base">
            Due On
            {{ moment(lastAssessment.due_date).format(" dddd MMMM D  YYYY") }}
        </div>
        <PrimaryButton class="w-11/12 bg-neutral-800"
            >View All Assessments
        </PrimaryButton>
    </div>
</template>

<script setup>
import moment from "moment/moment";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { usePage } from "@inertiajs/vue3";

const lastAssessment = usePage().props.last_assessment;
</script>

<style scoped></style>
