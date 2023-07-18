<template>
    <div
        class="flex w-full flex-col items-center space-y-8 bg-white p-3 lg:p-5"
    >
        <div class="flex w-full gap-3">
            <div class="flex w-5/12 flex-col">
                <div class="text-xl font-semibold lg:text-2xl">
                    {{ parseLevel(level.name) }}
                </div>
                <h3 class="text-sm text-brand-text-300">
                    {{ schoolYear.name }}
                </h3>
            </div>

            <SelectInput
                v-model="selectedBatch"
                :placeholder="$t('single.selectSection')"
                class="w-5/12"
                :options="batchOptions"
            />
        </div>

        <TabElement v-model:active="activeTab" :tabs="tabs">
            <template #[sectionsTab]>
                <Section :batch="activeBatch" />
            </template>

            <template #[subjectsTab]>
                <div class="flex w-10/12">
                    <LevelSubjects />
                </div>
            </template>

            <template #[studentTab]>
                <div class="flex w-10/12">
                    <LevelStudents />
                </div>
            </template>
        </TabElement>
    </div>
</template>

<script setup>
import { computed, ref, watch } from "vue";
import { usePage } from "@inertiajs/vue3";
import { parseLevel, toUnderscore } from "@/utils.js";
import TabElement from "@/Components/TabElement.vue";
import LevelSubjects from "@/Views/Admin/Levels/LevelSubjects.vue";
import LevelStudents from "@/Views/Admin/Levels/LevelStudents.vue";
import SelectInput from "@/Components/SelectInput.vue";
import Section from "@/Pages/Admin/Levels/Section.vue";
import { useI18n } from "vue-i18n";

const { t } = useI18n();
const level = computed(() => usePage().props.level);
const schoolYear = computed(() => usePage().props.school_year);
const sectionsTab = toUnderscore(t("common.sections"));
const subjectsTab = toUnderscore(t("common.subjects"));
const studentTab = toUnderscore(t("common.students"));
const tabs = [sectionsTab, subjectsTab, studentTab];
const activeTab = ref(sectionsTab);

const batches = computed(() => usePage().props.batches);
const batchOptions = computed(() => {
    return batches.value.map((batch) => {
        return {
            value: batch.id,
            label: `Section ${batch.section}`,
        };
    });
});
const activeBatch = ref(batches.value[0]);
const selectedBatch = ref(batchOptions.value[0].value);
watch(selectedBatch, (value) => {
    activeBatch.value = batches.value.find((batch) => batch.id === value);
});
</script>
