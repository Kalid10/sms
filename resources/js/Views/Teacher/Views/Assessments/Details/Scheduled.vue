<template>
    <div
        class="flex w-full flex-col items-center justify-center space-y-5 rounded-lg border-2 border-black bg-emerald-400 p-4"
    >
        <div class="w-full rounded-lg p-2 text-center text-xs">
            Setting an assessment as
            <span class="bg-emerald-200 px-1 font-semibold">' PUBLISHED '</span>
            will trigger immediate notifications to guardians and principals.
            Detailed information about the assessment can be accessed for
            further insight.
        </div>
        <SecondaryButton
            :title="$t('scheduled.publishAssessment')"
            class="w-32 rounded-xl border-2 border-black bg-emerald-200 font-semibold uppercase text-black lg:w-56"
            @click="showDialog = true"
        />

        <DialogBox
            v-model:open="showDialog"
            type="update"
            :title="$t('scheduled.publishAssessment')"
            @confirm="updateAssessment"
        >
            <template #description>
                {{ $t("scheduled.alertMessage") }}
            </template>
        </DialogBox>
    </div>
</template>
<script setup>
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { router } from "@inertiajs/vue3";
import DialogBox from "@/Components/DialogBox.vue";
import { inject, ref } from "vue";

const props = defineProps({
    assessment: {
        type: Object,
        required: true,
    },
});

const showDialog = ref(false);

const showNotification = inject("showNotification");

function updateAssessment() {
    router.post(
        "/teacher/assessments/update",
        {
            title: props.assessment.title,
            description: props.assessment.description,
            maximum_point: props.assessment.maximum_point,
            assessment_id: props.assessment.id,
            status: "published",
            due_date: props.assessment.due_date,
        },
        {
            onSuccess: () => {
                showNotification({
                    title: "Assessment Published",
                    message: "Assessment has been published successfully",
                    type: "success",
                });
            },
        }
    );
}
</script>
<style scoped></style>
