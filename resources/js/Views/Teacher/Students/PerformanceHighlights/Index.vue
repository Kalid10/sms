<template>
    <div class="flex h-full w-full flex-col justify-evenly space-y-5">
        <div class="py-1 text-center text-2xl font-medium">
            Performance Highlight
        </div>
        <StudentsList
            progress-type="up"
            title="Best Progressing Students"
            :icon="ArrowTrendingUpIcon"
            :progressing-students="progressingStudents"
        />
        <StudentsList
            progress-type="down"
            title="Students Falling Behind"
            :icon="ArrowTrendingDownIcon"
            :progressing-students="progressingStudents"
        />
    </div>
</template>
<script setup>
import {
    ArrowTrendingDownIcon,
    ArrowTrendingUpIcon,
} from "@heroicons/vue/24/outline";
import StudentsList from "@/Views/Teacher/Students/PerformanceHighlights/StudentsList.vue";

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
