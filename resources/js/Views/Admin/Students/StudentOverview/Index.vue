<template>
    <div class="flex w-full gap-3">
        <StudentPersonalDetail />
        <StudentGuardianContactCard />
    </div>
</template>

<script setup>
import StudentGuardianContactCard from "@/Views/Admin/Students/StudentOverview/StudentGuardianContactCard.vue";
import StudentPersonalDetail from "@/Views/Admin/Students/StudentOverview/StudentPersonalDetail.vue";
</script>

<style scoped></style>
