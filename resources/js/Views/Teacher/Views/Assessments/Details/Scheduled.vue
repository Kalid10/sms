<template>
    <div class="flex w-full flex-col items-center justify-center space-y-5">
        <div class="w-full text-center font-semibold text-black">
            <span v-html:="$t('scheduled.currentlyFull')"></span>
        </div>
        <SecondaryButton
            :title="$t('scheduled.publishAssessment')"
            class="w-44 rounded-xl bg-emerald-400 font-semibold uppercase lg:w-56"
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
import { ref } from "vue";

const props = defineProps({
    assessment: {
        type: Object,
        required: true,
    },
});

const showDialog = ref(false);

function updateAssessment() {
    router.post("/teacher/assessments/update", {
        title: props.assessment.title,
        description: props.assessment.description,
        maximum_point: props.assessment.maximum_point,
        assessment_id: props.assessment.id,
        status: "published",
        due_date: props.assessment.due_date,
    });
}
</script>
<style scoped></style>
