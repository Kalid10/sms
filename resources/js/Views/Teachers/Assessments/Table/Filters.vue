<template>
    <div class="flex w-full justify-between">
        <div class="flex w-2/5 justify-evenly">
            <TextInput v-model="search" placeholder="Search" class="w-2/5" />
            <DatePicker v-model="dueDate" class="w-2/5" />
        </div>
        <div class="flex w-3/5 justify-evenly">
            <SelectInput
                v-model="selectedSchoolYear"
                class="w-3/12"
                :options="schoolYearOptions"
                placeholder="Select SchoolYear"
            />
            <SelectInput
                v-if="selectedSchoolYear"
                v-model="selectedSemester"
                class="w-4/12"
                :options="semesterOptions"
                placeholder="Select Semester"
            />
            <SelectInput
                v-if="selectedSemester"
                v-model="selectedQuarter"
                class="w-3/12"
                :options="quarterOptions"
                placeholder="Select Quarter"
            />
        </div>
    </div>
</template>
<script setup>
import { computed, ref, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import debounce from "lodash/debounce";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import DatePicker from "@/Components/DatePicker.vue";

const {
    assessments: rawAssessments,
    school_years: schoolYears,
    semesters,
    quarters,
} = usePage().props;
const assessments = computed(() => rawAssessments);
const selectedQuarter = ref(null);
const selectedSemester = ref(null);
const selectedSchoolYear = ref(null);
const search = ref("");
const dueDate = ref(null);

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
    schoolYears.map((item) => ({ label: item.name, value: item.id }))
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
