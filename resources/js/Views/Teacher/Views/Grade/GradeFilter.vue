<template>
    <div class="flex items-center justify-between space-x-2">
        <SelectInput
            v-model="selectedSchoolYear"
            class="h-fit w-1/3 rounded-2xl !text-sm"
            :options="schoolYearOptions"
            placeholder="school year"
        />
        <SelectInput
            v-model="selectedSemester"
            class="h-fit w-1/3 rounded-2xl !text-sm"
            :options="semesterOptions"
            placeholder="Semester"
        />
        <SelectInput
            v-model="selectedQuarter"
            class="h-fit w-1/3 rounded-2xl !text-sm"
            :options="quarterOptions"
            placeholder="quarter"
        />
    </div>

    <div class="flex items-center space-x-2">
        <div
            v-if="selectedSchoolYear"
            class="flex items-center space-x-2 rounded-lg bg-zinc-700 p-2 text-white"
        >
            <span class="text-xs">School Year:</span>
            <span class="text-xs font-semibold">
                {{ selectedSchoolYear }}
            </span>
            <span
                class="cursor-pointer text-xs font-semibold"
                @click="
                    selectedSchoolYear = null;
                    selectedSemester = null;
                    selectedQuarter = null;
                "
            >
                <XMarkIcon class="h-3 w-3 fill-red-500" />
            </span>
        </div>
        <div
            v-if="selectedSemester"
            class="flex items-center space-x-2 rounded-lg bg-zinc-700 p-2 text-white"
        >
            <span class="text-xs">Semester:</span>
            <span class="text-xs font-semibold">
                {{ selectedSemester }}
            </span>
            <span
                class="cursor-pointer text-xs font-semibold"
                @click="
                    selectedSemester = null;
                    selectedQuarter = null;
                "
            >
                <XMarkIcon class="h-3 w-3 fill-red-500" />
            </span>
        </div>
        <div
            v-if="selectedQuarter"
            class="flex items-center space-x-2 rounded-lg bg-zinc-700 p-2 text-white"
        >
            <span class="text-xs">Quarter:</span>
            <span class="text-xs font-semibold">
                {{ selectedQuarter }}
            </span>
            <span
                class="cursor-pointer text-xs font-semibold"
                @click="selectedQuarter = null"
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

const selectedSchoolYear = ref(null);

const selectedSemester = ref(null);

const selectedQuarter = ref(null);

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
            return item.school_year_id === selectedSchoolYear.value;
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
            return item.semester_id === selectedSemester.value;
        })
        .map((item) => {
            return {
                label: item.name,
                value: item.id,
            };
        });
});

watch([selectedSchoolYear, selectedSemester, selectedQuarter], () => {
    applyFilters();
});

function applyFilters() {
    const params = {
        batch_subject_id: usePage().props.batch_subject.id,
        semester_id: selectedQuarter.value ? null : selectedSemester.value,
        quarter_id: selectedQuarter.value,
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
