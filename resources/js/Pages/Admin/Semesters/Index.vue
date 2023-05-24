<template>
    <SemestersStatistics />

    <AllSemesters />

    <SemestersBySchoolYear :school-year-id="1" />

    <SemestersBySeason season="1" />

    <SemestersByStatus status="Upcoming" />
</template>

<script setup>
import SemestersStatistics from "@/Views/Admin/Semesters/SemestersStatistics.vue";
import AllSemesters from "@/Views/Admin/Semesters/AllSemesters.vue";
import SemestersBySchoolYear from "@/Views/Admin/Semesters/SemestersBySchoolYear.vue";
import SemestersBySeason from "@/Views/Admin/Semesters/SemestersBySeason.vue";
import SemestersByStatus from "@/Views/Admin/Semesters/SemestersByStatus.vue";
</script>

<style scoped></style>
