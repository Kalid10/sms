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
            <div class="flex w-full gap-2 rounded-lg bg-zinc-200">
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
                    :class="[
                        swapScheduleASet ? !!schedule.school_period.is_custom ?
                    '' : schedule === swapSchedule.schedule_a ?
                    'border-2 border-dashed border-black' :
                    !!! swapScheduleBSet ?
                    'group cursor-pointer border-2 border-dashed border-purple-300 bg-purple-200 hover:border-purple-400 hover:bg-white' : '' : 'bg-purple-50',
                        swapScheduleBSet ? schedule === swapSchedule.schedule_a || schedule === swapSchedule.schedule_b ?
                        'border-2 border-dashed border-purple-300 bg-purple-200' : '' : ''
                    ]"
                    class="mb-8 flex w-1/12 justify-center rounded-lg border p-2 transition duration-300"
                    @click="setScheduleSwap(schedule)"
                >
                    <div class="flex flex-col items-center justify-center">
                        <span
                            :class="{ '!group-hover:scale-115 group-hover:font-semibold group-hover:text-purple-700': swapScheduleASet && !!!schedule.school_period.is_custom }"
                            class="text-xs font-medium transition-all duration-500"
                        >
                            {{ getPeriodName(schedule) }}
                        </span>
                        <span
                            :class="{ 'group-hover:scale-115 group-hover:font-semibold group-hover:text-purple-700': swapScheduleASet && !!!schedule.school_period.is_custom }"
                            class="text-xs font-light transition duration-500"
                        >
                            {{ getStartTimes(schedule) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <form v-if="swapScheduleBSet && swapScheduleASet" class="flex w-full items-center justify-end px-4">

            <button type="button" class="rounded-md bg-purple-400 px-5 py-2 text-sm font-semibold text-white" @click="attemptSwap">Swap Schedules</button>

        </form>
    </div>
    <EmptyView v-else title="No schedules found" />
</template>

<script setup>
import {computed, ref} from "vue";
import {router, usePage} from "@inertiajs/vue3";
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

const swapSchedule = ref({
    'schedule_a': null,
    'schedule_b': null
})

function setScheduleSwap(schedule) {

    if (schedule.school_period.is_custom) return

    if (swapScheduleASet.value) {
        swapSchedule.value.schedule_b = schedule
    } else {
        swapSchedule.value.schedule_a = schedule
    }

}

const swapScheduleASet = computed(() => !!swapSchedule.value.schedule_a)
const swapScheduleBSet = computed(() => !!swapSchedule.value.schedule_b)

function attemptSwap() {
    router.post('/batch-schedules/swap', {
        schedule_a: swapSchedule.value.schedule_a.id,
        schedule_b: swapSchedule.value.schedule_b.id
    }, {
        preserveState: true,
        onSuccess() {
            swapSchedule.value.schedule_a = null
            swapSchedule.value.schedule_b = null
        },
        onError() {
            alert(JSON.stringify(usePage().props.errors))
        }
    })
}
</script>

<style scoped></style>
