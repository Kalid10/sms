<template>
    <div
        class="flex w-full flex-col items-center justify-between space-y-4 py-2"
    >
        <div
            class="flex w-full items-center justify-between rounded-md bg-brand-100 py-3.5 shadow-sm"
        >
            <ResultCard
                :label="$t('resultStatistics.averageResult')"
                :score="assessment.average_score"
            />
            <div class="h-9 w-[0.01rem] bg-brand-150"></div>
            <ResultCard
                :label="$t('resultStatistics.highestResult')"
                :score="assessment.highest_score"
            />
            <div class="h-9 w-[0.01rem] bg-brand-150"></div>
            <ResultCard
                :label="$t('resultStatistics.lowestResult')"
                :score="assessment.lowest_score"
            />
        </div>
        <AssessmentOutcomePercentages
            :passed-percent="assessment.passing_percentage"
        />
    </div>
</template>

<script setup>
import ResultCard from "@/Views/Teacher/Views/Assessments/Details/Views/ResultCard.vue";
import AssessmentOutcomePercentages from "@/Views/Teacher/Views/Assessments/Details/Views/AssessmentOutcomePercentages.vue";

defineProps({
    assessment: {
        type: Object,
        required: true,
    },
});
</script>
