<template>
    <div class="flex flex-col">
        <div class="flex w-full items-center justify-between">
            <div class="text-xl font-medium">
                {{ $t("studentAssessments.recentAssessments") }}
            </div>

            <div
                v-if="assessmentTypeOptions.length > 0 && batchSubject"
                class="flex w-1/2 flex-col space-y-3 rounded-lg bg-white p-4 text-center"
            >
                <SelectInput
                    v-model="selectedAssessmentType"
                    :options="assessmentTypeOptions"
                    placeholder="Filter by Assessment Type"
                    class="w-full justify-end"
                />
            </div>
        </div>
    </div>

    <Item
        v-if="isAssessmentFound"
        class="mt-3"
        :assessments="assessments"
        view="student"
    />
    <div
        v-else
        class="flex h-32 flex-col items-center justify-center space-y-4 lg:h-44"
    >
        <EmptyView :title="$t('studentAssessments.noAssessmentsFound')" />
    </div>

    <div v-if="assessments?.data?.length" class="my-2 flex justify-center">
        <LinkCell
            class="flex w-fit items-center justify-end"
            :href="getAssessmentLink()"
            :value="$t('studentAssessments.seeAll')"
        />
    </div>
</template>
<script setup>
import Item from "@/Views/Teacher/Views/Home/Assessments/Item/Index.vue";
import LinkCell from "@/Components/LinkCell.vue";
import { computed, ref, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import EmptyView from "@/Views/EmptyView.vue";
import SelectInput from "@/Components/SelectInput.vue";
import { isTeacher } from "@/utils";

const showFilter = ref(false);

const assessments = computed(() => usePage().props.assessments);
const isAssessmentFound = computed(() => assessments.value.data?.length > 0);
const assessmentTypes = computed(() => usePage().props.assessment_types);

const selectedAssessmentType = ref(null);

watch(selectedAssessmentType, (value) => {
    if (value) {
        applyFilters();
    }
});

const assessmentTypeOptions = computed(() => {
    return assessmentTypes.value.map((assessmentType) => {
        return {
            label: assessmentType.name,
            value: assessmentType.id,
        };
    });
});

const getAssessmentLink = () => {
    if (isTeacher()) {
        return "/teacher/assessments";
    } else {
        return "/admin/assessments";
    }
};

const batchSubject = computed(() => usePage().props.batch_subject);

function applyFilters() {
    router.get(
        "/teacher/students/" + usePage().props.student.id,
        {
            batch_subject_id: usePage().props.batch_subject.id,
            assessment_type_id: selectedAssessmentType.value,
        },
        {
            onSuccess: () => {
                showFilter.value = false;
            },
            preserveState: true,
        }
    );
}
</script>
<style scoped></style>
