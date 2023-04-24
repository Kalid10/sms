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
                    <heading class="!font-semibold" size="lg">{{ teacher.user.name }}</heading>
                    <div class="flex w-full lg:gap-2">
                        <h3 class="max-w-full text-gray-500" size="">{{ teacher.user.email }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-red-400">
        <heading size="lg">Class Schedule</heading>
    </div>

    <div>
        <heading size="lg">Subjects</heading>
        <div class="flex w-full flex-wrap items-center justify-evenly">
            <div
                v-for="(item, index) in uniqueSubjectsAndLevels" :key="index"
                class="m-0.5 mt-3 w-1/4">
                <card :title="item.subject" :subtitle="item.levels" class="min-w-full">

                </card>
            </div>

        </div>
    </div>

    <div>
        <heading size="lg">Homerooms</heading>
        <div
            class="flex h-full w-full flex-col flex-wrap items-center space-y-3 md:flex-row md:space-y-0 lg:justify-center">
            <div
                v-for="(item, index) in teacher.homeroom" :key="index"
                class="w-11/12"
                :class=" teacher.homeroom.length > 3 ? 'md:w-1/4':'md:w-1/2 lg:w-1/3'">
                <card :title="item.batch.level.name + item.batch.section" subtitle="Class" icon="true">
                    <template #icon>
                        <AcademicCapIcon/>
                    </template>
                </card>
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

const teacher = usePage().props.teacher;

const uniqueSubjectsAndLevels = computed(() => {
    const subjectsMap = new Map();

    teacher.batch_subjects.forEach(item => {
        const subjectId = item.subject.id;
        const subjectName = item.subject.full_name;
        const level = item.batch.level.name;

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


</script>

<style scoped>

</style>
