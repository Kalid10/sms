<template>
    <div class="flex h-full cursor-pointer flex-col">
        <div
            v-if="!isLessonPlansEmpty"
            class="text-center font-medium 2xl:text-2xl"
        >
            Recent Lesson Plans
        </div>
        <div class="flex flex-col items-center justify-center">
            <div
                v-if="!isLessonPlansEmpty"
                class="flex flex-col items-center space-y-4 rounded-md py-4"
            >
                <Items />
                <LinkCell
                    class="flex w-full justify-end"
                    href="/teacher/lesson-plan"
                    value="View All Lesson Plans"
                />
            </div>
            <div
                v-else
                class="flex h-full flex-col items-center justify-center space-y-6"
            >
                <ExclamationTriangleIcon class="h-6 w-6 text-gray-500" />
                <div>No Lesson Plan Found!</div>
                <LinkCell
                    href="/teacher/lesson-plan"
                    value="View All LessonPlans"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";
import { ExclamationTriangleIcon } from "@heroicons/vue/24/outline";
import { computed } from "vue";
import Items from "@/Views/Teacher/Home/LessonPlan/Items.vue";
import LinkCell from "@/Components/LinkCell.vue";

const lessonPlans = usePage().props.teacher.lesson_plans;

const isLessonPlansEmpty = computed(() => {
    return lessonPlans.length === 0;
});
</script>

<style scoped></style>
