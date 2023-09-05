<template>
    <div class="flex w-full flex-col p-6">
        <div class="flex flex-col">
            <Heading size="2xl"> Your Weekly Schedule</Heading>
        </div>
        <TeacherScheduleView />
    </div>
</template>

<script setup>
import Heading from "@/Components/Heading.vue";
import TeacherScheduleView from "@/Views/Teacher/BatchSchedule/TeacherScheduleView.vue";
</script>
<style scoped></style>
