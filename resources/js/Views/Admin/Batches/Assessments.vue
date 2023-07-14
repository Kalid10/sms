<template>
    <div v-if="assessments.length > 0" class="flex w-full">
        <TableElement
            :selectable="false"
            :filterable="false"
            :columns="config"
            :data="assessmentsData"
            :title="$t('batchAssessments.studentNotes')"
        />
    </div>
    <div v-else class="flex w-full items-center justify-center">
        <EmptyView :title="$t('batchAssessments.noScheduledAssessment')"/>
    </div>
</template>
<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import TableElement from "@/Components/TableElement.vue";
import EmptyView from "@/Views/EmptyView.vue";
import {useI18n} from "vue-i18n";
const {t} = useI18n()
const assessments = computed(() => usePage().props.assessments);

const assessmentsData = computed(() => {
    return usePage().props.assessments.map((assessment) => {
        return {
            name: assessment.name,
            description: assessment.description,
            due_date: assessment.due_date,
            subject: assessment.subject,
            assessment_type: assessment.assessment_type,
        };
    });
});

const config = [
    {
        name: t('common.assessment'),
        key: "name",
        class: "font-semibold",
    },
    {
        name: t('common.description'),
        key: "description",
        class: "text-gray-500 text-xs font-semibold",
    },
    {
        name: t('batchAssessments.dueDate'),
        key: "due_date",
    },
    {
        name: t('common.subjects'),
        key: "subject",
    },
    {
        name: t('batchAssessments.assessmentType'),
        key: "assessment_type",
    },
];
</script>
<style scoped></style>
