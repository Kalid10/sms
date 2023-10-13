<template>
    <div v-if="filteredAssessments" class="flex flex-col space-y-4">
        <Filters
            class="z-10"
            @filter-enabled="setFilterValue"
            @create="$emit('create')"
        />

        <TableElement
            class="h-full bg-white text-black !shadow-none"
            :data="filteredAssessments"
            :class="filterEnabled ? 'blur-lg' : 'blur-none'"
            :selectable="false"
            :columns="config"
            header-style="!bg-brand-450 text-white text-xs"
            :filterable="false"
            :footer="true"
        >
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
                        class="w-fit rounded-xl px-2 py-1 text-center text-[0.55rem] font-semibold lg:px-2"
                        :class="{
                            'bg-zinc-500 text-white': data === 'Draft',
                            'bg-emerald-400': data === 'Published',
                            'bg-indigo-400 text-white': data === 'Marking',
                            'bg-yellow-400': data === 'Completed',
                            'bg-cyan-400': data === 'Scheduled',
                        }"
                    >
                        {{ data?.toUpperCase() ?? "" }}
                    </div>
                </div>
            </template>

            <template #footer>
                <Pagination :links="assessments.links" position="center" />
            </template>
        </TableElement>
    </div>
</template>
<script setup>
import { usePage } from "@inertiajs/vue3";
import TableElement from "@/Components/TableElement.vue";
import { capitalize, computed, ref, watch } from "vue";
import moment from "moment";
import Filters from "@/Views/Teacher/Views/Assessments/Table/Filters/Index.vue";
import Pagination from "@/Components/Pagination.vue";
import { useI18n } from "vue-i18n";

const { t } = useI18n();
const emit = defineEmits(["click", "create"]);
const assessments = computed(() => usePage().props.assessments);

const filteredAssessments = computed(() => {
    return (
        assessments.value?.data?.map((assessment) => {
            return {
                assessment: assessment,
                max_points: assessment?.maximum_point,
                status: assessment?.status ? capitalize(assessment.status) : "",
                due_date: assessment?.due_date
                    ? moment(assessment.due_date).fromNow()
                    : "",
                updated_at: assessment?.updated_at
                    ? moment(assessment.updated_at).fromNow()
                    : "",
                assessment_type: assessment?.assessment_type?.name
                    ? capitalize(assessment.assessment_type.name)
                    : "",
                id: assessment?.id,
            };
        }) ?? []
    );
});

const config = [
    {
        name: t("tableIndex.title"),
        key: "assessment",
        type: "custom",
        class: "text-xs py-3.5",
    },
    {
        name: t("tableIndex.type"),
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
        name: t("tableIndex.maxPoints"),
        key: "max_points",
        class: "text-xs",
    },
    {
        name: t("tableIndex.status"),
        key: "status",
        type: "custom",
        class: "text-xs",
    },
    {
        name: t("tableIndex.dueDate"),
        key: "due_date",
        class: "text-xs",
    },
    {
        name: t("tableIndex.lastUpdate"),
        key: "updated_at",
        class: "text-xs opacity-70",
    },
];

const selectedAssessment = ref();
const filterEnabled = ref(false);

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

function setFilterValue(value) {
    filterEnabled.value = value;
}
</script>

<style scoped></style>
