<template>
    <div class="flex w-full flex-col">
        <div class="flex flex-col pb-6">
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
