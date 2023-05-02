<template>
    <div class="mx-auto grid w-full grid-cols-12">

        <div class="col-span-12 flex h-fit p-2 md:col-span-10">

            <div class="flex w-fit items-center lg:gap-5">
                <div class="mr-2 w-fit lg:w-full">
                    <img
                        :src="`https://xsgames.co/randomusers/avatar.php?g=${teacher.user.gender}`"
                        alt="avatar"
                        class="mx-auto aspect-square w-full rounded-full md:w-3/4"
                    />
                </div>


                <div class="mx-auto flex w-fit flex-col items-center">
                    <Heading class="!font-semibold" size="lg">{{ teacher.user.name }}</Heading>
                    <div class="flex w-full lg:gap-2">
                        <h3 class="max-w-full text-gray-500" size="">{{ teacher.user.email }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex grid-cols-5 flex-col gap-3 lg:grid">
        <div
            v-for="(day, index) in days"
            :key="index"
            class="flex flex-col"
        >
            <h3 class="mb-2 w-full text-center text-xl">{{ day.name }}</h3>
            <div
                v-for="(item, index) in sortedAndFormattedTeacherPeriods"
                :key="index"
            >
                <Card
                    v-show="item && item.day_of_week && item.day_of_week === day.key"
                    class="mb-2 min-w-full"
                    :subtitle="'Period ' + item.school_period.name"
                    :title="item.batch_subject.batch.level.name + item.batch_subject.batch.section"
                >
                    <div class="font-medium">
                        {{ item.formattedStartTime }} - {{ item.formattedEndTime }}
                    </div>
                    <div>{{ item.batch_subject.subject.full_name }}</div>
                </Card>
            </div>
        </div>
    </div>

    <div>
        <Heading size="lg">Subjects</Heading>
        <div class="flex w-full flex-wrap items-center space-x-1.5">
            <div
                v-for="(item, index) in uniqueSubjectsAndLevels" :key="index"
                class="m-0.5 mt-3 w-full lg:w-1/4">
                <Card :title="item.subject" :subtitle="item.levels" class="min-w-full"/>
            </div>

        </div>
    </div>

    <div>
        <Heading size="lg">Homerooms</Heading>
        <div
            class="flex h-full w-full flex-col flex-wrap space-y-3 md:flex-row md:space-y-0">
            <div
                v-for="(item, index) in teacher.homeroom" :key="index"
                class="w-full"
                :class=" teacher.homeroom.length > 3 ? 'md:w-1/4':'md:w-1/2 lg:w-1/3'">
                <Card :title="item.batch.level.name + item.batch.section" subtitle="Class" :icon="true">
                    <template #icon>
                        <AcademicCapIcon/>
                    </template>
                </Card>
            </div>
        </div>
    </div>

    <div class="min-h-screen">
        <Heading class="my-2">Feedbacks</Heading>
        <div class="space-y-4 rounded-md p-2 xl:grid xl:grid-cols-3 xl:gap-3 xl:space-y-0">
            <div
                v-for="(feedback, index) in teacher.feedbacks"
                :key="feedback.id"
                class="rounded-md p-4 shadow-md"
            >
                <div
                    class="border-l-4 pl-4"
                    :class="{
          'border-blue-500': index % 2 === 0,
          'border-green-500': index % 2 === 1,
        }"
                >
                    <p class="text-sm text-gray-600">{{ feedback.feedback }}</p>
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                        <span class="text-xs">Submitted by: {{ feedback.author.name }}</span>
                        <span class="ml-auto text-xs">{{ moment(feedback.updated_at).fromNow() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {usePage} from "@inertiajs/vue3";
import Heading from "@/Components/Heading.vue";
import Card from "@/Components/Card.vue";
import {AcademicCapIcon} from "@heroicons/vue/20/solid";
import {computed} from "vue";
import moment from "moment";

const teacher = usePage().props.teacher;
const schedule = usePage().props.batchSchedules;
const days = [
    {name: 'Monday', key: 'monday'},
    {name: 'Tuesday', key: 'tuesday'},
    {name: 'Wednesday', key: 'wednesday'},
    {name: 'Thursday', key: 'thursday'},
    {name: 'Friday', key: 'friday'},
];

const uniqueSubjectsAndLevels = computed(() => {
    const subjectsMap = new Map();

    teacher.batch_subjects.forEach(subject => {
        const batchSubject = subject;
        const subjectId = batchSubject.subject.id;
        const subjectName = batchSubject.subject.full_name;
        const level = batchSubject.batch.level.name;

        if (subjectsMap.has(subjectId)) {
            subjectsMap.get(subjectId).levels.add(level);
        } else {
            subjectsMap.set(subjectId, {
                subject: subjectName,
                levels: new Set([level]),
            });
        }
    });

    return Array.from(subjectsMap.values()).map(subjectAndLevels => {
        const sortedLevels = Array.from(subjectAndLevels.levels)
            .sort((a, b) => a - b);

        const levelsString = sortedLevels.length > 1
            ? sortedLevels.slice(0, -1).join(', ') + ' and ' + sortedLevels.slice(-1)
            : sortedLevels.join(', ');

        return {
            ...subjectAndLevels,
            levels: 'Grade ' + levelsString,
        };
    });
});


const sortedAndFormattedTeacherPeriods = computed(() => {
    const daysOfWeek = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

    return teacher.batch_schedules.slice()
        .sort((a, b) => {
            const dayComparison = daysOfWeek.indexOf(a.day_of_week) - daysOfWeek.indexOf(b.day_of_week);
            return dayComparison === 0 ? parseInt(a.school_period.name) - parseInt(b.school_period.name) : dayComparison;
        })
        .map((item) => {
            const startTime = moment(item.school_period.start_time, 'HH:mm:ss');
            const endTime = startTime.clone().add(item.school_period.duration, 'minutes');
            return {
                ...item,
                formattedStartTime: startTime.format('h:mm A'),
                formattedEndTime: endTime.format('h:mm A'),
            };
        });
});


</script>

<style scoped>

</style>
