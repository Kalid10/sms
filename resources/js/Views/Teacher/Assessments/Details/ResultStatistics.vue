<template>
    <div class="flex w-full flex-col items-center justify-between space-y-2">
        <div
            class="flex w-full items-center justify-between rounded-md bg-gray-100 py-3.5 shadow-sm"
        >
            <ResultCard label="Average Result" score="13.5" total="20" />
            <div class="h-9 w-[0.01rem] bg-gray-200"></div>
            <ResultCard label="Highest Result" score="20" total="20" />
            <div class="h-9 w-[0.01rem] bg-gray-200"></div>
            <ResultCard label="Lowest Result" score="3.5" total="20" />
        </div>
        <AssessmentOutcomePercentages passed-percent="73" failed-percent="27" />
    </div>
</template>

<script setup>
import ResultCard from "@/Views/Teacher/Assessments/Details/ResultCard.vue";
import AssessmentOutcomePercentages from "@/Views/Teacher/Assessments/Details/AssessmentOutcomePercentages.vue";
</script>
