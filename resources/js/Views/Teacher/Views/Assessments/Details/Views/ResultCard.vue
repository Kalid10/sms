<template>
    <div
        class="flex h-full w-4/12 flex-col items-center justify-center space-y-1 rounded-md"
    >
        <div class="text-center text-[0.55rem] font-light">{{ label }}</div>
        <ScoreDisplay :score="score" :total="total" :size="size" />
    </div>
</template>

<script setup>
import ScoreDisplay from "@/Views/Teacher/Views/Assessments/Details/Views/ScoreDisplay.vue";

defineProps({
    label: { type: String, default: "" },
    score: { type: String, required: true },
    total: { type: String, default: "" },
    size: { type: String, default: "lg" },
});
</script>

<style scoped></style>
