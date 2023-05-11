<template>
    <TableElement
        class="h-full text-black"
        :data="filteredAssessments"
        :selectable="false"
        :columns="config"
    >
        <template #filter>
            <Filters />
        </template>
    </TableElement>
</template>
<script setup>
import { usePage } from "@inertiajs/vue3";
import TableElement from "@/Components/TableElement.vue";
import { computed } from "vue";
import moment from "moment";
import Filters from "@/Views/Teachers/Assessments/Table/Filters.vue";

const assessments = computed(() => usePage().props.assessments);
const filteredAssessments = computed(() => {
    return assessments.value.data.map((assessment) => {
        return {
            title: assessment.title,
            max_points: assessment.maximum_point,
            due_date: moment(assessment.due_date).format("dddd, MMMM D YYYY"),
            updated_at: moment(assessment.updated_at).format(
                "dddd, MMMM D YYYY"
            ),
        };
    });
});

const config = [
    {
        name: "Title",
        key: "title",
    },
    {
        name: "Max Points",
        key: "max_points",
    },
    {
        name: "Due Date",
        key: "due_date",
    },
    {
        name: "Last Update",
        key: "updated_at",
    },
];
</script>

<style scoped></style>
