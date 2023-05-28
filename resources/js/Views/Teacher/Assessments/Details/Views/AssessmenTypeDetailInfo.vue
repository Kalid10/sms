<template>
    <div class="flex h-fit w-full flex-col space-y-6">
        <div
            class="flex w-full flex-col items-center justify-center space-y-1 rounded-md border-2 border-black p-3"
        >
            <div class="flex w-full items-center justify-between">
                <div
                    class="font-semibold"
                    :class="
                        assessment.status === 'completed'
                            ? 'w-full text-center text-2xl'
                            : 'w-8/12 2xl:text-xl'
                    "
                >
                    {{ assessment.assessment_type.name }} (
                    <span class="text-base font-bold"
                        >{{ assessment.assessment_type.percentage }}%</span
                    >)
                </div>
                <div
                    v-if="assessment.status !== 'completed'"
                    class="my-1 flex w-4/12 cursor-pointer items-end justify-end text-end text-[0.6rem] text-black underline-offset-2 hover:font-medium hover:underline 2xl:text-[0.65rem]"
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

            <div
                v-if="assessment.status === 'draft'"
                class="flex w-11/12 items-center justify-center rounded-2xl bg-emerald-400 text-white lg:w-8/12"
            >
                <PencilIcon class="w-4 text-gray-700" />
                <SecondaryButton
                    title="Publish Assessment"
                    class="w-fit font-semibold"
                    @click="showDialog = true"
                />
                <DialogBox
                    v-model:open="showDialog"
                    type="update"
                    title="Update Assessment"
                    @confirm="updateAssessment"
                >
                    <template #description>
                        Performing this action will result significant change
                        across the entire subject, Are you sure you want to
                        proceed?
                    </template>
                </DialogBox>
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
import DialogBox from "@/Components/DialogBox.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { router } from "@inertiajs/vue3";
import { ref } from "vue";
import { PencilIcon } from "@heroicons/vue/24/solid";

defineEmits("update");

const props = defineProps({
    assessment: {
        type: Object,
        required: true,
    },
});

const showDialog = ref(false);

function updateAssessment() {
    router.post("/teacher/assessments/publish/" + props.assessment.id);
}
</script>
<style scoped></style>
