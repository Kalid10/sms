<template>

    <div class="flex flex-col gap-3">

        <div class="flex w-full flex-col">
            <h3 class="font-semibold">{{ parseLevel(level.name) }}</h3>
            <h3 class="text-sm text-gray-500">{{ activeSchoolYear }}</h3>
        </div>

        <LevelStatistics />

    </div>

    <TabElement :tabs="models">

        <template #sections>

            <LevelSections />

        </template>

        <template #subjects>

            <LevelSubjects />

        </template>

        <template #students>

            <LevelStudents />

        </template>

    </TabElement>

</template>

<script setup>
import {computed} from "vue";
import {usePage} from "@inertiajs/vue3";
import LevelStatistics from "@/Views/LevelStatistics.vue";
import {parseLevel} from "@/utils.js";
import TabElement from "@/Components/TabElement.vue";
import LevelSections from "@/Views/Levels/LevelSections.vue"
import LevelSubjects from "@/Views/Levels/LevelSubjects.vue"
import LevelStudents from "@/Views/Levels/LevelStudents.vue"
const level = computed(() => usePage().props.level);
const activeSchoolYear = computed(() => usePage().props.level.batches[0]?.school_year.name)

const models = [
    'Sections',
    'Subjects',
    'Students',
]

</script>
