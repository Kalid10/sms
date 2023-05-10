<template>
    <div
        class="flex w-full flex-col items-center justify-center rounded-b-md px-2 pl-3 shadow-md"
    >
        <div class="flex flex-col justify-evenly pt-2">
            <div v-for="(item, index) in schoolSchedule" :key="index">
                <div
                    class="flex h-11 w-full items-center justify-center space-x-1 font-light"
                >
                    <CalendarDaysIcon class="w-3 lg:w-3.5" />
                    <div class="text-xs lg:text-sm">
                        {{ item.title }}
                    </div>
                </div>
            </div>

            <div
                v-if="schoolSchedule.length < 1"
                class="flex h-full flex-col items-center justify-center space-y-2 text-xs lg:text-sm"
            >
                <ExclamationTriangleIcon class="h-6 w-6 text-gray-500" />
                <div>No Schedule found for today!</div>
            </div>
        </div>
        <div class="flex h-14 w-10/12 items-center justify-center lg:w-full">
            <SecondaryButton
                title="View All Schedules"
                class="w-11/12 bg-neutral-800 text-white"
            />
        </div>
    </div>
</template>
<script setup>
import { ExclamationTriangleIcon } from "@heroicons/vue/24/outline";
import { CalendarDaysIcon } from "@heroicons/vue/20/solid";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const schoolSchedule = computed(() => usePage().props.school_schedule);
</script>
<style scoped></style>
