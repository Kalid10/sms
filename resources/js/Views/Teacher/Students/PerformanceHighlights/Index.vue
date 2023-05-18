<template>
    <div class="flex h-fit w-full flex-col items-center space-y-2">
        <div class="w-full text-start text-xl font-semibold">
            Class Performance
        </div>
        <div class="w-full pr-5">
            <BatchPerformance />
        </div>
        <div class="flex w-full items-center justify-between pt-3">
            <div class="w-1/2">
                <StudentsList
                    progress-type="up"
                    title="Best Progressing Students"
                    :icon="ArrowTrendingUpIcon"
                    :progressing-students="progressingStudents"
                />
            </div>

            <div class="h-full w-[0.01rem] bg-neutral-200"></div>
            <div class="w-1/2">
                <StudentsList
                    progress-type="down"
                    title="Students Falling Behind"
                    :icon="ArrowTrendingDownIcon"
                    :progressing-students="progressingStudents"
                />
            </div>
        </div>
    </div>
</template>
<script setup>
import {
    ArrowTrendingDownIcon,
    ArrowTrendingUpIcon,
} from "@heroicons/vue/24/outline";
import StudentsList from "@/Views/Teacher/Students/PerformanceHighlights/StudentsList.vue";
import BatchPerformance from "@/Views/Teacher/Students/BatchPerformance/Index.vue";

const progressingStudents = [
    {
        name: "Kalid Abdu",
        progress: 80,
        attendance: 99,
    },
    {
        name: "Biniyam Lemma",
        progress: 85,
        attendance: 100,
    },
    {
        name: "Yoseph Seboka",
        progress: 85,
        attendance: 98,
    },
];
</script>

<style scoped></style>
