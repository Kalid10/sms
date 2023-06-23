<template>
    <Modal v-model:view="showFilter">
        <div
            class="flex w-1/2 flex-col justify-end space-y-3 rounded-lg bg-white p-4 text-center"
        >
            <div class="flex flex-col space-y-2">
                <div class="px-2 text-sm">Filters</div>

                <div class="flex flex-row items-center space-x-2">
                    <SelectInput
                        v-model="selectedSchoolYear"
                        :options="schoolYearOptions"
                        placeholder="school year"
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
                        placeholder="semester"
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
                        placeholder="quarter"
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
                        Apply
                    </button>
                </div>
            </div>
        </div>
    </Modal>
</template>

<script setup>
import SelectInput from "@/Components/SelectInput.vue";
import { computed, ref } from "vue";
import Modal from "@/Components/Modal.vue";

const emit = defineEmits(["filter"]);
const props = defineProps({
    schoolYears: {
        type: Array,
        default: () => [],
    },
    semesters: {
        type: Array,
        default: () => [],
    },
    quarters: {
        type: Array,
        default: () => [],
    },
});

const showFilter = ref(true);

const selectedSchoolYear = ref();

const selectedSemester = ref();

const selectedQuarter = ref();

const schoolYearOptions = computed(() => {
    return props.schoolYears?.map((schoolYear) => {
        return {
            value: schoolYear.id,
            label: schoolYear.name,
        };
    });
});
const semesterOptions = computed(() => {
    return props.semesters
        ?.filter(
            (semester) => semester.school_year_id === selectedSchoolYear.value
        )
        .map((semester) => {
            return {
                value: semester.id,
                label: semester.name,
            };
        });
});

const quarterOptions = computed(() => {
    return props.quarters
        ?.filter((quarter) => quarter.semester_id === selectedSemester.value)
        .map((quarter) => {
            return {
                value: quarter.id,
                label: quarter.name,
            };
        });
});

function resetFilters() {
    selectedSchoolYear.value = null;
    selectedSemester.value = null;
    selectedQuarter.value = null;
}

function applyFilters() {
    const params = {
        school_year_id: selectedSchoolYear.value ?? null,
        semester_id: selectedSemester.value ?? null,
        quarter_id: selectedQuarter.value ?? null,
    };
    emit("filter", params);
    resetFilters();
    showFilter.value = false;
}
</script>

<!--<script setup>-->
<!--import SelectInput from "@/Components/SelectInput.vue";-->
<!--import { computed, ref } from "vue";-->
<!--import { router, usePage } from "@inertiajs/vue3";-->
<!--import Modal from "@/Components/Modal.vue";-->

<!--const showFilter = ref(true);-->

<!--const schoolYears = computed(() => usePage().props.school_years);-->

<!--const semesters = computed(() => usePage().props.semesters);-->

<!--const quarters = computed(() => usePage().props.quarters);-->

<!--const pageProps = usePage().props;-->
<!--const { filters } = pageProps;-->

<!--const selectedSchoolYear = ref(-->
<!--    schoolYears.value?.find(-->
<!--        (schoolYear) => schoolYear.id === filters.school_year_id-->
<!--    ) ?? null-->
<!--);-->

<!--const selectedSemester = ref(-->
<!--    semesters.value?.find((semester) => semester.id === filters.semester_id) ??-->
<!--        null-->
<!--);-->

<!--const selectedQuarter = ref(-->
<!--    quarters.value?.find((quarter) => quarter.id === filters.quarter_id) ?? null-->
<!--);-->

<!--const schoolYearOptions = computed(() => {-->
<!--    return schoolYears.value?.map((schoolYear) => {-->
<!--        return {-->
<!--            value: schoolYear.id,-->
<!--            label: schoolYear.name,-->
<!--        };-->
<!--    });-->
<!--});-->
<!--const semesterOptions = computed(() => {-->
<!--    return semesters.value-->
<!--        ?.filter(-->
<!--            (semester) => semester.school_year_id === selectedSchoolYear.value-->
<!--        )-->
<!--        .map((semester) => {-->
<!--            return {-->
<!--                value: semester.id,-->
<!--                label: semester.name,-->
<!--            };-->
<!--        });-->
<!--});-->

<!--const quarterOptions = computed(() => {-->
<!--    return quarters.value-->
<!--        ?.filter((quarter) => quarter.semester_id === selectedSemester.value)-->
<!--        .map((quarter) => {-->
<!--            return {-->
<!--                value: quarter.id,-->
<!--                label: quarter.name,-->
<!--            };-->
<!--        });-->
<!--});-->

<!--function resetFilters() {-->
<!--    selectedSchoolYear.value = null;-->
<!--    selectedSemester.value = null;-->
<!--    selectedQuarter.value = null;-->
<!--}-->

<!--function applyFilters() {-->
<!--    const params = {-->
<!--        school_year_id: selectedSchoolYear.value ?? null,-->
<!--    };-->

<!--    return router.get("/admin/announcements", params, {-->
<!--        onSuccess: () => {-->
<!--            showFilter.value = false;-->
<!--            resetFilters();-->
<!--        },-->
<!--        preserveState: true,-->
<!--    });-->
<!--}-->
<!--</script>-->
<style scoped></style>
