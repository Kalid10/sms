<template>
    <div class="flex w-full items-center justify-between">
        <OutcomePercentageDisplay
            icon-class="icon-check text-green-500"
            title="Passed"
            :value="passedPercent"
            :icon="ArrowTrendingUpIcon"
            type="positive"
        />

        <div class="h-12 w-[0.01rem] bg-gray-200"></div>
        <OutcomePercentageDisplay
            icon-class="icon-close text-red-500"
            title="Failed"
            :value="failedPercent"
            :icon="ArrowSmallDownIcon"
            type="negative"
        />
    </div>
</template>

<script setup>
import { defineProps } from "vue";
import OutcomePercentageDisplay from "./OutcomePercentageDisplay.vue";
import {
    ArrowSmallDownIcon,
    ArrowTrendingUpIcon,
} from "@heroicons/vue/24/solid";

defineProps({
    passedPercent: {
        type: Number,
        required: true,
    },
    failedPercent: {
        type: Number,
        required: true,
    },
});
</script>
