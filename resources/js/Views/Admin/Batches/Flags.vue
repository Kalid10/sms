<template>
    <TableElement
        :selectable="false"
        :filterable="false"
        :columns="config"
        :data="flaggedStudentsData"
        :title="$t('batchFlags.flaggedStudents')"
    />
</template>
<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import TableElement from "@/Components/TableElement.vue";
import {useI18n} from "vue-i18n";
const {t} = useI18n()
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
        name: t('common.student'),
        key: "name",
        class: "font-semibold",
    },
    {
        name: t('batchFlags.userName'),
        key: "username",
        class: "text-gray-500 text-xs font-semibold",
    },
    {
        name:  t('common.gender'),
        key: "gender",
        type: "enum",
        options: ["male", "female"],
    },
    {
        name:  t('common.type'),
        key: "type",
    },
    {
        name:  t('common.description'),
        key: "description",
    },
    {
        name: t('batchFlags.flaggedBy'),
        key: "flagged_by",
    },
];
</script>
<style scoped></style>
