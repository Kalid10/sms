<template>
    <div>
        <FormElement
            :title="$t('assessmentForm.formElementTitle')"
            class="my-2"
            @submit="handleSubmit"
        >
            <!-- Form Fields -->
            <TextInput
                v-model="form.title"
                :error="form.errors.title"
                :placeholder="$t('assessmentForm.titlePlaceholder')"
                :label="$t('assessmentForm.titleLabel')"
            />
            <TextArea
                v-model="form.description"
                :error="form.errors.description"
                :rows="7"
                :placeholder="$t('assessmentForm.descriptionPlaceholder')"
                :label="$t('assessmentForm.descriptionLabel')"
            />
            <TextInput
                v-model="form.maximum_point"
                :error="form.errors.maximum_point"
                :placeholder="$t('assessmentForm.maximumPointPlaceholder')"
                :label="$t('assessmentForm.maximumPointLabel')"
                type="number"
            />
            <DatePicker
                v-model="form.due_date"
                :placeholder="$t('assessmentForm.dueDatePlaceholder')"
                :error="form.errors.due_date"
            />
            <SelectInput
                v-model="form.batch_subject_id"
                :options="batchSubjectOptions"
                :error="form.errors.batch_subject_id"
                :placeholder="$t('assessmentForm.batchSubjectIdPlaceholder')"
            />
            <SelectInput
                v-if="form.batch_subject_id"
                v-model="form.assessment_type_id"
                :options="selectedBatchAssessmentTypes"
                :error="form.errors.assessment_type_id"
                :placeholder="$t('assessmentForm.assessmentTypeId')"
            />
            <SelectInput
                v-model="form.status"
                :options="statusOptions"
                :placeholder="$t('assessmentForm.statusPlaceholder')"
                :error="form.errors.status"
            />
            <div
                v-if="
                    form.status === 'published' || form.status === 'scheduled'
                "
                class="flex w-full bg-brand-50 px-4 py-2 text-center text-[0.65rem] font-light"
            >
                <InformationCircleIcon class="mr-2 w-7 text-brand-text-450" />
                <!--                "የማረጋገጫውን ሁኔታ ወደ <span class="font-semibold upper/case">'{published}'</span> ወይም <span class="font-semibold uppercase">'{scheduled}'</span> መቀየር ወዲያውኑ ለአሳዳጊዎች እና ለመምህራን ማሳወቂያዎችን ያስነሳል። ለተጨማሪ ግንዛቤ የማረጋገጫው ዝርዝር መረጃ ሊደረስበት ይችላል።"-->

                <span v-html="$t('assessmentForm.message', [])"></span>

                <div>
                    Setting an assessment as
                    <span class="font-semibold uppercase">" PUBLISHED "</span>
                    or
                    <span class="font-semibold uppercase">" SCHEDULED "</span>
                    will trigger immediate notifications to guardians and
                    principals. Detailed information about the assessment can be
                    accessed for further insight.
                </div>
            </div>
        </FormElement>

        <DialogBox
            v-model:open="showDialog"
            type="update"
            :title="$t('assessmentForm.title')"
            @confirm="handleSubmit"
        >
            <template #description>
                {{ $t("assessmentForm.alertMessage") }}
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
import { useI18n } from "vue-i18n";

const { t } = useI18n();
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
        label: t("assessmentForm.draft"),
        value: "draft",
    },
    {
        label: t("assessmentForm.schedule"),
        value: "scheduled",
    },
    {
        label: t("assessmentForm.publish"),
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
