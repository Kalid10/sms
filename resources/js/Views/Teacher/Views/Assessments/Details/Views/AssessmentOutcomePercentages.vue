<template>
    <div class="flex w-full items-center justify-between">
        <OutcomePercentageDisplay
            icon-class="icon-check text-green-500"
            :title="$t('AssessmentOutcomePercentages.passedPercentTitle')"
            :value="passedPercent"
            :icon="ArrowSmallUpIcon"
            type="positive"
        />

        <div class="h-12 w-[0.01rem] bg-gray-200"></div>
        <OutcomePercentageDisplay
            icon-class="icon-close text-red-500"
            :title="$t('AssessmentOutcomePercentages.failedPercentTitle')"
            :value="100 - passedPercent"
            :icon="ArrowSmallDownIcon"
            type="negative"
        />
    </div>
</template>

<script setup>
import { defineProps } from "vue";
import OutcomePercentageDisplay from "./OutcomePercentageDisplay.vue";
import { ArrowSmallDownIcon, ArrowSmallUpIcon } from "@heroicons/vue/24/solid";
import TextArea from "@/Components/TextArea.vue";

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
