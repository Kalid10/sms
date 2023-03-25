<template>

    <SemestersStatistics/>

    <AllSemesters/>

    <SemestersBySchoolYear :school-year-id="1"/>

    <SemestersBySeason season="1"/>

    <SemestersByStatus status="Upcoming"/>

</template>

<script setup>
import SemestersStatistics from "@/Views/Semesters/SemestersStatistics.vue"
import AllSemesters from "@/Views/Semesters/AllSemesters.vue";
import SemestersBySchoolYear from "@/Views/Semesters/SemestersBySchoolYear.vue"
import SemestersBySeason from "@/Views/Semesters/SemestersBySeason.vue"
import SemestersByStatus from "@/Views/Semesters/SemestersByStatus.vue"</script>

<style scoped>

</style>
