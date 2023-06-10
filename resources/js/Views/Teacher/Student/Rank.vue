<template>
    <div
        class="flex w-full flex-col items-center space-y-3 rounded-lg bg-gradient-to-tl from-neutral-600 to-zinc-800 py-2 text-white shadow-sm"
    >
        <div
            class="flex space-x-2 text-center text-xl font-semibold underline-offset-4"
        >
            <ArrowTrendingUpIcon class="w-6" />
            <span>Rank</span>
        </div>
        <div
            v-if="rank"
            class="w-fit -skew-x-3 px-5 py-0.5 text-center text-6xl font-bold text-white"
        >
            {{ numberWithOrdinal(rank) }}
        </div>
        <div v-else class="text-4xl font-bold">-</div>
        <div class="text-sm text-gray-300">
            From
            <span class="text-zinc-100">{{ totalStudents }}</span>
            students in
            <span class="font-semibold uppercase text-zinc-100">{{
                batchSubject.subject.full_name
            }}</span>
        </div>
    </div>
</template>

<script setup>
import { ArrowTrendingUpIcon } from "@heroicons/vue/24/outline";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import { numberWithOrdinal } from "@/utils";

const rank = computed(() => usePage().props.batch_subject_grade?.rank);
const totalStudents = computed(() => usePage().props.total_batch_students);
const batchSubject = computed(() => usePage().props.batch_subject);
</script>

<style scoped></style>
