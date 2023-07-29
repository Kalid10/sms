<template>
    <div class="flex h-fit w-full flex-col space-y-6">
        <div
            class="flex w-full flex-col items-center justify-center space-y-2 rounded-md border-2 border-black p-3"
        >
            <div class="flex w-full items-center justify-between">
                <div
                    class="font-semibold"
                    :class="
                        assessment.status === 'completed'
                            ? 'w-full  text-2xl'
                            : 'w-8/12 pl-4 text-black 2xl:text-xl'
                    "
                >
                    <span
                        class="-skew-x-3 bg-brand-450 px-2.5 py-0.5 italic text-white"
                    >
                        {{ assessment.assessment_type.name }} (

                        <span
                            class="font-bold"
                            :class="
                                assessment.status === 'completed'
                                    ? 'text-2xl'
                                    : 'text-base'
                            "
                            >{{ assessment.assessment_type.percentage }}%</span
                        >)
                    </span>
                </div>
                <div
                    v-if="isAssessmentRemaing"
                    class="w-full text-center text-xs font-semibold"
                >
                    {{ $t("assessmentTypeDetailInfo.youHave") }}
                    <span class="font-bold">
                        {{
                            Number(
                                props.assessment.assessment_type.max_assessments
                            ) -
                            Number(
                                props.assessment.assessment_type_completed_count
                            )
                        }}
                    </span>
                    {{ $t("assessmentTypeDetailInfo.assessmentsRemaining") }}
                </div>
            </div>

            <div class="flex w-full divide-x divide-gray-200 py-2">
                <div class="flex w-4/12 flex-col space-y-1 text-center">
                    <div class="text-2xl font-semibold">
                        {{ assessment.maximum_point }}
                    </div>
                    <div class="text-[0.65rem] font-light">
                        {{ $t("assessmentTypeDetailInfo.maxPts") }}
                    </div>
                </div>
                <div
                    v-if="!isSingleAssessmentType"
                    class="flex w-8/12 divide-x divide-gray-200"
                >
                    <div
                        class="flex w-1/2 flex-col items-center justify-center space-y-1 text-center"
                    >
                        <div class="text-2xl font-semibold">
                            {{ assessment.assessment_type_completed_count }}
                        </div>
                        <div class="text-[0.62rem] font-light uppercase">
                            {{ $t("assessmentTypeDetailInfo.previous") }}

                            {{ assessment.assessment_type.name }}
                            {{ $t("assessmentTypeDetailInfo.count") }}
                        </div>
                    </div>
                    <div class="flex w-1/2 flex-col space-y-1 text-center">
                        <div class="text-2xl font-semibold">
                            {{ assessment.assessment_type_points_sum }}
                        </div>
                        <div class="text-[0.62rem] font-light uppercase">
                            Previous {{ assessment.assessment_type.name }}
                            {{ $t("assessmentTypeDetailInfo.totalPts") }}
                        </div>
                    </div>
                </div>
                <div
                    v-else
                    class="flex w-8/12 flex-col items-center justify-center space-y-1 text-center text-xs font-semibold"
                >
                    <div v-if="assessment.status === 'completed'">
                        {{ $t("assessmentTypeDetailInfo.assessmentCompleted") }}
                    </div>
                    <div v-else>
                        {{
                            $t(
                                "assessmentTypeDetailInfo.hintAssessmentAdministered"
                            )
                        }}
                    </div>
                </div>
            </div>

            <div
                class="flex w-full items-center justify-center bg-brand-50 px-4 py-2 text-[0.65rem] font-light"
            >
                <div class="text-center">
                    {{ $t("assessmentTypeDetailInfo.atTheConclusion") }}
                    <span class="font-semibold"
                        >{{ assessment.assessment_type.name }}s</span
                    >
                    {{ $t("assessmentTypeDetailInfo.willBeConverted") }}
                    <span class="font-semibold"
                        >{{ assessment.assessment_type.percentage }}%</span
                    >
                    {{ $t("assessmentTypeDetailInfo.ofTheFinalGrade") }}
                    <span v-if="assessment.assessment_type_completed_count > 0">
                        {{ $t("assessmentTypeDetailInfo.note") }}
                    </span>
                </div>
            </div>
            <div
                v-if="assessment.status !== 'completed' && isTeacher()"
                class="my-1 flex w-11/12 cursor-pointer justify-center rounded-2xl bg-brand-450 py-1.5 text-center text-[0.6rem] font-semibold text-white lg:w-1/2 2xl:w-5/12 2xl:text-xs"
                @click="$emit('update')"
            >
                {{ $t("assessmentTypeDetailInfo.updateAssessment") }}
            </div>
            <LinkCell
                v-if="assessment.lesson_plan_id"
                class="flex w-full justify-end"
                href="/teacher/lesson-plan"
                :value="'LessonPlan #' + assessment.lesson_plan_id"
            />
        </div>
    </div>
</template>
<script setup>
import LinkCell from "@/Components/LinkCell.vue";
import { computed } from "vue";
import { isTeacher } from "@/utils";

defineEmits("update");

const props = defineProps({
    assessment: {
        type: Object,
        required: true,
    },
});

const isSingleAssessmentType = computed(() => {
    return (
        !props.assessment.assessment_type.customizable &&
        props.assessment.assessment_type.max_assessments === 1
    );
});

const isAssessmentRemaing = computed(() => {
    return (
        Number(props.assessment.assessment_type.max_assessments) >
            Number(props.assessment.assessment_type_completed_count) &&
        !props.assessment.assessment_type.customizable &&
        !isSingleAssessmentType.value
    );
});
</script>
<style scoped></style>
