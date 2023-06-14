<template>
    <div class="flex h-fit w-full flex-col items-center space-y-5">
        <div class="w-full rounded-lg bg-white px-3 py-4 shadow-sm">
            <div class="w-full pb-1 text-start text-2xl font-semibold">
                Class Performance
            </div>
            <div class="w-full pr-5">
                <BatchPerformance />
            </div>
        </div>
        <div
            class="flex w-full flex-col space-y-2 rounded-lg bg-white p-3 shadow-sm"
        >
            <div class="w-full pb-1 text-start text-2xl font-semibold">
                Student Performance List
            </div>

            <div
                class="flex w-full items-center justify-between divide-x divide-zinc-300"
            >
                <div class="w-1/2">
                    <StudentsList
                        progress-type="up"
                        title="Top Students"
                        :icon="ArrowTrendingUpIcon"
                        :students="topStudents"
                    />
                </div>

                <div class="w-1/2">
                    <StudentsList
                        progress-type="down"
                        title="Students Falling Behind"
                        :icon="ArrowTrendingDownIcon"
                        :students="bottomStudents"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import {
    ArrowTrendingDownIcon,
    ArrowTrendingUpIcon,
} from "@heroicons/vue/24/outline";
import StudentsList from "@/Views/Teacher/Views/Batches/PerformanceHighlights/StudentsList.vue";
import BatchPerformance from "@/Views/Teacher/Views/Batches/BatchPerformance/Index.vue";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const topStudents = computed(() => usePage().props.top_students);
const bottomStudents = computed(() => usePage().props.bottom_students);
</script>

<style scoped></style>
