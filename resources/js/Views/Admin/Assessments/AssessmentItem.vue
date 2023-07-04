<template>
    <div
        class="group flex h-full cursor-pointer items-center space-x-10 py-4 hover:scale-105 hover:rounded-lg hover:bg-zinc-800 hover:text-white"
    >
        <div
            :class="{
                'border-yellow-500': assessment.status === 'marking',
                'border-green-500': assessment.status === 'marked',
                'border-red-500': assessment.status === 'closed',
                'border-sky-500': assessment.status === 'published',
                'border-blue-500': assessment.status === 'completed',
            }"
            class="w-1/12 border-l px-3 text-center text-xs font-light capitalize group-hover:border-none group-hover:text-center"
        >
            {{ assessment.status }}
        </div>
        <div class="flex w-8/12 flex-col justify-center space-y-4">
            <span class="flex text-sm font-medium">
                {{ assessment.title }}
            </span>
            <span class="flex justify-center text-sm font-light"
                >{{ assessment.description }}
            </span>
            <div class="flex justify-end space-x-2">
                <CalendarDaysIcon class="w-4 text-gray-500" />
                <span class="text-xs font-light">
                    {{
                        moment(assessment.due_date).format("dddd MMM DD YYYY")
                    }}</span
                >
            </div>
        </div>
    </div>
</template>
<script setup>
import { CalendarDaysIcon } from "@heroicons/vue/24/solid";
import moment from "moment/moment";

const props = defineProps({
    assessment: {
        type: Object,
        required: true,
    },
});
</script>

<style scoped></style>
