<template>
    <div class="flex w-full flex-col items-center space-y-4 bg-white p-5">
        <div class="flex w-full gap-3">
            <div class="flex w-5/12 flex-col">
                <div class="text-xl font-semibold lg:text-2xl">
                    {{ parseLevel(level.name) }}
                </div>
                <h3 class="text-sm text-gray-500">{{ schoolYear.name }}</h3>
            </div>

            <!--            <LevelStatistics />-->

            <SelectInput
                v-model="selectedBatch"
                placeholder="Select Section"
                class="w-5/12"
                :options="batchOptions"
            />
        </div>

        <TabElement v-model:active="activeTab" :tabs="tabs">
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
import { computed, ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import { parseLevel } from "@/utils.js";
import TabElement from "@/Components/TabElement.vue";
import LevelSections from "@/Views/Admin/Levels/LevelSections.vue";
import LevelSubjects from "@/Views/Admin/Levels/LevelSubjects.vue";
import LevelStudents from "@/Views/Admin/Levels/LevelStudents.vue";
import SelectInput from "@/Components/SelectInput.vue";

const level = computed(() => usePage().props.level);
const schoolYear = computed(() => usePage().props.school_year);
const tabs = ["Sections", "Subjects", "Students"];
const activeTab = ref("Sections");

const batches = computed(() => usePage().props.batches);
const batchOptions = computed(() => {
    return batches.value.map((batch) => {
        console.log(batch);
        return {
            value: batch.id,
            label: `Section ${batch.section}`,
        };
    });
});
const selectedBatch = ref(batchOptions.value[0].value);
</script>
