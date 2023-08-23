<template>
    <div class="flex w-full items-center justify-between">
        <OutcomePercentageDisplay
            icon-class="icon-check text-green-500"
            :title="$t('assessmentOutcomePercentages.passedPercentTitle')"
            :value="100 - passedPercent"
            :icon="ArrowSmallUpIcon"
            type="positive"
        />

        <div class="h-12 w-[0.01rem] bg-brand-150"></div>
        <OutcomePercentageDisplay
            icon-class="icon-close text-red-500"
            :title="$t('assessmentOutcomePercentages.failedPercentTitle')"
            :value="passedPercent"
            :icon="ArrowSmallDownIcon"
            type="negative"
        />
    </div>
</template>

<script setup>
import { defineProps } from "vue";
import OutcomePercentageDisplay from "./OutcomePercentageDisplay.vue";
import { ArrowSmallDownIcon, ArrowSmallUpIcon } from "@heroicons/vue/24/solid";

defineProps({
    passedPercent: {
        type: Number,
        required: true,
    },
});
</script>
