<template>
    <div class="w-10/12 py-5">
        <Title class="pb-8" :title="$t('common.absentee')" />

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
import {useI18n} from "vue-i18n";
const {t} = useI18n()
const tabs = [t('common.staff'), t('common.student')];
const staffTab = t('common.staff')
const studentTab = t('common.student')
const activeTab = ref(staffTab);
</script>
<style scoped></style>
