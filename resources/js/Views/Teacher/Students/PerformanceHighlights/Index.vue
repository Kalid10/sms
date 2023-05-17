<template>
    <div class="flex w-full justify-between">
        <div class="flex h-56 w-5/12 flex-col space-y-3 py-2">
            <StudentsList
                progress-type="up"
                title="Best Progressing Students"
                :icon="ArrowTrendingUpIcon"
                :progressing-students="progressingStudents"
            />
        </div>
        <div class="flex h-56 w-5/12 flex-col space-y-3 py-2">
            <StudentsList
                progress-type="down"
                title="Students Falling Behind"
                :icon="ArrowTrendingDownIcon"
                :progressing-students="progressingStudents"
            />
        </div>
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
