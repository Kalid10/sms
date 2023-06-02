<template>
    <div class="flex h-40 w-10/12 flex-col items-center space-y-4">
        <div
            class="flex space-x-2 text-center text-xl font-semibold underline-offset-4"
        >
            <ArrowTrendingUpIcon class="w-6" />
            <span>Rank</span>
        </div>
        <div
            class="w-fit -skew-x-3 bg-black px-5 py-0.5 text-center text-6xl font-bold text-white"
        >
            {{ numberWithOrdinal(rank) }}
        </div>
        <div class="text-sm text-gray-500">
            From
            <span class="text-gray-700">30</span>
            students in
            <span class="text-gray-700">Biology</span>
        </div>
    </div>
</template>

<script setup>
import { ArrowTrendingUpIcon } from "@heroicons/vue/24/outline";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import { numberWithOrdinal } from "@/utils";

const rank = computed(() => usePage().props.batch_subject_rank);
</script>

<style scoped></style>
