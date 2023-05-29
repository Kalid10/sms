<template>
    <div
        ref="parentDiv"
        class="flex w-full flex-col items-center justify-between space-y-3"
    >
        <div
            class="flex items-center justify-between gap-2 pb-3 md:w-full"
            :class="showFilter ? 'blur-sm' : 'blur-none'"
        >
            <div class="flex w-8/12 justify-between">
                <SelectInput
                    v-model="selectedBatchSubjectId"
                    :options="batchSubjectOptions"
                    placeholder="Select Subject"
                    class="md:w-5/12"
                />
                <TextInput
                    v-model="search"
                    placeholder="Search Assessment Title"
                    class="md:w-5/12"
                    class-style="h-8 bg-white border-gray-300 text-black placeholder:text-gray-500 placeholder:text-xs focus:border-none focus:ring-violet-500"
                />
            </div>
            <div
                class="flex w-fit cursor-pointer space-x-2 rounded-md bg-zinc-800 px-2 py-1.5 text-xs font-medium text-white hover:font-semibold"
                @click="$emit('create')"
            >
                <SquaresPlusIcon class="w-4" />
                <div>Create Assessment</div>
            </div>
        </div>

        <SelectedFilters
            :search="search"
            :selected-assessment-type-name="selectedAssessmentType?.name"
            :selected-batch-subject-name="selectedBatchSubject"
            :show-filter="showFilter"
            :selected-school-year="selectedSchoolYearName"
            :selected-semester="selectedSemesterName"
            :selected-quarter="selectedQuarterName"
            :status="selectedAssessmentStatus"
            @show="showFilter = true"
            @remove-batch-subject="selectedBatchSubjectId = null"
            @remove-assessment-type="selectedAssessmentTypeId = null"
            @remove-school-year="
                selectedSchoolYear = null;
                selectedSemester = null;
                selectedQuarter = null;
            "
            @remove-semester="
                selectedSemester = null;
                selectedQuarter = null;
            "
            @remove-quarter="selectedQuarter = null"
            @remove-search="search = ''"
            @remove-status="selectedAssessmentStatus = null"
        />
        <div
            v-if="showFilter"
            class="fixed z-50 ml-10 h-fit w-80 rounded-md bg-gradient-to-br from-zinc-200 via-zinc-200 to-zinc-100 p-2 shadow-md backdrop-blur-none"
        >
            <div class="flex w-full justify-between">
                <div class="px-2 text-sm">Filters</div>
                <XMarkIcon
                    class="w-4 cursor-pointer text-black hover:text-red-600"
                    @click="showFilter = !showFilter"
                />
            </div>

            <div class="flex w-full flex-col space-y-4 px-2 py-4">
                <SelectInput
                    v-model="selectedAssessmentStatus"
                    :options="assessmentStatusOptions"
                    placeholder="Select Status"
                />
                <SelectInput
                    v-if="selectedBatchSubjectId"
                    v-model="selectedAssessmentTypeId"
                    :options="selectedBatchAssessmentTypes"
                    placeholder="Assessment Type"
                />
                <SelectInput
                    v-model="selectedSchoolYear"
                    :options="schoolYearOptions"
                    class="w-full"
                    placeholder="Select SchoolYear"
                />
                <SelectInput
                    v-if="selectedSchoolYear"
                    v-model="selectedSemester"
                    :options="semesterOptions"
                    class="w-full"
                    placeholder="Select Semester"
                />
                <SelectInput
                    v-if="selectedSemester"
                    v-model="selectedQuarter"
                    :options="quarterOptions"
                    class="w-full"
                    placeholder="Select Quarter"
                />
            </div>
            <div class="flex justify-between px-2">
                <div
                    v-if="showFilter"
                    class="flex h-fit w-5/12 cursor-pointer items-center justify-center space-x-1 rounded-md bg-black from-violet-600 to-fuchsia-500 p-1.5 text-xs font-bold text-white hover:bg-gradient-to-br"
                    @click="showFilter = false"
                >
                    <CheckCircleIcon class="w-3 text-gray-100" />
                    <span> DONE</span>
                </div>
                <div
                    v-if="showFilter"
                    class="flex h-fit w-5/12 cursor-pointer items-center justify-center space-x-1 rounded-md bg-black from-red-600 to-orange-500 p-1.5 text-xs font-bold text-white hover:bg-gradient-to-br"
                    @click="clearFilters"
                >
                    <TrashIcon class="w-3 text-gray-100" />
                    <span> CLEAR ALL</span>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { computed, ref, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import debounce from "lodash/debounce";
import {
    CheckCircleIcon,
    SquaresPlusIcon,
    TrashIcon,
    XMarkIcon,
} from "@heroicons/vue/20/solid";
import SelectInput from "@/Components/SelectInput.vue";
import TextInput from "@/Components/TextInput.vue";
import { onClickOutside } from "@vueuse/core";
import SelectedFilters from "@/Views/Teacher/Assessments/Table/Filters/SelectedFilters.vue";

const emit = defineEmits(["create", "filterEnabled"]);

const pageProps = usePage().props;
const {
    assessments: rawAssessments,
    school_years: schoolYears,
    semesters,
    quarters,
    filters,
} = pageProps;
const teacher = pageProps.teacher;
const assessmentTypes = pageProps.assessment_type;

const assessments = computed(() => rawAssessments);

// Refs for selected values
const selectedSchoolYear = ref(filters.school_year_id);
const selectedSemester = ref(filters.semester_id);
const selectedQuarter = ref(filters.quarter_id);
const search = ref(filters.search ?? "");
const dueDate = ref(filters.due_date ? new Date(filters.due_date) : null);
const selectedBatchSubjectId = ref(
    filters.batch_subject_id ? Number(filters.batch_subject_id) : null
);
const selectedAssessmentTypeId = ref(
    filters.assessment_type_id ? Number(filters.assessment_type_id) : null
);
const showFilter = ref(false);
const selectedAssessmentStatus = ref(filters.status ?? null);

// Functions
const getOptions = (items, selectedValue, labelCallback) =>
    selectedValue
        ? items
              .filter((item) => labelCallback(item).id === selectedValue)
              .map((item) => ({
                  label: `${item.name} (${labelCallback(item).name})`,
                  value: item.id,
              }))
        : [];

const clearFilters = () => {
    selectedSchoolYear.value = null;
    selectedSemester.value = null;
    selectedQuarter.value = null;
    selectedAssessmentTypeId.value = null;
    selectedBatchSubjectId.value = null;
    dueDate.value = null;
    showFilter.value = false;
};

// Computed Options
const schoolYearOptions = computed(() =>
    schoolYears.map((item) => ({ label: item.name, value: item.id }))
);
const semesterOptions = computed(() =>
    getOptions(semesters, selectedSchoolYear.value, (item) => item.school_year)
);
const quarterOptions = computed(() =>
    getOptions(quarters, selectedSemester.value, (item) => item.semester)
);

const selectedBatchAssessmentTypes = computed(() => {
    if (selectedBatchSubjectId.value) {
        const batch_subject = teacher.batch_subjects.find(
            (batch_subject) => batch_subject.id === selectedBatchSubjectId.value
        );
        if (batch_subject) {
            const level_category_id =
                batch_subject.batch.level.level_category.id;
            return assessmentTypes
                .filter((type) => type.level_category_id === level_category_id)
                .map((type) => ({ label: type.name, value: type.id }));
        }
    }
    return [];
});

const batchSubjectOptions = computed(() =>
    teacher.batch_subjects.map((batchSubject) => ({
        value: batchSubject.id,
        label: `${batchSubject.subject.full_name} ${batchSubject.batch.level.name} ${batchSubject.batch.section}`,
    }))
);

const assessmentStatusOptions = computed(() => [
    { label: "All", value: null },
    { label: "Published", value: "published" },
    { label: "Completed", value: "completed" },
    { label: "Marking", value: "marking" },
    { label: "Draft", value: "draft" },
    { label: "Cancelled", value: "cancelled" },
]);

// Selected Filter Values
const selectedBatchSubject = computed(() => {
    if (!selectedBatchSubjectId.value) return null;
    let batchSubject = teacher.batch_subjects.find(
        (subject) => Number(subject.id) === Number(selectedBatchSubjectId.value)
    );

    if (batchSubject)
        return (
            batchSubject.batch.level.name +
            " " +
            batchSubject.batch.section +
            " " +
            batchSubject.subject.full_name
        );

    return null;
});

const selectedAssessmentType = computed(() => {
    return assessmentTypes.find(
        (type) => Number(type.id) === Number(selectedAssessmentTypeId.value)
    );
});

const selectedSchoolYearName = computed(() => {
    if (!selectedSchoolYear.value) return null;
    return schoolYears.find(
        (year) => Number(year.id) === Number(selectedSchoolYear.value)
    ).name;
});

const selectedSemesterName = computed(() => {
    if (!selectedSemester.value) return null;
    return semesters.find(
        (semester) => Number(semester.id) === Number(selectedSemester.value)
    ).name;
});

const selectedQuarterName = computed(() => {
    if (!selectedQuarter.value) return null;
    return quarters.find(
        (quarter) => Number(quarter.id) === Number(selectedQuarter.value)
    ).name;
});

// Watchers and debouncing
const watchers = [
    selectedSchoolYear,
    selectedSemester,
    selectedQuarter,
    selectedAssessmentTypeId,
    selectedBatchSubjectId,
    search,
    dueDate,
];
const debounceGetAssessments = debounce(getAssessments, 500);
watchers.forEach((item) => watch(item, debounceGetAssessments));

watch(showFilter, () => {
    emit("filterEnabled", showFilter.value);
});

async function getAssessments() {
    await router.get(
        "/teacher/assessments",
        {
            school_year_id: selectedSchoolYear.value,
            semester_id: selectedSemester.value,
            quarter_id: selectedQuarter.value,
            assessment_type_id: selectedAssessmentTypeId.value,
            batch_subject_id: selectedBatchSubjectId.value,
            search: search.value,
            due_date: dueDate.value,
            status: selectedAssessmentStatus.value,
        },
        {
            preserveScroll: true,
            preserveState: true,
        }
    );
}

// Outside click handling
const parentDiv = ref(null);
onClickOutside(parentDiv, () => {
    showFilter.value = false;
});
</script>

<style scoped></style>
