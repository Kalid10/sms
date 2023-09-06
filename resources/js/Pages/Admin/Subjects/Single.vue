<template>
    <div class="my-5 flex w-full flex-col space-y-5 p-3 lg:w-10/12 lg:p-0">
        <div class="flex items-center justify-center gap-2">
            <div
                :class="subjectPriorityLabels[subject['priority'] - 1]"
                class="h-7 w-0.5"
            />
            <h3 class="text-xl font-semibold lg:text-4xl">
                {{ subject["full_name"] }}
            </h3>
            <div class="flex flex-col items-baseline gap-1">
                <h3 class="text-md text-gray-700">
                    {{ subject["category"] }}
                </h3>
                <h3 class="text-xs text-gray-700">
                    {{
                        subject["tags"]
                            .map((tag) => toHashTag(tag))
                            .join("&nbsp;&nbsp;")
                    }}
                </h3>
            </div>
        </div>

        <TabElement v-model:active="activeTab" :tabs="subjectTabs">
            <template #[gradesTab]>
                <SubjectGrades />
            </template>

            <template #[teachersTab]>
                <SubjectTeachers />
            </template>
        </TabElement>
    </div>
</template>

<script setup>
import { computed, ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import TabElement from "@/Components/TabElement.vue";
import SubjectTeachers from "@/Views/Admin/Subjects/SubjectTeachers.vue";
import SubjectGrades from "@/Views/Admin/Subjects/SubjectGrades.vue";
import { subjectPriorityLabels, toHashTag, toUnderscore } from "@/utils";
import { useI18n } from "vue-i18n";

const { t } = useI18n();
const subject = computed(() => usePage().props.subject);

const teachersTab = toUnderscore(t("common.teachers"));
const gradesTab = toUnderscore(t("common.grades"));
const subjectTabs = [gradesTab, teachersTab];
const activeTab = ref(gradesTab);
</script>

<style scoped></style>
