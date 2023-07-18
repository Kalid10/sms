<template>
    <div class="flex w-full items-center justify-center space-x-0.5">
        <div :class="scoreClass">{{ score }}</div>
        <div v-if="total" :class="dividerClass">/</div>
        <span :class="totalClass">{{ total }}</span>
        <span
            class="flex h-5 items-end justify-center text-[0.55rem] text-brand-text-350"
        >
            {{ $t("scoreDisplay.pts") }}
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
    () => `${props.size === "sm" ? "text-sm" : "text-xl"}`
);
const totalClass = computed(
    () => `font-light ${props.size === "sm" ? "text-xs" : "text-sm"}`
);
</script>

<style scoped></style>
