<template>
    <div class="flex w-full flex-col space-y-4 rounded-lg p-4">
        <BatchPerformance class="my-4" :grade="batch?.grade" />
        <div
            class="flex w-full items-center justify-between divide-x divide-zinc-300"
        >
            <div class="w-1/2">
                <StudentsList
                    progress-type="up"
                    title="Top Students"
                    :icon="ArrowTrendingUpIcon"
                    :students="batch?.top_students"
                />
            </div>

            <div class="w-1/2">
                <StudentsList
                    progress-type="down"
                    title="Students Falling Behind"
                    :icon="ArrowTrendingDownIcon"
                    :students="batch?.bottom_students"
                />
            </div>
        </div>
    </div>
</template>
<script setup>
import {
    ArrowTrendingDownIcon,
    ArrowTrendingUpIcon,
} from "@heroicons/vue/24/outline";
import StudentsList from "@/Views/Teacher/Batches/PerformanceHighlights/StudentsList.vue";
import BatchPerformance from "@/Views/Teacher/Batches/BatchPerformance/Index.vue";

defineProps({
    batch: {
        type: Object,
        default: null,
    },
});
</script>
<style scoped></style>
