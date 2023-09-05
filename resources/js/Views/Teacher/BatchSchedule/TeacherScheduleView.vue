<template>
    <div class="grid-rows-10 grid grid-cols-5 gap-2">
        <div
            v-for="(day, d) in [
                'Monday',
                'Tuesday',
                'Wednesday',
                'Thursday',
                'Friday',
            ]"
            :key="d"
            class="flex items-center justify-center py-1 text-brand-text-50"
        >
            <Heading size="xs">{{ day }}</Heading>
        </div>

        <div
            v-for="(subject, s) in normalizedSchedule"
            :key="s"
            :class="[
                getColumnByDay(subject.day),
                subject.slot &&
                    !!subject.slot['batch_subject'] &&
                    getRowByName(subject.slot, subject.day, s),
                !subject.slot &&
                    getRowByName(
                        null,
                        null,
                        normalizeIndexByDate(s, subject.day)
                    ),
                getStyleForSubject(
                    subject.slot ? subject.slot['batch_subject'] : null
                ),
            ]"
            class="flex rounded-lg border p-2"
        >
            <div v-if="subject.slot" class="flex w-full">
                <div class="flex w-7/12 flex-col">
                    <div class="flex flex-col">
                        <Heading class="text-brand-text-50" size="sm">
                            {{ subject.slot.batch_subject?.subject?.full_name }}
                        </Heading>
                        <Heading
                            v-if="!!subject.slot.batch_subject.teacher"
                            class="text-brand-450"
                            size="xs"
                        >
                            Teacher
                            {{ subject.slot.batch_subject.teacher?.user?.name }}
                        </Heading>
                    </div>
                    <div class="flex flex-col">
                        <Heading size="sm"
                            >{{ addSuffix(subject.slot.school_period?.name) }}
                            period
                        </Heading>
                        <div class="flex items-center gap-1">
                            <ClockIcon class="h-3.5 w-3.5 stroke-2" />
                            <Heading class="!font-normal" size="xs"
                                >{{ subject.slot.school_period?.duration }}
                                minutes
                            </Heading>
                        </div>
                    </div>
                </div>

                <div class="flex w-4/12 flex-col">
                    <Heading size="sm">
                        {{ subject.slot.batch?.level?.level_category?.name }}
                    </Heading>
                    <Heading size="sm">
                        Grade: {{ subject.slot.batch?.level?.name }}
                        {{ subject.slot.batch?.section }}
                    </Heading>
                </div>
            </div>

            <div v-else class="flex h-10 items-center justify-center">
                <Heading size="sm"> No Schedule</Heading>
            </div>
        </div>
    </div>
</template>

<script setup>
import Heading from "@/Components/Heading.vue";
import { ClockIcon } from "@heroicons/vue/24/outline";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import { addSuffix } from "@/utils";

const schedules = computed(() => usePage().props.batch_schedules);
const schoolPeriodCount = computed(() => usePage().props.school_period_count);

function normalizeSchedule(schedule) {
    const normalizedSchedule = [];

    for (let day of ["monday", "tuesday", "wednesday", "thursday", "friday"]) {
        for (let i = 1; i <= schoolPeriodCount.value; i++) {
            const slot =
                schedule.find(
                    (s) =>
                        s["school_period"]["order"] === i &&
                        s["day_of_week"] === day
                ) || null;
            normalizedSchedule.push({ slot, day });
        }
    }

    return normalizedSchedule;
}

const normalizedSchedule = computed(() => normalizeSchedule(schedules.value));

function normalizeIndexByDate(index, date) {
    const dates = ["monday", "tuesday", "wednesday", "thursday", "friday"];

    return index - schoolPeriodCount.value * dates.indexOf(date);
}

function getRowByName(schedule, day, index) {
    let style = "";

    const indexer = !!schedule ? schedule["school_period"]["order"] : index + 1;

    style += [
        "row-start-1",
        "row-start-2",
        "row-start-3",
        "row-start-4",
        "row-start-5",
        "row-start-6",
        "row-start-7",
        "row-start-8",
        "row-start-9",
        "row-start-10",
        "row-start-11",
        "row-start-12",
        "row-start-13",
    ][indexer];

    return style;
}

function getStyleForSubject(batchSubject) {
    return batchSubject
        ? "bg-brand-100 border-brand-150"
        : "bg-gray-50 text-gray-500 border-gray-200 opacity-50";
}

function getColumnByDay(day) {
    let style = "";
    switch (day) {
        case "monday":
            style += "col-start-1";
            break;
        case "tuesday":
            style += "col-start-2";
            break;
        case "wednesday":
            style += "col-start-3";
            break;
        case "thursday":
            style += "col-start-4";
            break;
        case "friday":
            style += "col-start-5";
            break;
        default:
            style += "";
            break;
    }
    return style;
}
</script>

<style scoped></style>
