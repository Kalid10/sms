<template>
    <div class="flex w-full flex-col space-y-8">
        <Header
            title="Test 1 Biology Grade 11A"
            :formatted-date="
                String(moment('05/10/2023').format('dddd MMMM DD YYYY'))
            "
        />

        <GeneralInfo
            teacher-name="Bereket Gobeze"
            count="32"
            status="Marking"
        />

        <ResultStatistics />

        <StudentScoreList />
        <!--    <div class="flex w-full items-center justify-center">-->
        <!--        <SecondaryButton-->
        <!--            title="Start Marking"-->
        <!--            class="w-28 rounded-xl bg-emerald-500 text-xs text-white"-->
        <!--        />-->
        <!--    </div>-->
    </div>
</template>
<script setup>
import moment from "moment";
import ResultStatistics from "@/Views/Teacher/Assessments/Details/ResultStatistics.vue";
import StudentScoreList from "@/Views/Teacher/Assessments/Details/StudentScoreList.vue";
import Header from "@/Views/Teacher/Assessments/Details/Header.vue";
import GeneralInfo from "@/Views/Teacher/Assessments/Details/Info.vue";
</script>
<style scoped></style>
