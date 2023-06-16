<template>
    <div
        class="flex h-full w-full flex-col justify-center rounded-lg border border-zinc-700 shadow-sm"
        :class="classStyle ? classStyle : ' p-5 space-y-3 bg-white'"
    >
        <div v-if="schedule" class="text-xl font-semibold text-gray-700">
            {{ moment().format("dddd") }}'s Schedule
        </div>
        <div
            v-if="schedule"
            class="flex w-full items-center justify-between divide-x text-center"
        >
            <!--            {{ batch.active_session }}-->

            <div
                v-for="(item, index) in schedule"
                :key="index"
                class="flex w-full justify-center"
            >
                <div
                    class="flex flex-col space-y-2 py-3 text-center text-gray-500"
                    :class="
                        activeBatchScheduleId === item.id
                            ? 'bg-gradient-to-bl from-violet-500 to-purple-500 text-white rounded-lg w-11/12 '
                            : 'text-black w-full'
                    "
                >
                    <div class="text-black">
                        {{ item.batch_subject.subject.short_name }}
                        <span v-if="item?.batch" class="text-xs"
                            >({{ item.batch.level.name }}
                            {{ item.batch.section }})</span
                        >
                    </div>
                    <div class="text-[0.65rem] uppercase">
                        {{ numberWithOrdinal(Number(item.school_period.name)) }}
                        Period
                    </div>
                </div>
            </div>
        </div>
        <div
            v-else
            class="py-5 text-center text-2xl font-bold uppercase text-gray-600"
        >
            No schedule found!
        </div>
    </div>
</template>
<script setup>
import { numberWithOrdinal } from "@/utils";
import moment from "moment";

defineProps({
    schedule: {
        type: Array,
        required: true,
    },
    activeBatchScheduleId: {
        type: Number,
        default: 0,
    },
    classStyle: {
        type: String,
        default: null,
    },
});
</script>
<style scoped></style>
