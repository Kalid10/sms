<template>
    <div class="w-full py-5 px-3 lg:w-10/12 lg:px-0">
        <Title class="pb-8" title="Absentees" />
        <Title class="pb-8" :title="$t('common.absentees')" />

        <TabElement v-model:active="activeTab" :tabs="tabs">
            <template #[staffTab]>
                <StaffAbsenteesTable />
            </template>
            <template #[studentTab]>
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

const { t } = useI18n();
const staffTab = t("common.staff");
const studentTab = t("common.student");
const tabs = [staffTab, studentTab];
const activeTab = ref(staffTab);
</script>
<style scoped></style>
