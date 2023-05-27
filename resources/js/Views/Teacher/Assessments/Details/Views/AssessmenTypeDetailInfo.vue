<template>
    <div class="flex h-fit w-full flex-col items-center space-y-4">
        <div
            class="flex w-full flex-col space-y-1 rounded-md border-2 border-black p-3"
        >
            <div class="flex w-full items-center justify-between">
                <div class="w-8/12 text-xl font-semibold">
                    {{ assessment.assessment_type.name }} (
                    <span class="text-base font-bold"
                        >{{ assessment.assessment_type.percentage }}%</span
                    >)
                </div>
                <div
                    v-if="assessment.status !== 'completed'"
                    class="my-1 w-4/12 cursor-pointer text-end text-[0.65rem] text-black underline-offset-2 hover:font-medium hover:underline"
                    @click="$emit('update')"
                >
                    UPDATE ASSESSMENT
                </div>
            </div>

            <div
                class="flex w-full justify-between divide-x divide-gray-200 py-2"
            >
                <div class="flex w-3/12 flex-col space-y-1 text-center">
                    <div class="text-2xl font-semibold">
                        {{ assessment.maximum_point }}
                    </div>
                    <div class="text-[0.65rem] font-light">MAX PTS</div>
                </div>
                <div class="flex w-8/12 items-center text-center">
                    <div class="px-2 text-xs font-medium">
                        Final Quarterly Tests are given only once per quarter
                    </div>
                </div>
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

defineProps({
    assessment: {
        type: Object,
        required: true,
    },
});
defineEmits("update");
</script>
<style scoped></style>
