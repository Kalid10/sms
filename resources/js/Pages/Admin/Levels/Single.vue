<template>
    <div class="flex w-full flex-col items-center space-y-4 bg-white p-5">
        <div class="flex w-full flex-col gap-3">
            <div class="flex w-full flex-col">
                <div class="text-xl font-semibold lg:text-2xl">
                    {{ parseLevel(level.name) }}
                </div>
                <h3 class="text-sm text-gray-500">{{ schoolYear.name }}</h3>
            </div>

            <!--            <LevelStatistics />-->
        </div>

        <TabElement :tabs="models">
            <template #sections>
                <LevelSections />
            </template>

            <template #subjects>
                <div class="flex w-10/12">
                    <LevelSubjects />
                </div>
            </template>

            <template #students>
                <div class="flex w-10/12">
                    <LevelStudents />
                </div>
            </template>
        </TabElement>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import { parseLevel } from "@/utils.js";
import TabElement from "@/Components/TabElement.vue";
import LevelSections from "@/Views/Admin/Levels/LevelSections.vue";
import LevelSubjects from "@/Views/Admin/Levels/LevelSubjects.vue";
import LevelStudents from "@/Views/Admin/Levels/LevelStudents.vue";

const level = computed(() => usePage().props.level);
const schoolYear = computed(() => usePage().props.school_year);

const models = ["Sections", "Subjects", "Students"];
</script>
