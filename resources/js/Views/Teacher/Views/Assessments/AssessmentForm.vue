<template>
    <div>
        <FormElement
            title="Assessment Form"
            class="my-2"
            @submit="handleSubmit"
        >
            <!-- Form Fields -->
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
                v-if="form.batch_subject_id"
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
            <div
                v-if="
                    form.status === 'published' || form.status === 'scheduled'
                "
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
        </FormElement>

        <DialogBox
            v-model:open="showDialog"
            type="update"
            title="Submit Assessment"
            @confirm="handleSubmit"
        >
            <template #description>
                Performing this action will result significant change across the
                entire subject, Are you sure you want to proceed?
            </template>
        </DialogBox>
    </div>
</template>

<script setup>
import TextInput from "@/Components/TextInput.vue";
import DatePicker from "@/Components/DatePicker.vue";
import TextArea from "@/Components/TextArea.vue";
import FormElement from "@/Components/FormElement.vue";
import SelectInput from "@/Components/SelectInput.vue";
import DialogBox from "@/Components/DialogBox.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";
import { InformationCircleIcon } from "@heroicons/vue/24/outline";

const props = defineProps({
    assessment: {
        type: Object,
        default: null,
    },
});

let form = useForm({
    assessment_type_id: "",
    batch_subject_id: "",
    due_date: new Date(),
    title: "",
    description: "",
    maximum_point: "",
    status: "draft",
});

watch(
    () => props.assessment,
    (assessment) => {
        if (assessment) {
            form = useForm({
                ...assessment,
                due_date: new Date(assessment.due_date),
                status: assessment.status,
                assessment_id: assessment.id,
            });
        }
    },
    { immediate: true }
);

const showDialog = ref(false);

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
        label: "Schedule",
        value: "scheduled",
    },
    {
        label: "Publish",
        value: "published",
    },
];

function handleSubmit() {
    const url = form.assessment_id
        ? "/teacher/assessments/update/"
        : "/teacher/assessments/create";
    form.post(url, {
        preserveState: true,
        onSuccess: () => {
            emit("success");
        },
    });
}
</script>
