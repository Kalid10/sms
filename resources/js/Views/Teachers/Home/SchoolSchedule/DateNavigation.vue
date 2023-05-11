<template>
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
</template>
<script setup>
import moment from "moment/moment";
import { ref, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/vue/20/solid";

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
