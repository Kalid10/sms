<template>
    <div class="flex min-h-full w-full flex-col space-y-5">
        <div class="flex w-full justify-between">
            <TableElement
                :selectable="false"
                :filterable="false"
                :data="mappedAbsenteeList"
                :columns="columns"
                title="Your Absentee List"
                header-style="!bg-brand-400 text-white"
                class="h-full !w-7/12 !rounded-lg p-4 shadow-sm"
            >
                <template #date-column="{ data }">
                    <div>{{ moment(data).format("dddd  MMMM DD YYYY") }}</div>
                </template>
                <template #batch_session-column="{ data }">
                    <div v-if="data !== null">
                        {{
                            data.batch_schedule.batch.level.name +
                            "-" +
                            data.batch_schedule.batch.section +
                            " " +
                            data.batch_schedule.batch_subject.subject.full_name
                        }}
                    </div>
                    <div v-else>Full Day</div>
                </template>
            </TableElement>

            <div class="flex w-4/12 items-center justify-center">
                <div
                    v-if="teacher.leave_info !== null"
                    class="flex w-9/12 flex-col items-center justify-center space-y-6 rounded-lg px-5 py-10 font-bold text-white shadow-sm"
                    :class="
                        teacher.leave_info.remaining > 0
                            ? 'bg-emerald-400'
                            : 'bg-red-600'
                    "
                >
                    <span class="text-8xl">
                        {{
                            teacher.leave_info.total -
                            teacher.leave_info.remaining
                        }}
                        / {{ teacher.leave_info.total }}</span
                    >
                    <span class="px-4 text-center font-light text-brand-50">
                        <span v-if="teacher.leave_info.remaining > 0">
                            Your Leave Info
                        </span>
                        <span v-else
                            >You have reached your limit! Contact Admin to
                            increase limit.</span
                        >
                    </span>
                </div>
                <div v-else>
                    <div
                        class="flex w-9/12 flex-col items-center justify-center space-y-6 rounded-lg bg-red-600 px-5 py-10 text-center font-bold text-white shadow-sm"
                    >
                        <span>Your is leave is not set, contact admin!</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import TableElement from "@/Components/TableElement.vue";
import { computed, onBeforeMount } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import moment from "moment";

const teacher = usePage().props.auth.user.teacher;
const absenteeList = computed(() => usePage().props.absentee_list);
const mappedAbsenteeList = computed(() => {
    return absenteeList?.value?.data.map((item) => {
        return {
            date: item.created_at,
            is_leave: item.is_leave,
            batch_session: item.batch_session,
            reason: item.reason,
        };
    });
});

const columns = [
    {
        name: "Date",
        key: "date",
        type: "custom",
    },
    {
        name: "Reason",
        key: "reason",
    },
    {
        name: "Missed",
        key: "batch_session",
        type: "custom",
    },
    {
        name: "Is Leave",
        key: "is_leave",
        type: Boolean,
    },
];

onBeforeMount(() => {
    router.visit("/teacher/extras", {
        preserveState: true,
        only: ["absentee_list"],
    });
});
</script>
<style scoped></style>
