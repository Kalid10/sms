<template>
    <div class="flex min-w-full flex-col items-center space-y-2 py-4 text-center">
        <div class="flex h-96 w-3/5 flex-col items-center justify-evenly rounded-xl bg-black py-5 text-white">
            <span class="text-xl font-light">Next Class</span>
            <span class="text-7xl font-bold">{{
                    nextClass.batch_subject.batch.level.name
                }}{{ nextClass.batch_subject.batch.section }}</span>
            <span class="text-xl font-medium">{{ nextClass.batch_subject.subject.full_name }}</span>
            <span class="text-xl font-light">{{
                    nextClass.school_period.name
                }}th Period {{ moment(nextClass.date).fromNow() }}</span>

            <div class="font-light hover:cursor-pointer hover:font-medium hover:underline">
               <span v-if="nextClass.lesson_plan"> Lesson
                Plan #{{ nextClass.lesson_plan_id }}</span>
                <span v-else> Add LessonPlan</span>
            </div>
            <PrimaryButton class="w-2/3 bg-neutral-800">View Full Schedule</PrimaryButton>
        </div>
    </div>

</template>

<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {usePage} from "@inertiajs/vue3";
import moment from "moment";

const nextClass = usePage().props.teacher.next_batch_session;
</script>

<style scoped>

</style>
