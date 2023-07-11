<template>
    <div
        class="flex w-1/2 flex-col justify-end space-y-3 rounded-lg bg-white p-4 text-center"
    >
        <div class="flex flex-col space-y-2">
            <div class="px-2 text-sm">{{ title }}</div>
            <div class="flex flex-row items-center space-x-2">
                <SelectInput
                    v-model="selectedSchoolYear"
                    :options="schoolYearOptions"
                    :placeholder="
                        selectedSchoolYear
                            ? schoolYearOptions?.find(
                                  (option) =>
                                      option.value === selectedSchoolYear
                              )?.label
                            : $t('filters.schoolYear')
                    "
                    class="w-full"
                />
            </div>

            <div
                v-if="selectedSchoolYear"
                class="flex flex-row items-center space-x-2"
            >
                <SelectInput
                    v-if="semesterOptions?.length"
                    v-model="selectedSemester"
                    :options="semesterOptions"
                    :placeholder="
                        selectedSemester
                            ? semesterOptions?.find(
                                  (option) => option.value === selectedSemester
                              )?.label
                            : $t('filters.semester')
                    "
                    class="w-full"
                />
            </div>

            <div
                v-if="selectedSchoolYear"
                class="flex flex-row items-center space-x-2"
            >
                <SelectInput
                    v-if="quarterOptions?.length"
                    v-model="selectedQuarter"
                    :options="quarterOptions"
                    :placeholder="
                        selectedQuarter
                            ? quarterOptions?.find(
                                  (option) => option.value === selectedQuarter
                              )?.label
                            : $t('filters.quarter')
                    "
                    class="w-full"
                />
            </div>

            <div
                v-if="selectedSchoolYear"
                class="flex flex-row items-center space-x-2"
            >
                <button
                    class="w-full rounded-lg bg-black px-4 py-2 text-white"
                    @click="applyFilters"
                >
                     {{ $t('common.apply') }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import SelectInput from "@/Components/SelectInput.vue";
import { computed, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const emit = defineEmits(["filter"]);
const props = defineProps({
    title: {
        type: String,
        default: "Filter",
    },
});

const selectedSchoolYear = ref(Number(usePage().props.filters.school_year_id));
const schoolYears = computed(() => usePage().props.filters.school_years);

const selectedSemester = ref(Number(usePage().props.filters.semester_id));
const semesters = computed(() => usePage().props.filters.semesters);

const selectedQuarter = ref(Number(usePage().props.filters.quarter_id));
const quarters = computed(() => usePage().props.filters.quarters);

const schoolYearOptions = computed(() => {
    return schoolYears.value?.map((schoolYear) => {
        return {
            value: Number(schoolYear.id),
            label: schoolYear.name,
        };
    });
});
const semesterOptions = computed(() => {
    return semesters.value
        ?.filter(
            (semester) => semester.school_year_id === selectedSchoolYear.value
        )
        .map((semester) => {
            return {
                value: Number(semester.id),
                label: semester.name,
            };
        });
});

const quarterOptions = computed(() => {
    return quarters.value
        ?.filter((quarter) => quarter.semester_id === selectedSemester.value)
        .map((quarter) => {
            return {
                value: Number(quarter.id),
                label: quarter.name,
            };
        });
});

function applyFilters() {
    const params = {
        school_year_id: selectedSchoolYear.value ?? null,
        semester_id: selectedSemester.value ?? null,
        quarter_id: selectedQuarter.value ?? null,
    };
    emit("filter", params);
}
</script>
<style scoped></style>
