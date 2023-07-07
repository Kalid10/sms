<template>
    <div
        class="flex h-fit w-full flex-col items-center"
        :class="isTeacher() ? 'space-y-5' : 'space-y-14'"
    >
        <div class="w-full px-3 py-4">
            <div class="w-full pb-1 text-start text-2xl font-semibold">
                {{ $t('performanceHighlightsIndex.classPerformance')}}
            </div>
            <div class="w-full pr-5">
                <BatchPerformance />
            </div>
        </div>
        <div
            class="flex w-full flex-col items-center justify-between divide-y divide-zinc-300 rounded-lg bg-white px-3 py-5"
        >
            <div class="w-full">
                <StudentsList
                    progress-type="up"
                    :title="$t('performanceHighlightsIndex.topStudents')"
                    :icon="ArrowTrendingUpIcon"
                    :students="topStudents"
                />
            </div>

            <div class="w-full">
                <StudentsList
                    progress-type="down"
                    :title="$t('performanceHighlightsIndex.StudentsFallingBehind')"
                    :icon="ArrowTrendingDownIcon"
                    :students="bottomStudents"
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
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import { isTeacher } from "@/utils";

const topStudents = computed(() => usePage().props.top_students);
const bottomStudents = computed(() => usePage().props.bottom_students);
</script>

<style scoped></style>
