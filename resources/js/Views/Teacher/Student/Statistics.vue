<template>
    <div class="flex w-full justify-between">
        <StatCard
            title="Grade"
            color="blue"
            value="50/60"
            :icon="ChartBarIcon"
        />
        <StatCard
            title="Attendance"
            color="red"
            value="80%"
            :icon="CalendarDaysIcon"
        />
        <StatCard
            title="Conduct"
            color="emerald"
            value="C"
            :icon="HandThumbUpIcon"
        />
        <StatCard
            title="Vitals"
            color="emerald"
            value="Norm"
            :icon="BeakerIcon"
        />
    </div>
</template>
<script setup>
import StatCard from "@/Components/StatCard.vue";
import {
    BeakerIcon,
    CalendarDaysIcon,
    ChartBarIcon,
    HandThumbUpIcon,
} from "@heroicons/vue/24/outline";
</script>
<style scoped></style>
