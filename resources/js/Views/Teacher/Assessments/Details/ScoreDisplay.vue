<template>
    <div class="flex w-full items-center justify-center space-x-0.5">
        <div :class="scoreClass">{{ score }}</div>
        <div :class="dividerClass">/</div>
        <span :class="totalClass">{{ total }}</span>
        <span
            class="flex h-5 items-end justify-center px-0.5 text-[0.4rem] text-gray-600"
        >
            PTS
        </span>
    </div>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
    score: { type: String, required: true },
    total: { type: String, required: true },
    size: { type: String, default: "sm" },
});

const scoreClass = computed(
    () => `font-bold ${props.size === "sm" ? "text-sm" : "text-2xl"}`
);
const dividerClass = computed(
    () => `${props.size === "sm" ? "text-sm" : "text-2xl"}`
);
const totalClass = computed(
    () => `font-light ${props.size === "sm" ? "text-xs" : "text-xl"}`
);
</script>

<style scoped></style>
