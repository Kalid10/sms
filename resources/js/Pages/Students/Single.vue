<template>

    <div class="mx-auto grid w-full grid-cols-12 gap-4">

        <div class="col-span-12 flex h-full flex-col p-2 md:col-span-2">

            <div class="flex flex-col items-center gap-5">

                <div class="flex w-full flex-col gap-3">

                    <img
                        :src="`https://xsgames.co/randomusers/avatar.php?g=${student.user.gender}`"
                        alt="avatar"
                        class="mx-auto aspect-square w-16 rounded-full md:w-3/4"
                    />

                    <div class="mx-auto flex w-fit flex-col items-center">
                        <Heading class="!font-semibold" size="lg">{{ student.user.name }}</Heading>
                        <div class="flex w-full min-w-[28px] justify-start gap-2 leading-none">
<!--                            <h3 class="text-gray-500" size="">12B</h3>-->
                        </div>
                    </div>


                </div>

            </div>

        </div>

        <div class="col-span-12 flex h-full flex-col gap-6 overflow-auto p-2 md:col-span-10">

            <StudentOverview />

            <div class="flex w-full gap-4">
                <Card class="!max-w-xs" :title="`Notes about ${student.user.name.split(' ').slice(-1)}`" icon>

                    <ul class="flex flex-col gap-4">
                        <li v-for="(note, n) in notes" :key="n">
                            <h3 class="text-sm">{{ note.title }}</h3>
                            <h3 class="note-preview text-sm text-gray-500">
                                {{ note.body }}
                            </h3>
                            <div class="mt-1 flex w-full gap-2 text-xs">
                                <div class="flex items-center gap-1">
                                    <div :class="note.party === 'parent' ? 'bg-yellow-600' : 'bg-green-600'" class="h-2.5 w-2.5 rounded-full"/>
                                    <h3 class="">{{ note.author }}</h3>
                                </div>
                                <h3 class="italic text-gray-500">{{ note.date }}</h3>
                            </div>
                        </li>
                    </ul>

                    <TertiaryButton>View All</TertiaryButton>

                </Card>

                <div class="flex grow flex-col justify-between gap-3">

                    <h3 class="text-sm font-semibold">Class Schedule</h3>

                    <div class="flex h-full w-full rounded-md border">

                        <div class="hours grid grid-cols-1 flex-col">

                            <div class="border-b"></div>
                            <div v-for="(period, p) in periods" :key="p" class="grid place-items-center border-r border-b px-2 text-xs font-semibold text-gray-500 last:border-b-0">{{ period['start_time'] }}</div>

                        </div>

                        <div class="days grid w-full">
                            <div class="row-span-1 grid h-full w-full grid-cols-5 grid-rows-1">
                                <div
                                    v-for="(day, d) in ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']"
                                    :key="d"
                                    class="grid place-items-center border-b border-r text-xs font-semibold text-gray-500 last:border-r-0"
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
                                                'bg-brand-50/15 text-brand-100 col-span-5 border-r-0' :
                                                'col-span-1 session'"
                                            class="row-span-1 flex items-center justify-center border-r border-b text-sm font-semibold"
                                        >
                                            {{ getPeriodName(schedule) }}
                                        </div>
                                    </template>

                                </div>

                            </div>
                        </div>

                    </div>

<!--                    <div class="sessions grid grow grid-flow-col-dense grid-cols-5 rounded-md border">-->

<!--                        <template v-for="(schedule, s) in schedules" :key="s">-->
<!--                            <div-->
<!--                                v-if="! (schedule['day_of_week'] !== 'monday' && schedule['batch_subject'] === null)"-->
<!--                                :class="schedule['school_period']['is_custom'] ?-->
<!--                                    'bg-brand-50/15 text-brand-100 col-span-5 border-r-0' :-->
<!--                                    'col-span-1 session'"-->
<!--                                class="row-span-1 flex items-center justify-center border-r border-b text-sm font-semibold"-->
<!--                            >-->
<!--                                {{ getPeriodName(schedule) }}-->
<!--                            </div>-->
<!--                        </template>-->

<!--                    </div>-->

                </div>

            </div>


        </div>

    </div>

</template>

<script setup>
import {computed} from "vue"
import {notes} from "@/fake.js"
import {usePage} from "@inertiajs/vue3";
import Heading from "@/Components/Heading.vue";
import Card from "@/Components/Card.vue"
import StudentOverview from "@/Views/Students/StudentOverview.vue"
import TertiaryButton from "@/Components/TertiaryButton.vue";

const student = computed(() => usePage().props.student)
const schedules = computed(() => usePage().props.schedule)
const periods = computed(() => usePage().props.periods)
const periodsCount = computed(() => periods.value.length)

function getPeriodName(schedule) {
    return schedule['school_period']['is_custom'] ? schedule['school_period']['name'] : schedule['batch_subject']['subject']['full_name']
}

const subjects = [
    'Mathematics',
    'English',
    'Science',
    'History',
    'Geography',
    'Art',
    'Music',
    'Drama',
    'Physical Education',
    'Religious Education',
    'French',
    'Spanish',
    'German',
    'Latin',
    'Mandarin',
    'Japanese',
    'Italian',
    'Russian',
    'Arabic',
    'Hindi',
    'Urdu',
    'Bengali',
    'Punjabi',
]

const tabs = [
    'Overview',
    'Personal Details',
    'Guardian',
    'Attendance',
    'Classes',
    'Notes'
]

</script>

<style scoped>
.note-preview {
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
}

.sessions {
    grid-template-rows: repeat(v-bind(periodsCount), minmax(0, 1fr));
}

.hours {
    grid-template-rows: repeat(v-bind((periodsCount + 1)), minmax(0, 1fr));
}

.days {
    grid-template-rows: repeat(v-bind((periodsCount + 1)), minmax(0, 1fr));
}

.subjects {
    grid-row-start: 2;
    grid-row-end: v-bind(periodsCount + 2);
}

.session {
    @apply border-r;
}

.sessions > .session:nth-last-child(-n + v-bind(periodsCount)) {
    @apply border-r-0;
}

.day {
    height: calc(100% * (1 / v-bind(periodsCount + 1)));
}

/*.sessions > .session:nth-child(9n) {*/
/*    @apply border-b-0;*/
/*}*/
</style>
