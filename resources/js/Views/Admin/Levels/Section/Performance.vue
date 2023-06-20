<template>
    <div class="flex w-full flex-col space-y-6 rounded-lg">
        <BatchPerformance :grade="batch?.grade" />
        <div
            class="flex w-full items-center justify-between divide-x divide-gray-100"
        >
            <div class="w-6/12">
                <StudentsList
                    progress-type="up"
                    title="Top Students"
                    :icon="ArrowTrendingUpIcon"
                    :students="batch?.top_students"
                    :show-link="false"
                />
            </div>

            <div class="w-6/12">
                <StudentsList
                    progress-type="down"
                    title="Students Falling Behind"
                    :icon="ArrowTrendingDownIcon"
                    :students="batch?.bottom_students"
                    :show-link="false"
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
import StudentsList from "@/Views/Teacher/Views/Batches/PerformanceHighlights/StudentsList.vue";
import BatchPerformance from "@/Views/Teacher/Views/Batches/BatchPerformance/Index.vue";

defineProps({
    batch: {
        type: Object,
        default: null,
    },
});
</script>
<style scoped></style>
