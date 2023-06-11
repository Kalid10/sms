<template>
    <div
        class="flex h-fit w-full items-center justify-evenly rounded-t-lg bg-black py-3 text-white"
    >
        <div class="flex w-1/12 items-center justify-center">
            <ChevronLeftIcon
                class="w-9 cursor-pointer opacity-20 hover:opacity-100"
                @click="changeDate('down')"
            />
        </div>

        <div
            class="flex h-full w-5/12 items-end justify-center space-x-2.5 lg:w-8/12"
        >
            <div class="text-4xl font-bold 2xl:text-4xl">
                {{ moment(selectedDate).format("dddd") }}
            </div>
            <div
                :class="
                    isSidebarOpenOnXlDevice
                        ? 'text-xs bg-white font-light lg:bg-yellow-500 2xl:text-sm'
                        : 'text-xl font-light 2xl:text-2xl'
                "
            >
                {{ moment(selectedDate).format("MMMM D") }}
                {{ moment(selectedDate).format("YYYY") }}
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
import { isSidebarOpenOnXlDevice } from "@/utils";

const selectedDate = ref(usePage().props.school_schedule_date);
const props = defineProps({
    scheduleUrl: {
        type: String,
        default: "/teacher?school_schedule_date=",
    },
});

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
    router.visit(props.scheduleUrl + formattedDate, {
        only: ["school_schedule"],
        preserveState: true,
    });
});
</script>
<style scoped></style>
