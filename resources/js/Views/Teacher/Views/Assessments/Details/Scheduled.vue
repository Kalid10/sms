<template>
    <div class="flex w-full flex-col items-center justify-center space-y-5">
        <div class="w-full text-center font-semibold text-black">
            Currently, this assessment is in
            <span
                class="mx-0.5 -skew-x-3 bg-indigo-400 px-2 py-0.5 text-sm font-bold italic text-white"
                >SCHEDULED
            </span>
            state! You can:
        </div>
        <SecondaryButton
            title="PUBLISH ASSESSMENT"
            class="w-44 rounded-xl bg-emerald-400 font-semibold lg:w-56"
            @click="showDialog = true"
        />
        <DialogBox
            v-model:open="showDialog"
            type="update"
            title="Publish Assessment"
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
