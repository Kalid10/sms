<template>
    <div
        class="flex w-full flex-col items-center space-y-3 rounded-lg bg-brand-100 py-2 text-white shadow-sm"
    >
        <div
            class="flex space-x-2 text-center text-xl font-semibold underline-offset-4"
        >
            <ArrowTrendingUpIcon class="w-6" />
            <span>{{ $t("studentRank.rank") }}</span>
        </div>
        <div
            v-if="rank"
            class="w-fit -skew-x-3 px-5 py-0.5 text-center text-6xl font-bold text-white"
        >
            {{ numberWithOrdinal(rank) }}
        </div>
        <div v-else class="text-4xl font-bold">-</div>
        <div class="text-sm text-brand-text-200">
            From
            <span class="text-brand-text-100">{{ totalStudents }}</span>
            students
            <span
                v-if="batchSubject"
                class="font-semibold uppercase text-brand-text-100"
                >in {{ batchSubject?.subject?.full_name }}</span
            >
        </div>
    </div>
</template>

<script setup>
import { ArrowTrendingUpIcon } from "@heroicons/vue/24/outline";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import { numberWithOrdinal } from "@/utils";

const rank = computed(() => usePage().props.grade?.rank);
const totalStudents = computed(() => usePage().props.total_batch_students);
const batchSubject = computed(() => usePage().props.batch_subject);
</script>

<style scoped></style>
