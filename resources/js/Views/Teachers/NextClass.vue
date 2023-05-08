<template>
    <div
        class="flex h-full w-full flex-col items-center justify-evenly space-y-1 rounded-xl bg-black text-white"
    >
        <div
            v-if="nextClass"
            class="flex w-full justify-between text-center font-light"
        >
            <div class="w-1/2">Next Class</div>
            <div class="w-1/2">Last Assessment</div>
        </div>

        <div v-if="nextClass" class="flex w-full justify-evenly">
            <div
                class="flex h-full w-5/12 flex-col items-center justify-evenly space-y-5"
            >
                <span class="text-7xl font-bold"
                    >{{ nextClass.batch_subject.batch.level.name
                    }}{{ nextClass.batch_subject.batch.section }}</span
                >
                <span class="text-xl font-medium">{{
                    nextClass.batch_subject.subject.full_name
                }}</span>
                <span class=""
                    >{{ nextClass.school_period.name }}th Period
                    {{ moment(nextClass.date).fromNow() }}</span
                >

                <div
                    class="font-light hover:cursor-pointer hover:font-medium hover:underline"
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

            <div class="h-full w-0.5 bg-neutral-600"></div>
            <div
                class="flex w-5/12 flex-col items-center justify-between space-y-5"
            >
                <div class="font-medium">
                    <span class="text-7xl font-bold">{{
                        lastAssessment.maximum_point
                    }}</span>
                    Pts
                </div>
                <div class="text-xl font-medium">
                    {{ lastAssessment.assessment_type.name }}
                </div>
                <div>{{ lastAssessment.title }}</div>
                <div>
                    {{
                        moment(lastAssessment.due_date).format(
                            " dddd MMMM d  YYYY"
                        )
                    }}
                </div>
                <PrimaryButton class="w-11/12 bg-neutral-800"
                    >View All Assessments
                </PrimaryButton>
            </div>
        </div>

        <div
            v-else
            class="text-center text-xl font-light leading-relaxed lg:w-8/12"
        >
            No upcoming classes found! Please check your schedule or contact the
            <span
                class="cursor-pointer underline underline-offset-2 hover:font-medium"
                >admin</span
            >
            for assistance.
        </div>
    </div>
</template>

<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { usePage } from "@inertiajs/vue3";
import moment from "moment";

const nextClass = usePage().props.teacher.next_batch_session;
const lastAssessment = usePage().props.last_assessment;
</script>

<style scoped></style>
