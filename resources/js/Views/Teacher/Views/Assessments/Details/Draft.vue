<template>
    <div class="flex h-fit w-full flex-col items-center space-y-6">
        <div class="w-full text-center font-semibold text-black">
            <span v-html:="$t('draft.currentlyFull')"></span>
        </div>
        <div
            class="flex w-full bg-brand-50 px-4 py-2 text-center text-[0.65rem] font-light"
        >
            <span v-html:="$t('draft.settingAssessmentFull')"></span>
        </div>
        <div
            class="flex w-full flex-col items-center justify-center space-y-3 pt-4"
        >
            <div
                class="flex h-8 w-11/12 items-center justify-center rounded-2xl bg-emerald-400 text-white lg:w-6/12"
            >
                <DocumentCheckIcon class="w-4 text-white" />
                <SecondaryButton
                    :title="$t('draft.publishAssessment')"
                    class="w-fit font-semibold"
                    @click="
                        status = 'published';
                        showDialog = true;
                    "
                />
            </div>
            <div class="font-bold">{{ $t("draft.or") }}</div>
            <div
                class="flex h-8 w-11/12 items-center justify-center rounded-2xl bg-cyan-400 lg:w-6/12"
            >
                <CalendarDaysIcon class="w-4 text-white" />
                <SecondaryButton
                    :title="$t('draft.scheduleAssessment')"
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
                    class="flex h-8 w-10/12 items-center justify-center rounded-2xl bg-brand-450 shadow-md lg:w-8/12"
                >
                    <CheckCircleIcon class="w-4 text-white" />
                    <SecondaryButton
                        :title="$t('draft.finishUpdating')"
                        class="font-semibold uppercase text-white"
                        @click="showDialog = true"
                    />
                </div>
            </div>
        </div>

        <DialogBox
            v-model:open="showDialog"
            type="update"
            :title="$t('draft.updateAssessment')"
            @confirm="updateAssessment"
        >
            <template #description>
                {{ $t("draft.alertMessage") }}
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
    router.post(
        "/teacher/assessments/update",
        {
            title: props.assessment.title,
            description: props.assessment.description,
            maximum_point: props.assessment.maximum_point,
            assessment_id: props.assessment.id,
            status: status.value,
            due_date: dueDate.value,
        },
        {
            preserveState: true,
        }
    );
}
</script>
<style scoped></style>
