<template>
    <header
        class="sticky top-0 z-10 flex h-14 min-h-[3.5rem] w-full border-b bg-white"
    >
        <div class="h-full w-0 lg:min-w-[16rem]"></div>

        <div class="flex grow items-center px-4">
            <div class="flex items-center gap-4">
                <ChevronLeftIcon
                    class="h-3 w-3 stroke-gray-500 stroke-[3] transition duration-150 hover:scale-125 hover:stroke-black"
                    @click="previousMonth"
                />
                <ChevronRightIcon
                    class="h-3 w-3 stroke-gray-500 stroke-[3] transition duration-150 hover:scale-125 hover:stroke-black"
                    @click="nextMonth"
                />
                <div class="select-none text-base font-semibold capitalize">
                    {{ months[selectedMonth] }} {{ selectedYear }}
                </div>
            </div>
        </div>
    </header>

    <div class="flex max-h-full w-full grow">
        <aside class="h-full w-0 lg:min-w-[16rem] lg:border-r"></aside>

        <div class="flex w-full flex-col">
            <div
                class="days sticky top-[3.5rem] z-10 grid h-8 min-h-[2rem] w-full grid-cols-7 border-b bg-white"
            >
                <label
                    v-for="(day, d) in days"
                    :key="d"
                    class="hidden place-items-center text-xs font-semibold text-gray-500 lg:grid"
                >
                    {{ day }}
                </label>

                <label
                    v-for="(day, d) in shortDays"
                    :key="d"
                    class="grid place-items-center text-xs font-semibold uppercase text-gray-500 lg:hidden"
                >
                    {{ day }}
                </label>
            </div>

            <div
                class="calendar grid grow grid-flow-row auto-rows-fr grid-cols-7"
            >
                <div
                    v-for="o in offsetDays"
                    :key="o"
                    class="col-span-1 row-span-1 flex cursor-auto flex-col items-center border-b"
                >
                    <div
                        class="w-full p-2 text-left text-xs font-semibold text-gray-500"
                    >
                        {{ numberOfPreviousDays - offsetDays + o }}
                    </div>
                </div>

                <div
                    v-for="day in numberOfDays"
                    :key="day"
                    class="col-span-1 row-span-1 flex max-h-fit cursor-auto flex-col items-center border-b"
                    @click="
                        $emit(
                            'select',
                            new Date(selectedYear, selectedMonth, day)
                        )
                    "
                >
                    <div
                        class="w-full p-2 text-left text-xs font-semibold text-gray-500"
                    >
                        {{ day }}
                    </div>
                    <div
                        v-if="schedules"
                        class="flex w-full grow flex-wrap md:flex-col"
                    >
                        <div
                            v-for="(event, e) in getDaySchedule(day)"
                            :key="e"
                            class="event group relative flex h-fit w-fit cursor-pointer flex-col items-end p-1 md:h-auto md:w-full lg:px-2"
                        >
                            <div
                                class="flex w-full items-center justify-between gap-2"
                            >
                                <div
                                    class="flex items-baseline gap-2 overflow-hidden"
                                >
                                    <span
                                        :class="colors[event.type]"
                                        class="min-h-[0.5rem] min-w-[0.5rem] rounded-full"
                                    />
                                    <p
                                        class="hidden truncate whitespace-nowrap text-xs font-medium md:inline"
                                    >
                                        {{ event.title }}
                                    </p>
                                </div>

                                <div
                                    class="hidden overflow-hidden md:flex lg:min-w-fit"
                                >
                                    <p
                                        class="truncate whitespace-nowrap text-xs text-gray-500"
                                    >
                                        All-Day
                                    </p>
                                </div>
                            </div>
                            <div
                                class="event-detail hidden origin-right scale-[.7] items-center gap-1 lg:flex"
                            >
                                <div
                                    class="grid h-5 w-5 place-items-center rounded-full border border-black text-xs font-medium leading-none"
                                >
                                    G
                                </div>

                                <div
                                    class="grid h-5 w-5 place-items-center rounded-full border border-black text-xs font-medium leading-none"
                                >
                                    T
                                </div>

                                <div
                                    class="grid h-5 w-5 place-items-center rounded-full border border-black text-xs font-medium leading-none"
                                >
                                    S
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    v-for="k in 7 - ((offsetDays + numberOfDays) % 7)"
                    :key="k"
                    class="col-span-1 row-span-1 flex cursor-auto flex-col items-center border-b"
                >
                    <div
                        class="w-full p-2 text-left text-xs font-semibold text-gray-500"
                    >
                        {{ k }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted, ref } from "vue";
import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/vue/24/outline";
import moment from "moment";
import { router } from "@inertiajs/vue3";

const emits = defineEmits(["select"]);

const days = [
    "Sunday",
    "Monday",
    "Tuesday",
    "Wednesday",
    "Thursday",
    "Friday",
    "Saturday",
];

const shortDays = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

const months = [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December",
];

const colors = {
    holiday: "bg-red-500",
    event: "bg-green-500",
};

const today = new Date();
const selectedYear = ref(today.getFullYear());
const selectedMonth = ref(today.getMonth());

const offsetDays = computed(() =>
    new Date(selectedYear.value, selectedMonth.value).getDay()
);
const numberOfPreviousDays = computed(
    () =>
        35 -
        new Date(
            selectedMonth.value === 0
                ? selectedYear.value - 1
                : selectedYear.value,
            selectedMonth.value - 1 > -1 ? selectedMonth.value - 1 : 11,
            35
        ).getDate()
);
const numberOfDays = computed(
    () => 35 - new Date(selectedYear.value, selectedMonth.value, 35).getDate()
);

function previousMonth() {
    if (selectedMonth.value === 0) {
        selectedMonth.value = 11;
        selectedYear.value--;
    } else {
        selectedMonth.value--;
    }
}

function nextMonth() {
    if (selectedMonth.value === 11) {
        selectedMonth.value = 0;
        selectedYear.value++;
    } else {
        selectedMonth.value++;
    }
}

const schedules = ref(null);
onMounted(() => {
    router.reload({
        only: ["school_schedule"],
        onSuccess(data) {
            schedules.value = data.props.schedules;
        },
    });
});

function getDaySchedule(day) {
    return schedules.value.filter((schedule) => {
        return moment(
            `${selectedYear.value}-${selectedMonth.value + 1}-${day}`
        ).isBetween(schedule.start_date, schedule.end_date);
    });
}
</script>

<style scoped>
.calendar > div:not(:nth-child(7n)) {
    @apply border-r;
}

.days > label:not(:nth-child(7n)) {
    @apply border-r;
}

.event-detail {
    max-height: 0;
    overflow: hidden;
}

.event:hover .event-detail {
    max-height: 100rem;
}
</style>
