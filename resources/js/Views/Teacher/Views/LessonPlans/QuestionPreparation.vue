<template>
    <div
        class="flex h-fit w-full flex-col items-center space-y-6 rounded-lg border border-gray-400 bg-brand-50/50 px-5 pb-5 pt-3 shadow-sm"
    >
        <div class="text-xl font-light">
            {{
                $t("lessonPlanQuestionPreparation.lessonPlanQuestionGenerator")
            }}
        </div>
        <Error
            v-if="questionForm.errors?.length"
            :error="$t('lessonPlanQuestionPreparation.somethingWentWrong')"
        />
        <SelectInput
            v-model="questionForm.assessment_type_id"
            class="w-full"
            :label="$t('lessonPlanQuestionPreparation.selectQuestionType')"
            :options="filteredAssessmentType"
            :error="questionForm.errors.assessment_type_id"
        />
        <TextInput
            v-if="questionForm.assessment_type_id"
            v-model="questionForm.number_of_questions"
            class="w-full"
            :label="$t('lessonPlanQuestionPreparation.numberOfQuestions')"
            :placeholder="$t('lessonPlanQuestionPreparation.howManyQuestions')"
            type="number"
            :error="questionForm.errors.number_of_questions"
        />
        <div class="w-full flex-col space-y-1">
            <div>
                <label for="large-range" class="block text-sm font-medium">{{
                    $t("lessonPlanQuestionPreparation.setDifficultyLevel")
                }}</label>
                <p class="mb-2 py-1 text-xs font-light text-gray-600">
                    {{ $t("lessonPlanQuestionPreparation.useThisSlider") }}
                </p>
            </div>

            <input
                id="large-range"
                v-model="questionForm.difficulty_level"
                min="1"
                max="10"
                type="range"
                class="range-lg h-3 w-full cursor-pointer appearance-none rounded-lg bg-white"
            />
        </div>

        <SecondaryButton
            :title="$t('common.submit')"
            class="w-4/12 !rounded-2xl bg-purple-600 py-2 font-medium uppercase text-white"
            @click="submit"
        />

        <GeneratedQuestions
            v-if="questions?.length"
            ref="generatedQuestionsRef"
            class="scrollbar-hide !w-full !overflow-y-auto"
        />
    </div>
</template>
<script setup>
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import GeneratedQuestions from "@/Views/Teacher/Views/Copilot/GeneratedQuestions.vue";
import Error from "@/Components/Error.vue";
import { useUIStore } from "@/Store/ui";

const props = defineProps({
    lessonPlanId: {
        type: Number,
        required: true,
    },
    batchSubjectId: {
        type: Number,
        required: true,
    },
});

const emit = defineEmits(["limit-reached"]);

const showLoading = ref(false);
const generatedQuestionsRef = ref(null);
const questions = computed(() => usePage().props.questions);
const assessmentTypes = computed(() => usePage().props.assessment_types);

const filteredAssessmentType = computed(() => {
    return assessmentTypes.value.map((assessmentType) => {
        return {
            label: assessmentType.name,
            value: assessmentType.id,
        };
    });
});

const questionForm = useForm({
    assessment_type_id: null,
    number_of_questions: null,
    difficulty_level: 5,
    lesson_plan_ids: [props.lessonPlanId],
    question_source: "lesson-plans",
    batch_subject_id: props.batchSubjectId,
});

const uiStore = useUIStore();
const submit = () => {
    uiStore.setQuestionGenerationLoading(true);
    questionForm.post("/teacher/questions/create", {
        preserveState: true,
        onError: (error) => {
            uiStore.setQuestionGenerationLoading(false);
            emit("limit-reached");
        },
    });
};

Echo.private("question-generator").listen(".question-generator", (e) => {
    uiStore.setQuestionGenerationLoading(false);

    if (e.type === "success") uiStore.setQuestionGenerationStatus("success");

    if (e.type === "error") uiStore.setQuestionGenerationStatus("error");
});
</script>
<style scoped></style>
