<template>
    <div class="w-10/12 py-5">
        <Title class="pb-8" title="Absentee" />

        <TabElement v-model:active="activeTab" :tabs="tabs">
            <template #staff>
                <StaffAbsenteesTable />
            </template>
            <template #students>
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

const tabs = ["Staff", "Students"];
const activeTab = ref("Staff");
</script>
<style scoped></style>
