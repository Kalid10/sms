<template>
    <div
        v-if="allSchedules.length > 0"
        class="flex w-full flex-col justify-between rounded-lg border-2 px-2 py-5"
    >
        <div class="flex w-full">
            <div
                class="flex w-1/12 items-center justify-center rounded-lg text-sm font-semibold"
            >
                <span class="text-xs font-semibold">Period</span>
            </div>
            <div class="flex w-full gap-2 rounded-lg bg-brand-150">
                <div
                    v-for="(period, p) in schoolPeriodNames"
                    :key="p"
                    class="flex w-1/12 justify-center rounded-lg border-l-2 border-zinc-300 p-2"
                >
                    <span v-if="period <= 20" class="text-xs font-semibold">
                        {{ numberWithOrdinal(period) }}
                    </span>
                    <span v-else class="text-xs font-semibold"
                        >{{ period }}
                    </span>
                </div>
            </div>
        </div>
        <div
            v-for="(day, index) in weekDays"
            :key="index"
            class="flex w-full space-y-6"
        >
            <div
                class="flex w-1/12 items-center justify-center text-sm font-semibold"
            >
                {{ day }}
            </div>

            <div class="flex w-full gap-2">
                <div
                    v-for="(schedule, s) in getDaySchedules(day)"
                    :key="s"
                    class="mb-8 flex w-1/12 justify-center rounded-lg border bg-purple-50 p-2 transition duration-300"
                >
                    <div class="flex flex-col items-center justify-center">
                        <span
                            class="text-xs font-medium transition-all duration-500"
                        >
                            {{ getPeriodName(schedule) }}
                        </span>
                        <span
                            class="text-xs font-light transition duration-500"
                        >
                            {{ getStartTimes(schedule) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <EmptyView v-else title="No schedules found" />
</template>

<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import EmptyView from "@/Views/EmptyView.vue";
import { numberWithOrdinal } from "@/utils";

const schedules = computed(() => usePage().props.schedules);

const subjects = computed(() =>
    schedules.value.map((schedule) => schedule.batch_subject?.subject)
);

const weekDays = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];

const schoolPeriods = computed(() => usePage().props.school_periods);

const schoolPeriodNames = computed(() =>
    schoolPeriods.value.map((period) => period.name)
);

const allSchedules = computed(() => usePage().props.schedules);

const daySchedules = {
    Monday: computed(() =>
        allSchedules.value.filter(
            (schedule) => schedule.day_of_week === "monday"
        )
    ),
    Tuesday: computed(() =>
        allSchedules.value.filter(
            (schedule) => schedule.day_of_week === "tuesday"
        )
    ),
    Wednesday: computed(() =>
        allSchedules.value.filter(
            (schedule) => schedule.day_of_week === "wednesday"
        )
    ),
    Thursday: computed(() =>
        allSchedules.value.filter(
            (schedule) => schedule.day_of_week === "thursday"
        )
    ),
    Friday: computed(() =>
        allSchedules.value.filter(
            (schedule) => schedule.day_of_week === "friday"
        )
    ),
};

function getDaySchedules(day) {
    return daySchedules[day].value;
}

function getPeriodName(schedule) {
    return schedule["school_period"]["is_custom"]
        ? schedule["school_period"]["name"]
        : schedule["batch_subject"]["subject"]["short_name"];
}

function getStartTimes(schedule) {
    return schedule.school_period.start_time ?? null;
}
</script>

<style scoped></style>
