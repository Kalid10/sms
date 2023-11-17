<template>
    <div
        class="w-full px-3 py-5 lg:px-0"
        :class="isSidebarOpenOnXlDevice ? 'lg:w-11/12' : 'lg:w-10/12'"
    >
        <Title class="pb-8" :title="$t('common.absentees')" />

        <TabElement v-model:active="activeTab" :tabs="tabs">
            <template #[staffTab]>
                <StaffAbsenteesTable />
            </template>
            <template #[studentsTab]>
                <StudentsAbsenteesTable />
            </template>
        </TabElement>
    </div>
</template>
<script setup>
import Title from "@/Views/Teacher/Views/Title.vue";
import StaffAbsenteesTable from "@/Views/Admin/Absentees/Staff/StaffAbsenteesTable.vue";
import StudentsAbsenteesTable from "@/Views/Admin/Absentees/Students/StudentsAbsenteeTable.vue";
import TabElement from "@/Components/TabElement.vue";
import { ref } from "vue";
import { useI18n } from "vue-i18n";
import { isSidebarOpenOnXlDevice, toUnderscore } from "@/utils";

const { t } = useI18n();
const staffTab = toUnderscore(t("common.staff"));
const studentsTab = toUnderscore(t("common.students"));
const tabs = [staffTab, studentsTab];
const activeTab = ref(staffTab);
</script>
<style scoped></style>
