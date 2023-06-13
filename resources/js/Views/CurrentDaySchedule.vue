<template>
    <div
        class="flex h-full w-full flex-col justify-center space-y-3 rounded-lg border border-zinc-700 bg-white p-5 shadow-sm"
    >
        <div v-if="batch.schedule" class="text-xl font-semibold text-gray-700">
            {{ moment().format("dddd") }}'s Schedule
        </div>
        <div
            v-if="batch.schedule"
            class="flex w-full items-center justify-between divide-x text-center"
        >
            <!--            {{ batch.active_session }}-->

            <div
                v-for="(item, index) in batch.schedule"
                :key="index"
                class="flex w-full justify-center"
            >
                <div
                    class="flex flex-col space-y-2 py-3 text-center text-gray-500"
                    :class="
                        batch?.active_session[0]?.batch_schedule_id === item.id
                            ? 'bg-gradient-to-bl from-violet-500 to-purple-500 text-white rounded-lg w-11/12 '
                            : 'text-black w-full'
                    "
                >
                    <div class="">
                        {{ item.batch_subject.subject.short_name }}
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
            class="text-center text-2xl font-bold uppercase text-gray-600"
        >
            No schedule found for this section!
        </div>
    </div>
</template>
<script setup>
import { numberWithOrdinal } from "@/utils";
import moment from "moment";

defineProps({
    batch: {
        type: Array,
        required: true,
    },
});
</script>
<style scoped></style>
