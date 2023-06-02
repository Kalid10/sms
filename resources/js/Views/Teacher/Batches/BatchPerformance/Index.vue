<template>
    <div
        class="flex w-full justify-between divide-x divide-gray-500 rounded-md border-2 border-zinc-700 py-4"
    >
        <Item
            title="Average"
            value="70.8%"
            value-style="text-negative-100"
            :icon="ArrowSmallDownIcon"
        />
        <Item
            title="Rank"
            value="2 / 5"
            value-style="text-positive-100"
            :icon="ArrowSmallUpIcon"
        />
        <Item
            title="Attendance"
            value="99.2%"
            value-style="text-positive-100"
            :icon="ArrowSmallUpIcon"
        />
        <Item
            title="Conduct"
            value="80.2%"
            value-style="text-negative-100"
            :icon="ArrowSmallDownIcon"
        />
        <Item
            title="Grade"
            value="80"
            value-style="text-negative-100"
            :icon="ArrowSmallDownIcon"
        />
    </div>
</template>
<script setup>
import Item from "@/Views/Teacher/Batches/BatchPerformance/Item.vue";
import {
    ArrowSmallDownIcon,
    ArrowSmallUpIcon,
} from "@heroicons/vue/24/outline";
</script>

<style scoped></style>
