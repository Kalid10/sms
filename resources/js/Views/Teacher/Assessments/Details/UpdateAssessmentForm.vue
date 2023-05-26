<template>
    <div ref="parentDiv" class="w-full rounded-md p-2">
        <div
            class="my-1 w-full cursor-pointer text-end text-xs text-zinc-800 underline underline-offset-2 hover:font-medium hover:text-black"
        >
            <span v-if="showUpdateForm">View Form</span>
            <span v-else @click="showUpdateForm = false"> Hide Form</span>
        </div>
        <div
            v-if="showUpdateForm"
            class="flex w-full scale-95 flex-col space-y-5 rounded-md border p-3"
        >
            <div class="font-semibold">Update Assessment</div>
            <Error
                v-if="updateForm.errors.assessment_id"
                :error="updateForm.errors.assessment_id"
            />
            <TextInput
                v-model="updateForm.title"
                placeholder="Title"
                :error="updateForm.errors.title"
            />
            <TextInput
                v-model="updateForm.description"
                :error="updateForm.errors.description"
                placeholder="Description"
            />
            <TextInput
                v-model="updateForm.description"
                :error="updateForm.errors.description"
                placeholder="Description"
            />

            <DatePicker v-model="updateForm.due_date" placeholder="Due Date" />
            <div class="flex w-full justify-between">
                <div
                    :class="assessment.status === 'draft' ? 'w-5/12' : 'w-full'"
                >
                    <TextInput
                        v-model="updateForm.maximum_point"
                        placeholder="Max Points"
                        :error="updateForm.errors.maximum_point"
                    />
                </div>
                <div
                    v-if="assessment.status === 'draft'"
                    class="flex w-5/12 justify-center"
                >
                    <div
                        class="flex w-9/12 items-center justify-center space-x-1.5 rounded-md pr-2"
                    >
                        <Toggle v-model="updateForm.status" />

                        <DialogBox
                            v-model:open="showDialog"
                            type="update"
                            title="Update Assessment"
                            @confirm="updateAssessment"
                        >
                            <template #description>
                                Performing this action will result significant
                                change across the entire subject, Are you sure
                                you want to proceed?
                            </template>
                        </DialogBox>
                        <div class="text-sm font-semibold">
                            {{ updateForm.status ? "Publish" : "Draft" }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex w-full justify-end">
                <SecondaryButton
                    class="w-fit bg-zinc-800 font-semibold text-white"
                    title="Update"
                    @click="showDialog = true"
                />
                {{ updateForm.due_date }}
            </div>
        </div>
    </div>
</template>
<script setup>
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { computed, ref, watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import Toggle from "@/Components/Toggle.vue";
import DialogBox from "@/Components/DialogBox.vue";
import Error from "@/Components/Error.vue";
import DatePicker from "@/Components/DatePicker.vue";

import { onClickOutside } from "@vueuse/core";

const props = defineProps({
    assessment: {
        type: Object,
        required: true,
    },
});
const updateForm = useForm({
    title: "",
    description: "",
    maximum_point: "",
    status: "",
    assessment_id: null,
    due_date: null,
});
watch(
    () => props.assessment,
    (assessment) => {
        updateForm.title = assessment.title;
        updateForm.description = assessment.description;
        updateForm.maximum_point = assessment.maximum_point;
        updateForm.assessment_id = assessment.id;
        updateForm.status = assessment.status === "published";
    },
    { immediate: true }
);

const showUpdateForm = ref(true);
const showDialog = ref(false);

const title = computed(
    () =>
        props.assessment.title +
        " " +
        props.assessment.batch_subject.batch.level.name +
        props.assessment.batch_subject.batch.section
);

const parentDiv = ref(null);
onClickOutside(parentDiv, () => {
    updateFormOpacity.value = 25;
});

function updateAssessment() {
    updateForm.status
        ? (updateForm.status = "published")
        : (updateForm.status = "draft");
    updateForm.post("/teacher/assessments/update/", {
        preserveState: true,
    });
}
</script>
<style scoped></style>
