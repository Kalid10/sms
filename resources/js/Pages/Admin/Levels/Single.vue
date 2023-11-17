<template>
    <div class="flex w-full flex-col items-center space-y-8 p-3 lg:p-5">
        <div class="flex w-full gap-3">
            <div class="flex w-5/12 flex-col">
                <div class="text-xl font-semibold lg:text-2xl">
                    {{ parseLevel(level.name) }}
                </div>
                <h3 class="text-sm text-black">
                    {{ schoolYear.name }}
                </h3>
            </div>
        </div>

        <TabElement v-model:active="activeTab" :tabs="tabs">
            <template #[subjectsTab]>
                <LevelSubjects />
            </template>

            <template #[sectionsTab]>
                <UnderConstruction />
            </template>

            <template #[studentTab]>
                <UnderConstruction />
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
import { useI18n } from "vue-i18n";
import UnderConstruction from "@/Components/UnderConstruction.vue";

const { t } = useI18n();
const level = computed(() => usePage().props.level);
const schoolYear = computed(() => usePage().props.school_year);
const sectionsTab = toUnderscore(t("common.sections"));
const subjectsTab = toUnderscore(t("common.subjects"));
const studentTab = toUnderscore(t("common.students"));
const tabs = [subjectsTab, sectionsTab, studentTab];
const activeTab = ref(subjectsTab);

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
