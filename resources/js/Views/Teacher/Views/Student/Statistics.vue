<template>
    <div class="flex w-full justify-between space-x-8">
        <StatCard
            :title="$t('studentStatistics.grade')"
            color="blue"
            value="50/60"
            :icon="ChartBarIcon"
        />
        <StatCard
            :title="$t('studentStatistics.attendance')"
            color="red"
            value="80%"
            :icon="CalendarDaysIcon"
        />
        <StatCard
            :title="$t('studentStatistics.conduct')"
            color="emerald"
            value="C"
            :icon="HandThumbUpIcon"
        />
        <StatCard
            :title="$t('studentStatistics.vitals')"
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
import TextArea from "@/Components/TextArea.vue";
</script>
<style scoped></style>
