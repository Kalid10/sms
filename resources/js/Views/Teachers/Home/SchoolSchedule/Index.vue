<template>
    <div class="flex w-full flex-col items-center">
        <!--        <div class="px-4 text-2xl font-medium">School Schedule</div>-->

        <div class="flex h-fit w-full flex-col justify-between rounded-lg">
            <DateNavigation />
            <Schedules />
        </div>
    </div>
</template>
<script setup>
import DateNavigation from "@/Views/Teachers/Home/SchoolSchedule/DateNavigation.vue";
import Schedules from "@/Views/Teachers/Home/SchoolSchedule/Schedules.vue";
</script>

<style scoped></style>
