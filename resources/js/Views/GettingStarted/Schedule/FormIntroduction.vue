<template>

    <div class="flex h-full flex-col items-center justify-center gap-4">

        <BellAlertIcon class="stroke h-7 w-7 stroke-gray-500" />

        <h3 class="font-semibold">Create Your School's Weekly Period Schedule</h3>

        <p class="flex max-w-lg flex-col gap-3 p-8 text-center text-sm text-gray-500">
                <span>
                    This tool is designed to assist school
                    administrators in creating a weekly schedule for their school. By providing key
                    information such as the start time of the school day, the number of periods per day,
                    and the duration of each period, you can easily generate a well-structured timetable.
                </span>

            <span>
                    Additionally, you have the flexibility to include custom periods such as recess and lunch
                    breaks in your schedule. These breaks are crucial for students' well-being and playtime.
                </span>

            <span>
                    Let's get started! Simply enter the requested information, including the start time,
                    number of periods, duration, and any custom periods, and let the module assist you
                    in creating an organized and efficient weekly schedule for your school.
                </span>
        </p>

        <PrimaryButton @click="$emit('submit')">Get Started</PrimaryButton>

    </div>

</template>

<script setup>
import {BellAlertIcon} from "@heroicons/vue/24/outline/index.js";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const emits = defineEmits(['submit'])
</script>

<style scoped>

</style>
