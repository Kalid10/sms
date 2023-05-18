<template>
    <div class="flex w-full flex-col justify-between gap-2 md:flex-row">
        <div class="flex justify-evenly gap-2 md:w-2/5">
            <TextInput v-model="search" placeholder="Search" class="md:w-2/5"/>
            <DatePicker v-model="dueDate" class="md:w-2/5"/>
        </div>
        <div class="flex flex-col justify-between gap-2 md:w-3/5 md:flex-row md:justify-evenly">
            <SelectInput
                v-model="selectedSchoolYear"
                class="md:w-3/12"
                :options="schoolYearOptions"
                placeholder="Select SchoolYear"
            />
            <SelectInput
                v-if="selectedSchoolYear"
                v-model="selectedSemester"
                class="md:w-4/12"
                :options="semesterOptions"
                placeholder="Select Semester"
            />
            <SelectInput
                v-if="selectedSemester"
                v-model="selectedQuarter"
                class="md:w-3/12"
                :options="quarterOptions"
                placeholder="Select Quarter"
            />
        </div>
    </div>
</template>
<script setup>
import {computed, ref, watch} from "vue";
import {router, usePage} from "@inertiajs/vue3";
import debounce from "lodash/debounce";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import DatePicker from "@/Components/DatePicker.vue";

const {
    assessments: rawAssessments,
    school_years: schoolYears,
    semesters,
    quarters,
    filters,
} = usePage().props;

const assessments = computed(() => rawAssessments);
const selectedSchoolYear = ref(Number(filters.school_year_id)) ?? null;
const selectedSemester = ref(Number(filters.semester_id)) ?? null;
const selectedQuarter = ref(Number(filters.quarter_id)) ?? null;
const search = ref(filters.search) ?? "";
const dueDate = ref(filters.due_date ? new Date(filters.due_date) : null);

function getOptions(items, selectedValue, labelCallback) {
    if (selectedValue) {
        return items
            .filter((item) => labelCallback(item).id === selectedValue)
            .map((item) => ({
                label: `${item.name} (${labelCallback(item).name})`,
                value: item.id,
            }));
    } else {
        return [];
    }
}

let schoolYearOptions = computed(() =>
    schoolYears.map((item) => ({label: item.name, value: item.id}))
);

let semesterOptions = computed(() =>
    getOptions(semesters, selectedSchoolYear.value, (item) => item.school_year)
);

let quarterOptions = computed(() =>
    getOptions(quarters, selectedSemester.value, (item) => item.semester)
);

const watchers = [
    selectedSchoolYear,
    selectedSemester,
    selectedQuarter,
    search,
    dueDate,
];
const debounceGetAssessments = debounce(getAssessments, 500);
watchers.forEach((item) => watch(item, debounceGetAssessments));

async function getAssessments() {
    await router.get(
        "/teacher/assessments",
        {
            school_year_id: selectedSchoolYear.value,
            semester_id: selectedSemester.value,
            quarter_id: selectedQuarter.value,
            search: search.value,
            due_date: dueDate.value,
        },
        {
            preserveScroll: true,
            preserveState: true,
        }
    );
}
</script>

<style scoped></style>
