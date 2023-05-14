<template>
    <div class="h-fit w-full rounded-lg">
        <!--        Header-->
        <div
            v-if="teacher.assessments.length > 0"
            class="flex w-full justify-between"
        >
            <div class="font-medium lg:text-xl 2xl:text-2xl">
                Recent Assessments
            </div>
            <LinkCell
                class="flex w-fit items-center justify-center"
                href="/teacher/assessments"
                value="SEE ALL"
            />
        </div>

        <!--        Content-->
        <div class="flex w-full flex-col">
            <div
                v-if="teacher.assessments.length > 0"
                class="mt-1 flex w-full flex-col justify-center divide-y-2 lg:mt-2 lg:py-2"
            >
                <Item :assessments="teacher.assessments" />
            </div>
            <div
                v-else
                class="flex h-32 flex-col items-center justify-center space-y-4 lg:h-72"
            >
                <ExclamationTriangleIcon class="h-6 w-6 text-gray-500" />
                <div class="text-xs lg:text-sm">No Assessments Found!</div>
                <LinkCell
                    href="/teacher/assessments"
                    value="Go To Assessments"
                />
            </div>
        </div>

        <Modal v-model:view="showModal">
            <FormElement
                title="Assessment Name"
                class="my-2"
                @submit="handleSubmit"
            >
                <TextInput
                    v-model="form.title"
                    placeholder="Add title for the assessment"
                    label="Title"
                />
                <TextArea
                    v-model="form.description"
                    :rows="7"
                    label="Description"
                    placeholder="Add description for the assessment"
                />
                <TextInput
                    v-model="form.maximum_point"
                    placeholder="Maximum Point"
                    label="Maximum Point"
                    type="number"
                />
                <DatePicker v-model="form.due_date" placeholder="Due Date" />

                <select v-model="form.assessment_type_id">
                    <option
                        v-for="type in selected_batch_assessment_types"
                        :key="type.id"
                        :value="type.id"
                    >
                        {{ type.name }}
                    </option>
                </select>
                <select v-model="form.batch_subject_id">
                    <option
                        v-for="batch_subject in teacher.batch_subjects"
                        :key="batch_subject.id"
                        :value="batch_subject.id"
                    >
                        {{ batch_subject.subject.full_name }}
                        {{ batch_subject.batch.level.name }}
                        {{ batch_subject.batch.section }}
                    </option>
                </select>
            </FormElement>
        </Modal>
    </div>
</template>
<script setup>
import FormElement from "@/Components/FormElement.vue";
import { computed, onMounted, ref } from "vue";
import Modal from "@/Components/Modal.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import DatePicker from "@/Components/DatePicker.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";

import { ExclamationTriangleIcon } from "@heroicons/vue/24/outline";
import Item from "@/Views/Assessments/Item.vue";
import LinkCell from "@/Components/LinkCell.vue";

const showModal = ref(false);
const teacher = usePage().props.teacher;
const all_assessment_types = usePage().props.assessment_type;

const selected_batch_assessment_types = computed(() => {
    if (form.batch_subject_id) {
        const batch_subject = teacher.batch_subjects.find(
            (batch_subject) => batch_subject.id === form.batch_subject_id
        );
        if (batch_subject) {
            const level_category_id =
                batch_subject.batch.level.level_category.id;
            return all_assessment_types.filter(
                (type) => type.level_category_id === level_category_id
            );
        }
    }
    return [];
});

const form = useForm({
    assessment_type_id: "",
    batch_subject_id:
        teacher.batch_subjects.length > 0 ? teacher.batch_subjects[0].id : "",
    due_date: new Date(),
    title: "",
    description: "",
    maximum_point: "",
});

onMounted(() => {
    form.assessment_type_id =
        selected_batch_assessment_types.value.length > 0
            ? selected_batch_assessment_types.value[0].id
            : "";
});

function handleSubmit() {
    form.post("/teacher/assessments/create", {
        onSuccess: () => {
            showModal.value = false;
        },
    });
}
</script>
<style scoped></style>
