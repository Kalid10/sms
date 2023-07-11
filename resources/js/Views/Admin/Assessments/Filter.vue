<template>
    <div
        class="flex w-full flex-col justify-between space-y-4 rounded-lg bg-zinc-50 p-3 text-black shadow-sm"
    >
        <Loading v-if="showLoading" is-full-screen color="secondary" />
        <div class="pl-2 text-sm font-light text-black">{{ $t('assessmentsFilter.filters') }} </div>
        <div class="flex w-full justify-between">
            <SelectInput
                v-if="levelOptions?.length"
                v-model="selectedLevel"
                :options="levelOptions"
                :placeholder="
                    selectedLevel
                        ? levelOptions?.find(
                              (option) => option.value === selectedLevel
                          )?.label
                        : $t('assessmentsFilter.selectClass')
                "
                class="w-5/12"
            />
            <SelectInput
                v-if="subjectOptions?.length"
                v-model="selectedSubject"
                :options="subjectOptions"
                :placeholder="
                    selectedSubject
                        ? subjectOptions?.find(
                              (option) => option.value === selectedSubject
                          )?.label
                        : $t('assessmentsFilter.selectSubject')
                "
                class="w-5/12"
            />
        </div>

        <div class="flex w-full justify-between">
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
                        : $t('assessmentsFilter.selectAssessmentType')
                "
                class="w-5/12"
            />
            <TextInput
                v-model="query"
                class="w-5/12"
                :placeholder="$t('assessmentsFilter.searchAssessment')"
            />
        </div>
    </div>
</template>
<script setup>
import { computed, ref, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import SelectInput from "@/Components/SelectInput.vue";
import TextInput from "@/Components/TextInput.vue";
import Loading from "@/Components/Loading.vue";

const emit = defineEmits(["filter"]);
const showLoading = ref(false);

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
    showLoading.value = true;
    const params = {
        level_id: selectedLevel.value,
        subject_id: selectedSubject.value,
        assessment_type_id: selectedAssessmentType.value,
    };
    router.get("/admin/assessments", params, {
        preserveState: true,
        onFinish: () => {
            showLoading.value = false;
        },
    });
}

const query = ref(usePage().props.filters.search);

function search() {
    showLoading.value = true;
    router.get(
        "/admin/assessments/",
        {
            search: query.value,
        },
        {
            preserveState: true,
            onFinish: () => {
                showLoading.value = false;
            },
        }
    );
}

const key = ref(0);
watch([query], () => {
    search();
});
</script>
<style scoped></style>
