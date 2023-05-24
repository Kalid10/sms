<template>
    <div class="flex w-full flex-col space-y-5">
        <div class="flex flex-col space-y-3">
            <h2 class="text-xl font-medium leading-7 text-gray-900">
                Schedule real time display
            </h2>

            <h3
                v-if="schoolPeriods.length < 1"
                class="text-xs font-light text-gray-900"
            >
                No periods found, enter the number (amount) of periods to
                display in real time.
            </h3>
        </div>

        <div class="my-2 flex w-full flex-wrap space-x-2.5">
            <div class="rounded-md py-2">
                <span
                    class="inline-flex select-none flex-wrap items-center gap-2 text-sm"
                >
                    <span
                        class="flex items-center gap-1 whitespace-nowrap rounded-xl border border-black px-2 font-semibold"
                    >
                        <XCircleIcon
                            class="h-4 w-4 cursor-pointer fill-black"
                        />
                        <span class="">asdfk</span>
                    </span>
                </span>
            </div>
        </div>

        <div class="grid w-full grid-cols-5 place-items-center align-middle">
            <div
                v-for="(day, index) in classScheduleWithCustomPeriods"
                :key="index"
                class="w-full text-xs"
                :class="index === 0 ? 'border-x' : 'border-r'"
            >
                <h2
                    class="mb-5 text-center text-sm font-semibold underline underline-offset-4"
                >
                    {{ day.name }}
                </h2>
                <div
                    v-for="(period, periodIndex) in day.periods"
                    :key="periodIndex"
                    class="mt-4 w-full rounded-sm border-b py-1.5 px-2 text-center text-xs"
                >
                    <h3 class="mb-1 font-semibold">
                        {{ period.start }} - {{ period.end }}
                    </h3>
                    <p class="text-[0.65rem]">Period {{ periodIndex + 1 }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const props = defineProps({
    schoolPeriods: {
        type: Array,
        required: true,
    },
});

const levelCategories = computed(() => usePage().props.level_categories || []);

const weekdays = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];

const weekdaysClassSchedule = computed(() => {
    return weekdays.map((weekday, index) => {
        let periods = [];
        let currentTime = new Date(
            `1970-01-01T${props.schoolPeriods.start_time}`
        );
        for (let i = 0; i < props.schoolPeriods.no_of_periods; i++) {
            let endTime = new Date(
                currentTime.getTime() +
                    props.schoolPeriods.minutes_per_period * 60000
            );
            periods.push({
                start: currentTime.toTimeString().substring(0, 5),
                end: endTime.toTimeString().substring(0, 5),
            });
            currentTime = endTime;
        }
        return {
            name: weekday,
            periods,
        };
    });
});

const classScheduleWithCustomPeriods = computed(() => {
    let classSchedule = [...weekdaysClassSchedule.value];
    for (let day of classSchedule) {
        let periods = [];
        for (let i = 0; i < day.periods.length; i++) {
            // Get custom periods that should come before this period
            let customPeriods = props.schoolPeriods.custom_periods.filter(
                (p) => p.before_period === i + 1
            );

            // Convert custom periods to period format and insert them before this period
            for (let cp of customPeriods) {
                let start =
                    periods.length > 0
                        ? periods[periods.length - 1].end
                        : day.periods[0].start;
                let end = addMinutes(start, cp.duration);
                periods.push({
                    start,
                    end,
                    name: cp.name,
                });
            }
            // Insert this period
            periods.push(day.periods[i]);
        }
        day.periods = periods;
    }
    return classSchedule;
});
</script>

<style scoped></style>
