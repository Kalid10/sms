<template>
    <WeeklySchedule />
</template>
<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import WeeklySchedule from "@/Views/Admin/Batches/WeeklySchedule.vue";

const batchSchedules = computed(() => usePage().props.batch_schedules);

const formattedSchedules = computed(() => {
    return batchSchedules.value.map((batchSchedule) => {
        return {
            subject:
                batchSchedule.sessions[0].batch_subject?.subject?.full_name ??
                "-",
            period: batchSchedule.sessions[0].school_period?.name,
            status: batchSchedule.sessions[0].status,
            startTime: batchSchedule.sessions[0].school_period?.start_time,
            teacher: batchSchedule.sessions[0].teacher?.user.name ?? "-",
        };
    });
});

const config = [
    {
        name: "Subjects",
        key: "subject",
        class: "font-semibold",
        align: "left",
    },
    {
        name: "Period",
        key: "period",
        class: "text-gray-500 text-xs font-semibold",
        align: "left",
    },
    {
        name: "",
        key: "status",
        type: "enum",
        options: ["scheduled", "in_progress", "completed"],
        align: "left",
    },
    {
        name: "Start Time",
        key: "startTime",
        align: "left",
    },
    {
        name: "Teacher",
        key: "teacher",
        sortable: true,
        align: "left",
    },
];
</script>
<style scoped></style>
