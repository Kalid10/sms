<template>
    <div class="flex flex-col space-y-2">
        <div class="text-center font-semibold uppercase">
            {{ $t("questionPreparation.dailyAIUsageProgress") }}
        </div>
        <div class="w-full rounded-full bg-brand-100">
            <div
                :class="progressBarClass"
                :style="{ width: openaiUsagePercentage }"
            >
                {{ openaiUsagePercentage }}
            </div>
        </div>
    </div>
</template>

<script setup>
import { router, usePage } from "@inertiajs/vue3";
import { computed, onMounted, watch } from "vue";

const props = defineProps({
    updateUsage: {
        type: Boolean,
        default: false,
    },
    page: {
        type: String,
        default: "/teacher/copilot",
    },
});

onMounted(() => {
    fetchAIUsage();
});

watch(
    () => props.updateUsage,
    () => {
        fetchAIUsage();
        props.updateUsage = false;
    }
);
const openaiDailyUsage = computed(() => usePage().props.openai_daily_usage);
const openaiDailyUsageLimit = computed(
    () => usePage().props.openai_daily_usage_limit
);

const openaiUsagePercentage = computed(() => {
    const percentage =
        (openaiDailyUsage.value / openaiDailyUsageLimit.value) * 100;
    return `${Math.min(percentage, 100).toFixed(1)}%`;
});

const progressBarClass = computed(() => {
    const percentage = parseFloat(openaiUsagePercentage.value);
    if (percentage < 20)
        return "rounded-full bg-brand-100 p-0.5 text-center text-xs font-medium leading-none text-black";
    if (percentage < 50)
        return "rounded-full bg-brand-250 p-0.5 text-center text-xs font-medium leading-none text-white";
    if (percentage < 75)
        return "rounded-full bg-brand-300 p-0.5 text-center text-xs font-medium leading-none text-white";
    return "rounded-full bg-brand-400 p-0.5 text-center text-xs font-medium leading-none text-white";
});

const fetchAIUsage = () => {
    router.get(
        props.page,
        {},
        {
            preserveState: true,
            only: ["openai_daily_usage", "openai_daily_usage_limit"],
            onFinish: () => {
                props.updateUsage = false;
            },
        }
    );
};
</script>
