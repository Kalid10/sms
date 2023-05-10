<template>
    <div
        class="flex h-full w-full flex-col items-center justify-evenly space-y-4 py-3 lg:w-5/12 lg:space-y-10 lg:py-0"
    >
        <div
            class="w-1/2 text-center text-xs font-light opacity-60 lg:text-base"
        >
            Next Class
        </div>
        <span class="text-center text-3xl font-bold lg:text-6xl xl:text-7xl"
            >{{ nextClass.batch_subject.batch.level.name
            }}{{ nextClass.batch_subject.batch.section }}</span
        >
        <span class="text-xs font-medium lg:text-xl">{{
            nextClass.batch_subject.subject.full_name
        }}</span>
        <span class="text-xs lg:text-base"
            >{{ nextClass.school_period.name }}th Period
            {{ moment(nextClass.date).fromNow() }}</span
        >

        <div
            class="text-xs font-light hover:cursor-pointer hover:font-medium hover:underline lg:text-base"
        >
            <span v-if="nextClass.lesson_plan">
                Lesson Plan #{{ nextClass.lesson_plan_id }}</span
            >
            <span v-else> Add LessonPlan</span>
        </div>

        <PrimaryButton class="w-11/12 bg-neutral-800"
            >View Full Schedule
        </PrimaryButton>
    </div>
</template>
<script setup>
import moment from "moment/moment";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { usePage } from "@inertiajs/vue3";

const nextClass = usePage().props.teacher.next_batch_session;
</script>

<style scoped></style>
