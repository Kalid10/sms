<template>
    <div
        class="flex h-full w-4/12 flex-col items-center justify-center rounded-md"
    >
        <div class="text-center text-[0.6rem]">{{ label }}</div>
        <ScoreDisplay :score="score" :total="total" :size="size" />
    </div>
</template>

<script setup>
import ScoreDisplay from "@/Views/Teacher/Assessments/Details/ScoreDisplay.vue";

defineProps({
    label: { type: String, default: "" },
    score: { type: String, required: true },
    total: { type: String, default: "" },
    size: { type: String, default: "lg" },
});
</script>

<style scoped></style>
