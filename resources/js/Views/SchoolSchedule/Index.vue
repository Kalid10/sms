<template>
    <div class="flex h-fit w-full flex-col items-center">
        <!--        <div class="px-4 text-2xl font-medium">School Schedule</div>-->

        <Loading v-if="showLoading" color="secondary" />
        <div
            v-else
            class="flex h-fit w-full flex-col justify-between rounded-lg"
        >
            <DateNavigation
                :schedule-url="scheduleUrl"
                @loading="showLoading = true"
                @finish="showLoading = false"
            />
            <Schedules />
        </div>
    </div>
</template>
<script setup>
import DateNavigation from "@/Views/SchoolSchedule/DateNavigation.vue";
import Schedules from "@/Views/SchoolSchedule/Schedules.vue";
import { ref } from "vue";
import Loading from "@/Components/Loading.vue";

defineProps({
    scheduleUrl: {
        type: String,
        default: "/teacher?school_schedule_date=",
    },
});

const showLoading = ref(false);
</script>

<style scoped></style>
