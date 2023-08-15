<template>
    <div>
        <span class="font-semibold">
            {{ $t("questionPreparation.dailyAIUsageProgress") }} ({{
                openaiUsagePercentage
            }})
        </span>
    </div>
    <div class="w-full rounded-full bg-purple-200">
        <div
            :class="progressBarClass"
            :style="{ width: openaiUsagePercentage }"
        >
            {{ openaiUsagePercentage }}
        </div>
    </div>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const openaiDailyUsage = computed(() => usePage().props.openai_daily_usage);
const openaiDailyUsageLimit = computed(
    () => usePage().props.openai_daily_usage_limit
);

const openaiUsagePercentage = computed(() => {
    const percentage =
        (openaiDailyUsage.value / openaiDailyUsageLimit.value) * 100;
    return `${percentage}%`;
});

const progressBarClass = computed(() => {
    const percentage = parseFloat(openaiUsagePercentage.value);
    if (percentage < 50)
        return "rounded-full bg-green-400 p-0.5 text-center text-xs font-medium leading-none text-white";
    if (percentage < 75)
        return "rounded-full bg-yellow-400 p-0.5 text-center text-xs font-medium leading-none text-white";
    return "rounded-full bg-red-600 p-0.5 text-center text-xs font-medium leading-none text-white";
});
</script>
