<template>
    <div
        class="flex h-fit w-full flex-col items-center space-y-2 rounded-lg bg-white p-3 shadow-sm"
    >
        <div class="w-full pb-1 text-start text-2xl font-semibold">
            Class Performance
        </div>
        <div class="w-full pr-5">
            <BatchPerformance />
        </div>
        <div
            class="flex w-full items-center justify-between divide-x divide-zinc-300 pt-5"
        >
            <div class="w-1/2">
                <StudentsList
                    progress-type="up"
                    title="Best Progressing Students"
                    :icon="ArrowTrendingUpIcon"
                    :progressing-students="progressingStudents"
                />
            </div>

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
import StudentsList from "@/Views/Teacher/Batches/PerformanceHighlights/StudentsList.vue";
import BatchPerformance from "@/Views/Teacher/Batches/BatchPerformance/Index.vue";

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
