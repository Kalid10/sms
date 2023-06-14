<template>
    <GeneralInfo
        teacher-name="Bereket Gobeze"
        :count="assessment.total_students"
        :status="capitalize(assessment.status)"
    />

    <div class="flex w-full flex-col space-y-2">
        <ResultStatistics :assessment="assessment" />

        <StudentScoreList :assessment="assessment" view="detail" />
    </div>
</template>
<script setup>
import { capitalize } from "vue";

import GeneralInfo from "@/Views/Teacher/Assessments/Details/Views/Info.vue";
import StudentScoreList from "@/Views/Teacher/Assessments/Details/Views/StudentScoreList.vue";
import ResultStatistics from "@/Views/Teacher/Assessments/Details/Views/ResultStatistics.vue";

defineProps({
    assessment: {
        type: Object,
        required: true,
    },
});
</script>
<style scoped></style>
