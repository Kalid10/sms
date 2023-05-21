<template>
    <div class="flex w-full flex-col space-y-3">
        <div class="flex w-full items-center">
            <StudentsScoreSection
                label="Exemplary"
                :icon="ArrowTrendingUpIcon"
                icon-color="text-emerald-500"
                :students="exemplaryStudents"
            />
            <div class="h-full w-[0.01rem] bg-gray-200"></div>
            <StudentsScoreSection
                label="UnderAchievers"
                :icon="ArrowSmallDownIcon"
                icon-color="text-red-600"
                :students="underAchievingStudents"
            />
        </div>
        <LinkCell
            href="/score"
            class="w-full pr-2 text-end"
            value="View All Results"
        />
    </div>
</template>

<script setup>
import StudentsScoreSection from "@/Views/Teacher/Assessments/Details/StudentsScoreSection.vue";
import {
    ArrowSmallDownIcon,
    ArrowTrendingUpIcon,
} from "@heroicons/vue/24/outline";
import LinkCell from "@/Components/LinkCell.vue";

const exemplaryStudents = [
    {
        id: 1,
        name: "Binyam Lemma",
        score: "20",
        total: "20",
    },
    {
        id: 2,
        name: "Kalid Abdu",
        score: "18",
        total: "20",
    },
    {
        id: 3,
        name: "Samuel Mesfin",
        score: "18",
        total: "20",
    },
    {
        id: 4,
        name: "Meron Tadesse",
        score: "17",
        total: "20",
    },
];

const underAchievingStudents = [
    {
        id: 5,
        name: "Tsega Molla",
        score: "3",
        total: "20",
    },
    {
        id: 6,
        name: "Fasil Tesfaye",
        score: "4",
        total: "20",
    },
    {
        id: 7,
        name: "Kebede Getachew",
        score: "7",
        total: "20",
    },
    {
        id: 8,
        name: "Ermias Belay",
        score: "10",
        total: "20",
    },
];
</script>
