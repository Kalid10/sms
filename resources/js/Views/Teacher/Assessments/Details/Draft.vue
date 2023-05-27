<template>
    <div
        v-if="assessment.status === 'draft'"
        class="flex h-fit w-full flex-col items-center space-y-4"
    >
        <div
            class="rounded-md bg-gradient-to-br from-red-600 to-orange-500 py-3 px-4 text-sm font-medium text-white"
        >
            This assessment is currently on draft, click
            <span
                class="cursor-pointer text-red-900 underline-offset-2 hover:text-black hover:underline"
                @click="showDialog = true"
                >here</span
            >
            to publish it!
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
import DialogBox from "@/Components/DialogBox.vue";
import { computed, ref } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    assessment: {
        type: Object,
        required: true,
    },
});

const showDialog = ref(false);

const title = computed(
    () =>
        props.assessment.title +
        " " +
        props.assessment.batch_subject.batch.level.name +
        props.assessment.batch_subject.batch.section
);

function updateAssessment() {
    router.post("/teacher/assessments/publish/" + props.assessment.id);
}
</script>
<style scoped></style>
