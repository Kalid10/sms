<template>
    <div class="flex items-center justify-between space-x-2">
        <SelectInput
            v-model="computedSchoolYearId"
            class="h-fit w-1/3 rounded-2xl !text-sm"
            :options="schoolYearOptions"
            placeholder="school year"
        />
        <SelectInput
            v-model="computedSemesterId"
            class="h-fit w-1/3 rounded-2xl !text-sm"
            :options="semesterOptions"
            placeholder="Semester"
        />
        <SelectInput
            v-model="computedQuarterId"
            class="h-fit w-1/3 rounded-2xl !text-sm"
            :options="quarterOptions"
            placeholder="quarter"
        />
    </div>

    <div class="flex items-center space-x-2">
        <div
            v-if="selectedSchoolYear"
            class="flex items-center space-x-2 rounded-lg bg-brand-400 p-2 text-white"
        >
            <span class="text-xs font-semibold">
                {{ selectedSchoolYear.name }}
            </span>
            <span
                class="cursor-pointer text-xs font-semibold"
                @click="
                    selectedSchoolYear = { id: null };
                    selectedSemester = { id: null };
                    selectedQuarter = { id: null };
                "
            >
                <XMarkIcon class="h-3 w-3 fill-red-500" />
            </span>
        </div>
        <div
            v-if="selectedSemester"
            class="flex items-center space-x-2 rounded-lg bg-brand-400 p-2 text-white"
        >
            <span class="text-xs font-semibold">
                {{ selectedSemester.name }}
            </span>
            <span
                class="cursor-pointer text-xs font-semibold"
                @click="
                    selectedSemester = { id: null };
                    selectedQuarter = { id: null };
                "
            >
                <XMarkIcon class="h-3 w-3 fill-red-500" />
            </span>
        </div>
        <div
            v-if="selectedQuarter"
            class="flex items-center space-x-2 rounded-lg bg-brand-400 p-2 text-white"
        >
            <span class="text-xs font-semibold">
                {{ selectedQuarter.name }}
            </span>
            <span
                class="cursor-pointer text-xs font-semibold"
                @click="selectedQuarter = { id: null }"
            >
                <XMarkIcon class="h-3 w-3 fill-red-500" />
            </span>
        </div>
    </div>
</template>
<script setup>
import { XMarkIcon } from "@heroicons/vue/20/solid";
import SelectInput from "@/Components/SelectInput.vue";
import { computed, ref, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";

const schoolYears = computed(() => usePage().props.filters.school_years);
const semesters = computed(() => usePage().props.filters.semesters);
const quarters = computed(() => usePage().props.filters.quarters);

const batchSubject = usePage().props.batch_subject;
const batchSubjectId = batchSubject ? batchSubject.id : null;

const selectedSchoolYear = ref(usePage().props.filters.school_year);

const selectedSemester = ref(usePage().props.filters.semester);

const selectedQuarter = ref(usePage().props.filters.quarter);

const schoolYearOptions = computed(() => {
    return schoolYears.value?.map((item) => {
        return {
            label: item.name,
            value: item.id,
        };
    });
});

// Semesters options based on selected school year
const semesterOptions = computed(() => {
    if (selectedSchoolYear.value === null) return [];
    return semesters.value
        ?.filter((item) => {
            return item.school_year_id === selectedSchoolYear.value.id;
        })
        .map((item) => {
            return {
                label: item.name,
                value: item.id,
            };
        });
});

// Quarters options based on selected semester
const quarterOptions = computed(() => {
    if (selectedSemester.value === null) return [];
    return quarters.value
        ?.filter((item) => {
            return item.semester_id === selectedSemester.value.id;
        })
        .map((item) => {
            return {
                label: item.name,
                value: item.id,
            };
        });
});

const computedSchoolYearId = computed({
    get: () =>
        selectedSchoolYear.value && selectedSchoolYear.value
            ? selectedSchoolYear.value.id
            : null,
    set: (newValue) => {
        if (selectedSchoolYear.value) {
            selectedSchoolYear.value.id = newValue;
        }
    },
});

const computedSemesterId = computed({
    get: () =>
        selectedSemester.value && selectedSemester.value
            ? selectedSemester.value.id
            : null,
    set: (newValue) => {
        if (selectedSemester.value) {
            selectedSemester.value.id = newValue;
        }
    },
});

const computedQuarterId = computed({
    get: () =>
        selectedQuarter.value && selectedQuarter.value
            ? selectedQuarter.value.id
            : null,
    set: (newValue) => {
        if (selectedQuarter.value) {
            selectedQuarter.value.id = newValue;
        }
    },
});

watch(
    [selectedSchoolYear, selectedSemester, selectedQuarter],
    () => {
        applyFilters();
    },
    {
        deep: true,
    }
);

function applyFilters() {
    if (selectedSchoolYear.value) {
        const schoolYearOption = schoolYearOptions.value.find(
            (item) => item.value === selectedSchoolYear.value.id
        );
        if (schoolYearOption) {
            selectedSchoolYear.value.name = schoolYearOption.label;
        }
    }

    if (selectedSemester.value) {
        const semesterOption = semesterOptions.value.find(
            (item) => item.value === selectedSemester.value.id
        );
        if (semesterOption) {
            selectedSemester.value.name = semesterOption.label;
        }
    }

    if (selectedQuarter.value) {
        const quarterOption = quarterOptions.value.find(
            (item) => item.value === selectedQuarter.value.id
        );
        if (quarterOption) {
            selectedQuarter.value.name = quarterOption.label;
        }
    }

    const params = {
        batch_subject_id: batchSubjectId,
        school_year_id: selectedSchoolYear.value
            ? selectedSchoolYear.value.id
            : null,
        semester_id: selectedSemester.value ? selectedSemester.value.id : null,
        quarter_id: selectedQuarter.value ? selectedQuarter.value.id : null,
    };

    router.get(
        "/teacher/students/" + usePage().props.student.id,
        {
            ...params,
        },
        {
            preserveState: true,
        }
    );
}
</script>
<style scoped></style>
