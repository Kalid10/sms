<template>
    <div class="w-10/12 py-5">
        <Title class="pb-8" title="Staff Absentees" />

        <StaffAbsenteesTable />
    </div>
</template>
<script setup>
import Title from "@/Views/Teacher/Views/Title.vue";
import StaffAbsenteesTable from "@/Views/Admin/Absentees/StaffAbsenteesTable.vue";
</script>
<style scoped></style>
