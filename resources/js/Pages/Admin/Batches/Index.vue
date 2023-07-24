<template>
    <div class="mx-auto w-full space-y-3 px-3 lg:w-11/12">
        <div class="flex w-full flex-col flex-wrap items-center text-center">
            <Title :title="gradeLevel" />
        </div>
        <TabElement v-model:active="activeTab" :tabs="tabs">
            <template #[homeTab]>
                <div class="flex flex-col items-center justify-center">
                    <Home />
                </div>
            </template>
            <template #[schedulesTab]>
                <div class="flex flex-col items-center justify-center">
                    <Schedules />
                </div>
            </template>
            <template #[studentsTab]>
                <div class="flex flex-col items-center justify-center">
                    <Students />
                </div>
            </template>
        </TabElement>
    </div>
</template>
<script setup>
import Title from "@/Views/Teacher/Views/Title.vue";
import TabElement from "@/Components/TabElement.vue";
import { computed, ref } from "vue";
import Schedules from "@/Views/Admin/Batches/Schedules.vue";
import Home from "@/Views/Admin/Batches/Index.vue";
import Students from "@/Views/Admin/Batches/Students.vue";
import { usePage } from "@inertiajs/vue3";
import { useI18n } from "vue-i18n";
import { toUnderscore } from "@/utils";

const { t } = useI18n();
const batch = computed(() => usePage().props.batch);

const gradeLevel = computed(() => {
    return batch.value.level.name + " " + batch.value.section;
});

const homeTab = toUnderscore(t("common.home"));
const schedulesTab = toUnderscore(t("common.schedules"));
const studentsTab = toUnderscore(t("common.students"));
const tabs = [homeTab, schedulesTab, studentsTab];

const activeTab = ref(homeTab);
</script>
<style scoped></style>
