<template>
    <div>
        <FormElement
            title="Create Assessment"
            class="my-2"
            @submit="handleSubmit"
        >
            <TextInput
                v-model="form.title"
                :error="form.errors.title"
                placeholder="Add title for the assessment"
                label="Title"
            />
            <TextArea
                v-model="form.description"
                :error="form.errors.description"
                :rows="7"
                label="Description"
                placeholder="Add description for the assessment"
            />
            <TextInput
                v-model="form.maximum_point"
                :error="form.errors.maximum_point"
                placeholder="Maximum Point"
                label="Maximum Point"
                type="number"
            />
            <DatePicker
                v-model="form.due_date"
                placeholder="Due Date"
                :error="form.errors.due_date"
            />
            <SelectInput
                v-model="form.batch_subject_id"
                :options="batchSubjectOptions"
                :error="form.errors.batch_subject_id"
                placeholder="Select Subject"
            />
            <SelectInput
                v-model="form.assessment_type_id"
                :options="selectedBatchAssessmentTypes"
                :error="form.errors.assessment_type_id"
                placeholder="Select type"
            />

            <SelectInput
                v-model="form.status"
                :options="statusOptions"
                placeholder="Select Status"
                :error="form.errors.status"
            />
        </FormElement>
    </div>
</template>
<script setup>
import TextInput from "@/Components/TextInput.vue";
import DatePicker from "@/Components/DatePicker.vue";
import TextArea from "@/Components/TextArea.vue";
import FormElement from "@/Components/FormElement.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { computed, onMounted } from "vue";
import SelectInput from "@/Components/SelectInput.vue";

onMounted(() => {
    form.assessment_type_id =
        selectedBatchAssessmentTypes.value.length > 0
            ? selectedBatchAssessmentTypes.value[0].value
            : "";
});

const emit = defineEmits(["success"]);

const teacher = usePage().props.teacher;
const assessmentTypes = usePage().props.assessment_type;

const selectedBatchAssessmentTypes = computed(() => {
    if (form.batch_subject_id) {
        const batch_subject = teacher.batch_subjects.find(
            (batch_subject) => batch_subject.id === form.batch_subject_id
        );
        if (batch_subject) {
            const level_category_id =
                batch_subject.batch.level.level_category.id;
            return assessmentTypes
                .filter((type) => type.level_category_id === level_category_id)
                .map((type) => {
                    return {
                        label: type.name,
                        value: type.id,
                    };
                });
        }
    }
    return [];
});
const batchSubjectOptions = computed(() => {
    return teacher.batch_subjects.map((batchSubject) => {
        return {
            value: batchSubject.id,
            label:
                batchSubject.subject.full_name +
                " " +
                batchSubject.batch.level.name +
                batchSubject.batch.section,
        };
    });
});
const statusOptions = [
    {
        label: "Draft",
        value: "draft",
    },
    {
        label: "Publish",
        value: "published",
    },
];

const form = useForm({
    assessment_type_id: "",
    batch_subject_id:
        batchSubjectOptions.value.length > 0
            ? batchSubjectOptions.value[0].value
            : "",
    due_date: new Date(),
    title: "",
    description: "",
    maximum_point: "",
    status: "draft",
});

function handleSubmit() {
    form.post("/teacher/assessments/create", {
        onSuccess: () => {
            emit("success");
        },
    });
}
</script>

<style scoped></style>
