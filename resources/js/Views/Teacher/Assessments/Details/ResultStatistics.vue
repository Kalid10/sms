<template>
    <div class="flex w-full items-center justify-between">
        <ResultCard label="Average Result" score="13.5" total="20" />
        <div class="h-14 w-[0.01rem] bg-gray-200"></div>
        <ResultCard label="Highest Result" score="20" total="20" />
        <div class="h-14 w-[0.01rem] bg-gray-200"></div>
        <ResultCard label="Lowest Result" score="3.5" total="20" />
    </div>
</template>

<script setup>
import ResultCard from "@/Views/Teacher/Assessments/Details/ResultCard.vue";
</script>
