<template>
    <div
        class="scrollbar-hide flex w-full space-x-2 overflow-x-auto lg:flex-wrap"
        :class="showFilter ? 'blur-sm' : 'blur-none'"
    >
        <FilterItem
            v-if="selectedBatchSubjectName"
            :value="selectedBatchSubjectName"
            class-style="bg-emerald-400"
            icon-style="text-black"
            :show-remove-icon="false"
        />
        <FilterItem
            v-if="status"
            :value="capitalize(status)"
            class-style="bg-red-500 text-white"
            icon-style="text-gray-200"
            @remove="$emit('removeStatus')"
        />
        <FilterItem
            v-if="search"
            :value="search"
            class-style="bg-purple-500 text-white"
            icon-style="text-fuchsia-200 hover:text-black"
            @remove="$emit('removeSearch')"
        />
        <FilterItem
            v-if="selectedAssessmentTypeName"
            :value="selectedAssessmentTypeName"
            @remove="$emit('removeAssessmentType')"
        />
        <FilterItem
            v-if="selectedSchoolYear"
            :value="selectedSchoolYear"
            class-style="bg-neutral-700 text-white"
            icon-style="text-gray-200"
            @remove="$emit('removeSchoolYear')"
        />
        <FilterItem
            v-if="selectedSemester"
            :value="selectedSemester"
            class-style="bg-teal-400 text-black"
            icon-style="text-black hover:text-black"
            @remove="$emit('removeSemester')"
        />
        <FilterItem
            v-if="selectedQuarter"
            :value="selectedQuarter"
            class-style="bg-cyan-400"
            icon-style="text-black"
            @remove="$emit('removeQuarter')"
        />
        <PlusIcon
            v-if="!showFilter"
            class="hover:w-4.5 w-4 cursor-pointer hover:text-black"
            @click="$emit('show')"
        />
    </div>
</template>
<script setup>
import FilterItem from "@/Views/Teacher/Assessments/Table/Filters/FilterItem.vue";
import { PlusIcon } from "@heroicons/vue/20/solid";
import { capitalize } from "vue";

defineEmits([
    "show",
    "removeQuarter",
    "removeSemester",
    "removeSchoolYear",
    "removeAssessmentType",
    "removeBatchSubject",
    "removeSearch",
    "removeStatus",
]);

const props = defineProps({
    showFilter: {
        type: Boolean,
        required: true,
    },
    selectedAssessmentTypeName: {
        type: String,
        default: null,
    },
    selectedBatchSubjectName: {
        type: String,
        default: null,
    },
    selectedSchoolYear: {
        type: String,
        default: null,
    },
    selectedSemester: {
        type: String,
        default: null,
    },
    selectedQuarter: {
        type: String,
        default: null,
    },
    search: {
        type: String,
        default: null,
    },
    status: {
        type: String,
        default: null,
    },
});
</script>
<style scoped></style>
