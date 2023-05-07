<template>
    <div class="flex flex-col items-center space-y-1 p-4">
        <!--        <div class="px-4 text-2xl font-medium">School Schedule</div>-->

        <div class="flex h-full w-full justify-between rounded-lg shadow-md">
            <div
                class="flex h-full w-4/12 flex-col items-center justify-evenly rounded-l-lg bg-black text-white"
            >
                <ChevronUpIcon
                    class="w-9 cursor-pointer opacity-20 hover:opacity-100"
                    @click="changeDate('up')"
                />
                <div class="text-6xl font-bold">
                    {{ moment(selectedDate).format("ddd") }}
                </div>
                <div>{{ moment(selectedDate).format("MMMM D YYYY") }}</div>
                <ChevronDownIcon
                    class="w-9 cursor-pointer opacity-20 hover:opacity-100"
                    @click="changeDate('down')"
                />
            </div>

            <div class="flex w-7/12 flex-col justify-evenly space-y-1">
                <div v-for="(item, index) in schoolSchedule" :key="index">
                    <div class="w-full text-sm">
                        <span class="font-bold">-</span> {{ item.title }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { router, usePage } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";
import moment from "moment";
import { ChevronDownIcon, ChevronUpIcon } from "@heroicons/vue/20/solid";

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

watch(selectedDate, (newValue, oldValue) => {
    const formattedDate = moment(newValue).format("YYYY-MM-DD");
    router.visit("/teacher?school_schedule_date=" + formattedDate, {
        only: ["school_schedule"],
        preserveState: true,
    });
});
</script>

<style scoped></style>
