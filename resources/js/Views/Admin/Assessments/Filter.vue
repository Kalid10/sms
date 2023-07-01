<template>
    <div class="flex w-full justify-between space-x-1">
        <div class="flex w-full">
            <SelectInput
                v-if="levelOptions?.length"
                v-model="selectedLevel"
                :options="levelOptions"
                :placeholder="
                    selectedLevel
                        ? levelOptions?.find(
                              (option) => option.value === selectedLevel
                          )?.label
                        : 'level'
                "
                class="w-full"
            />
        </div>

        <div class="flex w-full">
            <SelectInput
                v-if="subjectOptions?.length"
                v-model="selectedSubject"
                :options="subjectOptions"
                :placeholder="
                    selectedSubject
                        ? subjectOptions?.find(
                              (option) => option.value === selectedSubject
                          )?.label
                        : 'subject'
                "
                class="w-full"
            />
        </div>

        <div class="flex w-full">
            <SelectInput
                v-if="assessmentTypeOptions?.length"
                v-model="selectedAssessmentType"
                :options="assessmentTypeOptions"
                :placeholder="
                    selectedAssessmentType
                        ? assessmentTypeOptions?.find(
                              (option) =>
                                  option.value === selectedAssessmentType
                          )?.label
                        : 'assessment type'
                "
                class="w-full"
            />
        </div>
    </div>
</template>
<script setup>
import { computed, ref, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import SelectInput from "@/Components/SelectInput.vue";

const emit = defineEmits(["filter"]);

const selectedLevel = ref(Number(usePage().props.filters.level));
const selectedSubject = ref(Number(usePage().props.filters.subject));
const selectedAssessmentType = ref(
    Number(usePage().props.filters.assessment_type)
);

const levels = computed(() => usePage().props.filters.levels);
const subjects = computed(() => usePage().props.filters.subjects);
const assessmentTypes = computed(
    () => usePage().props.filters.assessment_types
);

const levelOptions = computed(() => {
    return levels.value.map((level) => {
        return {
            label: `Grade ${level.name}`,
            value: level.id,
        };
    });
});

const subjectOptions = computed(() => {
    return subjects.value.map((subject) => {
        return {
            label: subject.full_name,
            value: subject.id,
        };
    });
});

const assessmentTypeOptions = computed(() => {
    return assessmentTypes.value.map((assessmentType) => {
        return {
            label:
                assessmentType.name +
                `( ${assessmentType.level_category.name} )`,
            value: assessmentType.id,
        };
    });
});

watch([selectedLevel, selectedSubject, selectedAssessmentType], applyFilters);

function applyFilters() {
    const params = {
        level_id: selectedLevel.value,
        subject_id: selectedSubject.value,
        assessment_type_id: selectedAssessmentType.value,
    };
    router.get("/admin/assessments", params, {
        preserveState: true,
    });
}
</script>
<style scoped></style>
