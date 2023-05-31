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
                        class="-skew-x-3 bg-zinc-800 px-2.5 py-0.5 italic text-white"
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
                    You have
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
                    assessments remaining for this quarter.
                </div>
            </div>

            <div class="flex w-full divide-x divide-gray-200 py-2">
                <div class="flex w-4/12 flex-col space-y-1 text-center">
                    <div class="text-2xl font-semibold">
                        {{ assessment.maximum_point }}
                    </div>
                    <div class="text-[0.65rem] font-light">MAX PTS</div>
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
                            Previous
                            {{ assessment.assessment_type.name }} COUNT
                        </div>
                    </div>
                    <div class="flex w-1/2 flex-col space-y-1 text-center">
                        <div class="text-2xl font-semibold">
                            {{ assessment.assessment_type_points_sum }}
                        </div>
                        <div class="text-[0.62rem] font-light uppercase">
                            Previous {{ assessment.assessment_type.name }} TOTAL
                            PTS
                        </div>
                    </div>
                </div>
                <div
                    v-else
                    class="flex w-8/12 flex-col items-center justify-center space-y-1 text-center text-xs font-semibold"
                >
                    <div v-if="assessment.status === 'completed'">
                        You have successfully completed this assessment, and as
                        a result, any further modifications or creation of new
                        assessments are no longer permitted.
                    </div>
                    <div v-else>
                        This assessment is administered on a quarterly basis, so
                        please ensure that you are taking the appropriate
                        actions accordingly.
                    </div>
                </div>
            </div>

            <div
                class="flex w-full items-center justify-center bg-gray-50 px-4 py-2 text-[0.65rem] font-light"
            >
                <div class="text-center">
                    At the conclusion of the quarter or semester, all the

                    <span class="font-semibold"
                        >{{ assessment.assessment_type.name }}s</span
                    >
                    will be converted to account for
                    <span class="font-semibold"
                        >{{ assessment.assessment_type.percentage }}%</span
                    >
                    of the final grade. Note: This does not include this
                    assessment.
                </div>
            </div>
            <div
                v-if="assessment.status !== 'completed'"
                class="my-1 flex w-11/12 cursor-pointer justify-center rounded-2xl bg-zinc-800 py-1.5 text-center text-[0.6rem] font-semibold text-white lg:w-1/2 2xl:w-5/12 2xl:text-xs"
                @click="$emit('update')"
            >
                UPDATE ASSESSMENT
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
