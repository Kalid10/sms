<template>
    <div
        class="flex w-full flex-col items-center justify-center space-y-2 rounded-b-md bg-white px-2 pl-3 pb-5 shadow-sm"
    >
        <div class="flex w-full flex-col justify-evenly py-2">
            <div
                v-for="(item, index) in schoolSchedule"
                :key="index"
                class="my-1 flex w-full items-center justify-center space-x-2 rounded-lg py-3 font-medium"
                :class="index % 2 === 1 ? 'bg-gray-50' : 'bg-white'"
            >
                <div class="text-xs 2xl:text-xs">- {{ item.title }}</div>
            </div>

            <EmptyView
                v-if="schoolSchedule.length < 1"
                title="No Schedule found for today!"
            />
        </div>
        <LinkCell href="/school-schedules" value="View All Schedules" />
    </div>
</template>
<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import EmptyView from "@/Views/EmptyView.vue";
import LinkCell from "@/Components/LinkCell.vue";

const schoolSchedule = computed(() => usePage().props.school_schedule);
</script>
<style scoped></style>
