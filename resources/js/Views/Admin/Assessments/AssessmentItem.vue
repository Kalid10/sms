<template>
    <div
        class="group flex w-full cursor-pointer items-center justify-between py-4 text-sm hover:scale-105 hover:rounded-lg hover:bg-zinc-800 hover:text-white"
    >
        <div
            :class="{
                'border-yellow-500': assessment.status === 'marking',
                'border-green-500': assessment.status === 'marked',
                'border-red-500': assessment.status === 'closed',
                'border-sky-500': assessment.status === 'published',
                'border-blue-500': assessment.status === 'completed',
            }"
            class="w-2/12 border-l px-3 text-center text-xs font-light capitalize group-hover:border-none group-hover:text-center"
        >
            {{ assessment.status }}
        </div>
        <div class="w-3/12 text-center font-medium">
            {{ assessment.title }}
        </div>
        <div class="w-3/12 text-center font-light">
            {{ assessment.description }}
        </div>
        <div class="w-3/12 text-center font-light">
            {{ moment(assessment.due_date).format("dddd MMM DD YYYY") }}
        </div>
    </div>
</template>
<script setup>
import moment from "moment/moment";

const props = defineProps({
    assessment: {
        type: Object,
        required: true,
    },
});
</script>

<style scoped></style>
