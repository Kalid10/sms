<template>

    <div class="grid-rows-10 grid grid-cols-5 gap-2">

        <div v-for="(day, d) in ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']" :key="d" class="flex items-center justify-center py-1 text-brand-text-50">
            <Heading size="xs">{{ day }}</Heading>
        </div>

        <div
            v-for="(subject, s) in schedule" :key="s"
            :class="[
                getColumnByDay(subject),
                getRowByName(subject),
                isCustomPeriod(subject['batch_subject'])
            ]"
            class="flex rounded-lg border p-4"
        >
            <div v-if="!!subject['batch_subject']" class="flex flex-col">
                <Heading class="text-brand-text-50" size="sm">{{ subject['batch_subject']['subject']['full_name'] }}</Heading>
                <Heading v-if="!!subject.batch_subject.teacher" class="text-brand-450" size="xs">Teacher {{ subject.batch_subject.teacher?.user?.name }}</Heading>
            </div>
            <div v-else class="flex flex-col">
                <Heading size="sm">{{ subject['school_period']['name'] }}</Heading>
                <div class="flex items-center gap-1">
                    <ClockIcon class="h-3.5 w-3.5 stroke-2" />
                    <Heading class="!font-normal" size="xs">{{ subject['school_period']['duration'] }} minutes</Heading>
                </div>
            </div>
        </div>
    </div>

</template>

<script setup>
import Heading from "@/Components/Heading.vue";
import { ClockIcon } from "@heroicons/vue/24/outline";
import { computed } from "vue";

const props = defineProps({
    batch: {
        type: Object,
        required: true
    }
})

const schedule = computed(() => props.batch.schedule)

function getRowByName(schedule) {
    let style = ''

    style += [
        'row-start-1',
        'row-start-2',
        'row-start-3',
        'row-start-4',
        'row-start-5',
        'row-start-6',
        'row-start-7',
        'row-start-8',
        'row-start-9',
        'row-start-10',
        'row-start-11',
        'row-start-12',
        'row-start-13',
    ][schedule['school_period']['order']];

    return style;
}

function isCustomPeriod(schedule) {
    if (schedule === null) return 'text-gray-500'
    return 'bg-brand-100 border-brand-150 border-brand-200'
}

function getColumnByDay(schedule) {

    let style = '';

    switch (schedule['day_of_week']) {

        case "monday":
            style += 'col-start-1'
            break;
        case "tuesday":
            style += 'col-start-2'
            break;
        case "wednesday":
            style += 'col-start-3'
            break;
        case "thursday":
            style += 'col-start-4'
            break;
        case "friday":
            style += 'col-start-5'
            break;
        default:
            style += ''
            break;
    }

    return style;
}
</script>

<style scoped>

</style>
