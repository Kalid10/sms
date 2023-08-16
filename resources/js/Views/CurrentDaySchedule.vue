<template>
    <div
        class="flex h-full w-full flex-col justify-center rounded-lg bg-white shadow-sm"
        :class="classStyle ? classStyle : ' p-5 space-y-3 bg-white'"
    >
        <div v-if="schedule?.length" class="text-xl font-medium text-black">
            {{ moment().format("dddd") }}
            {{ $t("currentDaySchedule.schedule") }}
        </div>
        <div
            v-if="schedule?.length"
            class="flex w-full items-center justify-between divide-x text-center"
        >
            <!--            {{ batch.active_session }}-->

            <div
                v-for="(item, index) in schedule"
                :key="index"
                class="flex w-full justify-center"
            >
                <div
                    class="flex flex-col space-y-2 py-3 text-center text-brand-text-300"
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
                    <div class="text-[0.65rem] uppercase text-gray-500">
                        {{ numberWithOrdinal(Number(item.school_period.name)) }}
                        {{ $t("currentDaySchedule.period") }}
                    </div>
                </div>
            </div>
        </div>
        <div
            v-else
            class="flex h-44 items-center justify-center py-5 text-center text-2xl font-bold uppercase"
        >
            <EmptyView
                :title="
                    $t('currentDaySchedule.noScheduleFound') +
                    moment().format('dddd')
                "
            />
        </div>
    </div>
</template>
<script setup>
import { numberWithOrdinal } from "@/utils";
import moment from "moment";
import EmptyView from "@/Views/EmptyView.vue";

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
