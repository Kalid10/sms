<template>
    <div v-if="assessments.length > 0" class="flex w-full">
        <TableElement
            :selectable="false"
            :filterable="false"
            :columns="config"
            :data="assessmentsData"
            title="Student Notes"
        />
    </div>
    <div v-else class="flex w-full items-center justify-center">
        <EmptyView title="No Scheduled Assessment Found" />
    </div>
</template>
<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import TableElement from "@/Components/TableElement.vue";
import EmptyView from "@/Views/EmptyView.vue";

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
        name: "Assessment",
        key: "name",
        class: "font-semibold",
    },
    {
        name: "Description",
        key: "description",
        class: "text-gray-500 text-xs font-semibold",
    },
    {
        name: "Due Date",
        key: "due_date",
    },
    {
        name: "Subject",
        key: "subject",
    },
    {
        name: "Assessment Type",
        key: "assessment_type",
    },
];
</script>
<style scoped></style>
