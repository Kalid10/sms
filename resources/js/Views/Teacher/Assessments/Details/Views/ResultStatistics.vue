<template>
    <div
        class="flex w-full flex-col items-center justify-between space-y-4 py-2"
    >
        <div
            class="flex w-full items-center justify-between rounded-md bg-gray-100 py-3.5 shadow-sm"
        >
            <ResultCard
                label="Average Result"
                :score="assessment.average_score"
            />
            <div class="h-9 w-[0.01rem] bg-gray-200"></div>
            <ResultCard
                label="Highest Result"
                :score="assessment.highest_score"
            />
            <div class="h-9 w-[0.01rem] bg-gray-200"></div>
            <ResultCard
                label="Lowest Result"
                :score="assessment.lowest_score"
            />
        </div>
        <AssessmentOutcomePercentages
            :passed-percent="assessment.passing_percentage"
            failed-percent="27"
        />
    </div>
</template>

<script setup>
import ResultCard from "@/Views/Teacher/Assessments/Details/Views/ResultCard.vue";
import AssessmentOutcomePercentages from "@/Views/Teacher/Assessments/Details/Views/AssessmentOutcomePercentages.vue";

defineProps({
    assessment: {
        type: Object,
        required: true,
    },
});
</script>
