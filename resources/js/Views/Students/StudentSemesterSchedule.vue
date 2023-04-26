<template>

    <div class="flex grow flex-col justify-between gap-3">

        <h3 class="font-semibold">{{ student.user.name.split(' ').slice(-1)[0] }}'s Semester Schedule</h3>

        <div class="flex h-full w-full rounded-md border-l border-t">

            <div class="hours grid w-fit grid-cols-1 flex-col">

                <div class="grid w-[5rem] max-w-[5rem] place-items-center whitespace-nowrap border-r border-b px-3 text-xs font-semibold text-gray-500 last:border-b-0"></div>
                <div
                    v-for="(period, p) in periods" :key="p"
                    class="flex w-[5rem] max-w-[5rem] flex-col items-center justify-center whitespace-nowrap border-r border-b px-3 text-xs font-semibold text-gray-500"
                >
                                <span>
                                    {{ period['start_time'] }}
                                </span>
                </div>

            </div>

            <div class="days grid w-[calc(100%-5rem)]">
                <div class="row-span-1 grid h-full grid-cols-5 grid-rows-1">
                    <div
                        v-for="(day, d) in ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']"
                        :key="d"
                        class="grid place-items-center border-b border-r text-xs font-semibold text-gray-500"
                    >
                        {{ day }}
                    </div>
                </div>
                <div class="subjects row-span-auto w-full">

                    <div class="sessions grid h-full grow grid-flow-col-dense grid-cols-5">

                        <template v-for="(schedule, s) in schedules" :key="s">
                            <div
                                v-if="! (schedule['day_of_week'] !== 'monday' && schedule['batch_subject'] === null)"
                                :class="schedule['school_period']['is_custom'] ?
                                                'bg-brand-50/15 text-brand-100 col-span-5' :
                                                'col-span-1 session'"
                                class="row-span-1 flex items-center justify-center border-r border-b text-xs font-semibold"
                            >
                                {{ getPeriodName(schedule) }}
                            </div>
                        </template>

                    </div>

                </div>
            </div>

        </div>

    </div>

</template>

<script setup>
import {computed} from "vue";
import {usePage} from "@inertiajs/vue3";

const student = computed(() => usePage().props.student)
const schedules = computed(() => usePage().props.schedule)
const periods = computed(() => usePage().props.periods)
const periodsCount = computed(() => periods.value.length)
function getPeriodName(schedule) {
    return schedule['school_period']['is_custom'] ?
        schedule['school_period']['name'] :
        schedule['batch_subject']['subject']['full_name']
}
</script>

<style scoped>

.sessions {
    grid-template-rows: repeat(v-bind(periodsCount), minmax(0, 1fr));
}

.hours {
    grid-template-rows: repeat(v-bind(periodsCount + 1), minmax(0, 1fr));
}

.days {
    grid-template-rows: repeat(v-bind(periodsCount + 1), minmax(0, 1fr));
}

.subjects {
    grid-row-start: 2;
    grid-row-end: v-bind(periodsCount + 2);
}

.session {
    @apply border-r;
}

.day {
    height: calc(100% * (1 / v-bind(periodsCount + 1)));
}

</style>
