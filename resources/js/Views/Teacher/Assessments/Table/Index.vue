<template>
    <div v-if="filteredAssessments">
        <TableElement
            class="h-full bg-white text-black !shadow-none"
            :data="filteredAssessments"
            :selectable="false"
            :columns="config"
            header-style="!bg-black text-white text-xs"
            :footer="false"
        >
            <template #filter>
                <Filters />
            </template>
        </TableElement>
    </div>
</template>
<script setup>
import { usePage } from "@inertiajs/vue3";
import TableElement from "@/Components/TableElement.vue";
import { capitalize, computed } from "vue";
import moment from "moment";
import Filters from "@/Views/Teacher/Assessments/Table/Filters.vue";

const assessments = computed(() => usePage().props.assessments);
const filteredAssessments = computed(() => {
    return assessments.value.data.map((assessment) => {
        return {
            title: assessment.title,
            max_points: assessment.maximum_point,
            status: capitalize(assessment.status),
            due_date: moment(assessment.due_date).fromNow(),
            updated_at: moment(assessment.updated_at).fromNow(),
        };
    });
});

const config = [
    {
        name: "Title",
        key: "title",
        class: "text-xs",
        link: "/teacher/assessments/1",
    },
    {
        name: "Max Points",
        key: "max_points",
        class: "text-xs",
    },
    {
        name: "Status",
        key: "status",
        type: "enum",
        options: ["Marking", "Completed", "Published", "Draft", "Closed"],
        class: "text-xs",
    },
    {
        name: "Due Date",
        key: "due_date",
        class: "text-xs",
    },
    {
        name: "Last Update",
        key: "updated_at",
        class: "text-xs opacity-70",
    },
];
</script>

<style scoped></style>
