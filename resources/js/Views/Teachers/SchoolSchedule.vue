<template>
    <div class="flex w-full flex-col items-center">
        <!--        <div class="px-4 text-2xl font-medium">School Schedule</div>-->

        <div class="flex h-fit w-full flex-col justify-between rounded-lg">
            <div
                class="flex h-fit w-full items-center justify-evenly rounded-t-lg bg-black py-2.5 text-white"
            >
                <div class="flex w-1/12 items-center justify-center">
                    <ChevronLeftIcon
                        class="w-9 cursor-pointer opacity-20 hover:opacity-100"
                        @click="changeDate('down')"
                    />
                </div>
                <div
                    class="flex h-full w-5/12 items-center justify-center space-x-2.5 lg:w-8/12"
                >
                    <div class="text-4xl font-bold lg:text-5xl">
                        {{ moment(selectedDate).format("ddd") }}
                    </div>
                    <div
                        class="flex h-full flex-col items-center justify-center space-y-1 text-xs font-semibold"
                    >
                        <div class="w-full text-[0.65rem] lg:text-xs">
                            {{ moment(selectedDate).format("MMMM D") }}
                        </div>
                        <div>{{ moment(selectedDate).format("YYYY") }}</div>
                    </div>
                </div>

                <div class="flex w-1/12 items-center justify-center">
                    <ChevronRightIcon
                        class="w-9 cursor-pointer opacity-25 hover:opacity-100"
                        @click="changeDate('up')"
                    />
                </div>
            </div>
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
                        class="flex h-full flex-col items-center justify-center space-y-2"
                    >
                        <ExclamationTriangleIcon
                            class="h-6 w-6 text-gray-500"
                        />
                        <div>No Schedule found for today!</div>
                    </div>
                </div>
                <div
                    class="flex h-14 w-10/12 items-center justify-center lg:w-full"
                >
                    <SecondaryButton
                        title="View All Schedules"
                        class="w-11/12 bg-neutral-800 text-white"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { router, usePage } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";
import moment from "moment";
import {
    CalendarDaysIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
} from "@heroicons/vue/20/solid";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { ExclamationTriangleIcon } from "@heroicons/vue/24/outline";

const schoolSchedule = computed(() => usePage().props.school_schedule);

const selectedDate = ref(usePage().props.school_schedule_date);

const changeDate = (direction) => {
    if (direction === "up") {
        selectedDate.value = moment(selectedDate.value).add(1, "days").toDate();
    } else {
        selectedDate.value = moment(selectedDate.value)
            .subtract(1, "days")
            .toDate();
    }
};

watch(selectedDate, (newValue) => {
    const formattedDate = moment(newValue).format("YYYY-MM-DD");
    router.visit("/teacher?school_schedule_date=" + formattedDate, {
        only: ["school_schedule"],
        preserveState: true,
    });
});
</script>

<style scoped></style>
