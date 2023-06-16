<template>
    <div
        class="flex w-full justify-between divide-x divide-gray-200 rounded-md bg-gray-50 py-4 shadow-sm"
    >
        <Item
            title="Class Average"
            :value="grade?.score"
            value-style="text-negative-100"
            :icon="ArrowSmallDownIcon"
        />
        <Item
            title="Class Rank"
            :value="grade?.rank"
            value-style="text-positive-100"
            :icon="ArrowSmallUpIcon"
        />
        <Item
            title="Class Attendance"
            :value="grade?.attendance ? grade?.attendance + '%' : '-'"
            value-style="text-positive-100"
            :icon="ArrowSmallUpIcon"
        />
        <Item
            title="Class Conduct"
            :value="grade?.conduct"
            value-style="text-negative-100"
            :icon="ArrowSmallDownIcon"
        />
    </div>
</template>
<script setup>
import Item from "@/Views/Teacher/Views/Batches/BatchPerformance/Item.vue";
import {
    ArrowSmallDownIcon,
    ArrowSmallUpIcon,
} from "@heroicons/vue/24/outline";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const props = defineProps({
    grade: {
        type: Object,
        default: null,
    },
});

const grade = props.grade ?? computed(() => usePage().props.grade);
</script>

<style scoped></style>
