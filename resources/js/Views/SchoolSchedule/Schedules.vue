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
                <CalendarDaysIcon class="w-3 lg:w-3.5" />
                <div class="text-xs 2xl:text-sm">
                    {{ item.title }}
                </div>
            </div>

            <EmptyView
                v-if="schoolSchedule.length < 1"
                title="No Schedule found for today!"
            />
        </div>
        <div
            class="flex h-9 w-8/12 items-center justify-center rounded-3xl bg-zinc-800 lg:w-9/12"
        >
            <SecondaryButton
                title="View All Schedules"
                class="text-white"
                @click="router.get('/school-schedules')"
            />
        </div>
    </div>
</template>
<script setup>
import { CalendarDaysIcon } from "@heroicons/vue/20/solid";
import { computed } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import EmptyView from "@/Views/EmptyView.vue";

const schoolSchedule = computed(() => usePage().props.school_schedule);
</script>
<style scoped></style>
