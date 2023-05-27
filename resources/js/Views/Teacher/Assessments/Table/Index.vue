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

            <template #assessment-column="{ data }">
                <div
                    class="cursor-pointer underline underline-offset-2"
                    @click="click(data)"
                >
                    {{ data.title }}
                </div>
            </template>
            <template #status-column="{ data }">
                <div class="flex w-full items-center justify-center">
                    <div
                        class="w-full rounded-xl py-1 px-2 text-center text-[0.55rem] font-semibold md:w-10/12 lg:w-10/12 lg:px-2 xl:w-9/12 2xl:w-7/12"
                        :class="{
                            'bg-zinc-800 text-white': data === 'Draft',
                            'bg-emerald-400': data === 'Published',
                            'bg-blue-200': data === 'Marking',
                            'bg-yellow-400': data === 'Completed',
                        }"
                    >
                        {{ data.toUpperCase() }}
                    </div>
                </div>
            </template>
        </TableElement>
    </div>
</template>
<script setup>
import { usePage } from "@inertiajs/vue3";
import TableElement from "@/Components/TableElement.vue";
import { capitalize, computed, ref, watch } from "vue";
import moment from "moment";
import Filters from "@/Views/Teacher/Assessments/Table/Filters.vue";

const emit = defineEmits(["click"]);
const assessments = computed(() => usePage().props.assessments);
const filteredAssessments = computed(() => {
    return assessments.value.data.map((assessment) => {
        return {
            assessment: assessment,
            max_points: assessment.maximum_point,
            status: capitalize(assessment.status),
            due_date: moment(assessment.due_date).fromNow(),
            updated_at: moment(assessment.updated_at).fromNow(),
            assessment_type: capitalize(assessment.assessment_type.name),
            id: assessment.id,
        };
    });
});

const config = [
    {
        name: "Title",
        key: "assessment",
        type: "custom",
        class: "text-xs py-3.5",
    },
    {
        name: "Type",
        key: "assessment_type",
        class: "text-xs",
        type: "enum",
        options: [
            "Homework",
            "Classwork",
            "Final Quarterly Exam",
            "Final Exam",
            "Test",
        ],
    },
    {
        name: "Max Points",
        key: "max_points",
        class: "text-xs",
    },
    {
        name: "Status",
        key: "status",
        type: "custom",
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

const selectedAssessment = ref();

watch(assessments, (newValue) => {
    if (selectedAssessment.value && newValue) {
        let selected = newValue.data.find(
            (assessment) => assessment.id === selectedAssessment.value
        );
        if (selected) {
            emit("click", selected);
            selectedAssessment.value = null;
        }
    }
});

function click(e) {
    selectedAssessment.value = e.id;
    emit("click", e);
}
</script>

<style scoped></style>
