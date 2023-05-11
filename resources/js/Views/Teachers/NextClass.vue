<template>
    <div
        class="flex h-full w-full flex-col items-center justify-evenly space-y-1 rounded-xl bg-black text-white"
    >
        <div
            v-if="nextClass"
            class="flex w-full flex-col justify-evenly divide-y-2 divide-neutral-800 py-2 lg:flex-row lg:divide-y-0 lg:py-0"
        >
            <div
                class="flex h-full w-full flex-col items-center justify-evenly space-y-4 py-3 lg:w-5/12 lg:space-y-5 lg:py-0"
            >
                <div
                    class="w-1/2 text-center text-sm font-light opacity-60 lg:text-base"
                >
                    Next Class
                </div>
                <span class="text-4xl font-bold lg:text-7xl"
                    >{{ nextClass.batch_subject.batch.level.name
                    }}{{ nextClass.batch_subject.batch.section }}</span
                >
                <span class="text-sm font-medium lg:text-xl">{{
                    nextClass.batch_subject.subject.full_name
                }}</span>
                <span class="text-sm lg:text-base"
                    >{{ nextClass.school_period.name }}th Period
                    {{ moment(nextClass.date).fromNow() }}</span
                >

                <div
                    class="text-sm font-light hover:cursor-pointer hover:font-medium hover:underline lg:text-base"
                >
                    <span v-if="nextClass.lesson_plan">
                        Lesson Plan #{{ nextClass.lesson_plan_id }}</span
                    >
                    <span v-else> Add LessonPlan</span>
                </div>

                <PrimaryButton class="w-lg:w-11/12 bg-neutral-800"
                    >View Full Schedule
                </PrimaryButton>
            </div>

            <div
                v-if="lastAssessment"
                class="hidden h-full w-0.5 bg-neutral-600 lg:inline-block"
            ></div>

            <div
                v-if="lastAssessment"
                class="flex w-full flex-col items-center justify-between space-y-4 py-3 lg:w-5/12 lg:space-y-5 lg:py-0"
            >
                <div
                    class="w-1/2 text-center text-sm font-light opacity-60 lg:text-base"
                >
                    Last Assessment
                </div>
                <div class="font-medium">
                    <span class="text-4xl font-bold lg:text-7xl">{{
                        lastAssessment.maximum_point
                    }}</span>
                    Pts
                </div>
                <div class="text-sm font-medium lg:text-xl">
                    {{ lastAssessment.assessment_type.name }}
                </div>
                <div class="text-sm lg:text-base">
                    {{ lastAssessment.title }}
                </div>
                <div class="text-sm lg:text-base">
                    Due @
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
            class="flex h-32 w-11/12 flex-col justify-center text-center text-sm font-light leading-relaxed lg:w-10/12 lg:text-xl"
        >
            <div>
                No upcoming classes found! Please check your schedule or contact
                the
                <span
                    class="cursor-pointer underline underline-offset-2 hover:font-medium"
                    >admin</span
                >
                for assistance.
            </div>
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
