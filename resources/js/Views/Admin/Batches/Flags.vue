<template>
    <TableElement
        :selectable="false"
        :filterable="false"
        :columns="config"
        :data="flaggedStudentsData"
        title="Flagged Students"
    />
</template>
<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import TableElement from "@/Components/TableElement.vue";

const flaggedStudents = computed(() => usePage().props.flagged_students);

const flaggedStudentsData = computed(() => {
    return flaggedStudents.value.map((flaggedStudent) => {
        return {
            name: flaggedStudent.flaggable.user.name,
            username: flaggedStudent.flaggable.user.username,
            gender: flaggedStudent.flaggable.user.gender,
            type: flaggedStudent.type,
            description: flaggedStudent.description,
            flagged_by: flaggedStudent.flagged_by.name,
        };
    });
});

const config = [
    {
        name: "Student",
        key: "name",
        class: "font-semibold",
    },
    {
        name: "Username",
        key: "username",
        class: "text-gray-500 text-xs font-semibold",
    },
    {
        name: "Gender",
        key: "gender",
        type: "enum",
        options: ["male", "female"],
    },
    {
        name: "Type",
        key: "type",
    },
    {
        name: "Description",
        key: "description",
    },
    {
        name: "Flagged by",
        key: "flagged_by",
    },
];
</script>
<style scoped></style>
