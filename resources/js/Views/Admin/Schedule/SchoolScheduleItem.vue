<template>
    <div
        class="group flex h-full cursor-pointer items-center justify-between space-x-10 bg-white py-5 hover:scale-105 hover:rounded-lg hover:bg-zinc-800 hover:text-white"
    >
        <div
            :class="{
                'border-yellow-500': schoolSchedule.type === 'holiday',
                'border-sky-500': schoolSchedule.type === 'event',
            }"
            class="w-1/12 border-l px-3 text-center text-sm font-light uppercase group-hover:border-none group-hover:text-center"
        >
            {{ schoolSchedule.type }}
        </div>
        <div class="flex w-8/12 flex-col justify-center space-y-4">
            <div class="text-sm font-medium">
                {{ schoolSchedule.title }}
                {{ schoolSchedule.type }}
            </div>
            <div class="flex items-center space-x-2">
                <CalendarDaysIcon class="w-4 text-gray-500" />
                <span class="text-xs font-light">{{
                    moment(schoolSchedule.start_date).format(
                        "dddd MMM DD YYYY"
                    ) +
                    " - " +
                    moment(schoolSchedule.end_date).format("dddd MMM DD YYYY")
                }}</span>
            </div>
        </div>
        <div class="flex w-2/12 justify-evenly">
            <TrashIcon
                class="group-hover:text-ref-500 w-4 cursor-pointer text-red-600 hover:scale-110"
            />
            <PencilSquareIcon
                class="w-4 cursor-pointer text-gray-500 hover:scale-110 group-hover:text-gray-200"
            />
        </div>
    </div>
</template>
<script setup>
import { CalendarDaysIcon } from "@heroicons/vue/24/solid";
import { PencilSquareIcon, TrashIcon } from "@heroicons/vue/24/outline";
import moment from "moment/moment";

defineProps({
    schoolSchedule: {
        type: Object,
        required: true,
    },
});
</script>

<style scoped></style>
