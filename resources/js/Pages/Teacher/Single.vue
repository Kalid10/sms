<template>
    <div
        class="flex w-full flex-col space-y-3 px-5 py-3 lg:space-y-10 lg:px-12"
    >
        <!-- Welcome header-->
        <div
            class="flex items-center rounded-lg py-5 text-xl font-light lg:py-7 lg:pl-0 lg:text-4xl"
        >
            Welcome back,

            <span class="ml-1 font-semibold lg:ml-2"
                >{{ teacher.user.name }}!</span
            >
        </div>

        <!-- Assessments and Next class-->
        <div
            class="flex w-full flex-col justify-between space-y-5 lg:flex-row lg:space-y-0"
        >
            <div class="w-full lg:w-6/12">
                <Assessments class="" />
            </div>
            <div class="hidden lg:inline-block lg:w-5/12">
                <NextClass />
            </div>
        </div>

        <!-- Students, LessonPlan, Feedback, Grades and SchoolSchedule-->
        <div
            class="flex h-full w-full flex-col justify-between space-y-2 pt-8 lg:flex-row"
        >
            <Students class="w-full lg:w-3/12" />
            <LessonPlans class="w-full lg:w-4/12" />

            <div
                class="w-ful flex h-full flex-col justify-between lg:w-4/12"
            >
                <div class="w-full">
                    <Grades />
                </div>

                <div
                    class="mt-5 flex w-full flex-col justify-between space-y-2 lg:flex-row lg:space-y-0"
                >
                    <div class="w-full lg:w-6/12">
                        <div class="w-full font-medium lg:text-center">
                            Feedbacks
                        </div>
                        <Feedbacks />
                    </div>
                    <div
                        class="flex w-full flex-col items-center lg:w-5/12"
                    >
                        <div
                            class="mb-4 w-full font-medium lg:text-center"
                        >
                            School Schedule
                        </div>
                        <SchoolSchedule />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";
import Assessments from "@/Views/Teachers/Assessments.vue";
import NextClass from "@/Views/Teachers/NextClass.vue";
import Students from "@/Views/Teachers/Students.vue";
import LessonPlans from "@/Views/Teachers/LessonPlans.vue";
import Grades from "@/Views/Teachers/Grades.vue";
import SchoolSchedule from "@/Views/Teachers/SchoolSchedule.vue";
import Feedbacks from "@/Views/Teachers/Feedbacks.vue";
const teacher = usePage().props.teacher;

</script>

<style scoped></style>
