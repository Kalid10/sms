<template>
    <div class="flex h-fit w-full flex-col items-center">
        <!--        <div class="px-4 text-2xl font-medium">School Schedule</div>-->

        <div class="flex h-fit w-full flex-col justify-between rounded-lg">
            <DateNavigation :schedule-url="scheduleUrl" />
            <Schedules />
        </div>
    </div>
</template>
<script setup>
import DateNavigation from "@/Views/SchoolSchedule/DateNavigation.vue";
import Schedules from "@/Views/SchoolSchedule/Schedules.vue";

defineProps({
    scheduleUrl: {
        type: String,
        default: "/teacher?school_schedule_date=",
    },
});
</script>

<style scoped></style>
