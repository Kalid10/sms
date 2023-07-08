<template>
    <div
        v-if="batchSchedules.length > 0"
        class="flex w-full flex-col justify-start space-y-6"
    >
        <TableElement
            :selectable="false"
            :filterable="false"
            :title="`${batchSchedules[0].day_of_week} Schedules `"
            :columns="config"
            :data="formattedSchedules"
        />
    </div>
    <div v-else class="flex w-full items-center justify-center">
        <EmptyView :title="$t('batchesSchedules.noSchedulesFound')" />
    </div>
</template>
<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import EmptyView from "@/Views/EmptyView.vue";
import TableElement from "@/Components/TableElement.vue";
import {useI18n} from "vue-i18n";
const {t} = useI18n()
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
        name: t('common.subject'),
        key: "subject",
        class: "font-semibold",
        align: "left",
    },
    {
        name: t('common.period'),
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
        name: t('batchesSchedules.startTime'),
        key: "startTime",
        align: "left",
    },
    {
        name:t('common.teacher'),
        key: "teacher",
        sortable: true,
        align: "left",
    },
];
</script>
<style scoped></style>
