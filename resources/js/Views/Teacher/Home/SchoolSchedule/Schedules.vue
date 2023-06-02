<template>
    <div
        class="flex w-full flex-col items-center justify-center rounded-b-md px-2 pl-3 pb-2 shadow-md"
    >
        <div class="flex w-full flex-col justify-evenly py-2">
            <div v-for="(item, index) in schoolSchedule" :key="index">
                <div
                    class="my-1 flex h-8 w-full items-center justify-center space-x-1 font-light"
                >
                    <CalendarDaysIcon class="w-3 lg:w-3.5" />
                    <div class="text-xs 2xl:text-sm">
                        {{ item.title }}
                    </div>
                </div>
            </div>

            <div
                v-if="schoolSchedule.length < 1"
                class="flex h-full flex-col items-center justify-center space-y-2 text-xs 2xl:text-sm"
            >
                <ExclamationTriangleIcon class="h-6 w-6 text-gray-500" />
                <div>No Schedule found for today!</div>
            </div>
        </div>
        <div
            class="flex h-9 w-8/12 items-center justify-center rounded-3xl bg-zinc-800 lg:w-9/12"
        >
            <SecondaryButton title="View All Schedules" class="text-white" />
        </div>
    </div>
</template>
<script setup>
import { ExclamationTriangleIcon } from "@heroicons/vue/24/outline";
import { CalendarDaysIcon } from "@heroicons/vue/20/solid";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const schoolSchedule = computed(() => usePage().props.school_schedule);
</script>
<style scoped></style>
