<template>
    <div class="flex h-fit w-full flex-col items-center space-y-6">
        <div class="w-full text-center font-semibold text-black">
            Currently, this assessment is in
            <span
                class="-skew-x-3 bg-yellow-400 px-2 py-0.5 text-sm font-bold italic"
                >DRAFT</span
            >
            state! You can:
        </div>
        <div
            class="flex w-full bg-gray-50 px-4 py-2 text-center text-[0.65rem] font-light"
        >
            <InformationCircleIcon class="mr-2 w-7 text-zinc-800" />

            <div>
                Setting an assessment as
                <span class="font-semibold">" PUBLISHED "</span> or
                <span class="font-semibold">" SCHEDULED "</span>
                will trigger immediate notifications to guardians and
                principals. Detailed information about the assessment can be
                accessed for further insight.
            </div>
        </div>
        <div
            class="flex w-full flex-col items-center justify-center space-y-3 pt-4"
        >
            <div
                class="flex h-8 w-11/12 items-center justify-center rounded-2xl bg-emerald-400 text-white lg:w-6/12"
            >
                <DocumentCheckIcon class="w-4 text-gray-700" />
                <SecondaryButton
                    title="Publish Assessment"
                    class="w-fit font-semibold"
                    @click="
                        status = 'published';
                        showDialog = true;
                    "
                />
            </div>
            <div class="font-bold">OR</div>
            <div
                class="flex h-8 w-11/12 items-center justify-center rounded-2xl bg-cyan-400 lg:w-6/12"
            >
                <CalendarDaysIcon class="w-4 text-gray-700" />
                <SecondaryButton
                    title="Schedule Assessment"
                    class="w-fit font-semibold"
                    @click="status = 'scheduled'"
                />
            </div>

            <!--            Submit schedule section-->
            <div
                v-if="status === 'scheduled'"
                class="flex w-full animate-scale-up flex-col items-center justify-center space-y-5 rounded-md py-5 lg:w-8/12"
            >
                <DatePicker v-model="dueDate" class="w-11/12 lg:w-8/12" />

                <div
                    v-if="dueDate && status === 'scheduled'"
                    class="flex h-8 w-10/12 items-center justify-center rounded-2xl bg-violet-500 shadow-md lg:w-9/12"
                >
                    <CheckCircleIcon class="w-4 text-zinc-100" />
                    <SecondaryButton
                        title="FINISH UPDATING"
                        class="font-semibold text-white"
                        @click="showDialog = true"
                    />
                </div>
            </div>
        </div>

        <DialogBox
            v-model:open="showDialog"
            type="update"
            title="Update Assessment"
            @confirm="updateAssessment"
        >
            <template #description>
                Performing this action will result significant change across the
                entire subject, Are you sure you want to proceed?
            </template>
        </DialogBox>
    </div>
</template>
<script setup>
import { computed, ref } from "vue";
import {
    CalendarDaysIcon,
    CheckCircleIcon,
    DocumentCheckIcon,
} from "@heroicons/vue/24/solid";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { router } from "@inertiajs/vue3";
import DialogBox from "@/Components/DialogBox.vue";
import { InformationCircleIcon } from "@heroicons/vue/24/outline";
import DatePicker from "@/Components/DatePicker.vue";

const props = defineProps({
    assessment: {
        type: Object,
        required: true,
    },
});
const status = ref(props.assessment.status);
const dueDate = ref(new Date(props.assessment.due_date));

const title = computed(
    () =>
        props.assessment.title +
        " " +
        props.assessment.batch_subject.batch.level.name +
        props.assessment.batch_subject.batch.section
);

const showDialog = ref(false);

function updateAssessment() {
    router.post("/teacher/assessments/update", {
        title: title.value,
        description: props.assessment.description,
        maximum_point: props.assessment.maximum_point,
        assessment_id: props.assessment.id,
        status: status.value,
        due_date: dueDate.value,
    });
}
</script>
<style scoped></style>
