<template>
    <div
        class="flex min-h-full w-full flex-col items-center space-y-8 rounded-lg p-4 py-8"
    >
        <div
            class="absolute top-6 right-0 flex w-8/12 flex-col items-center rounded-lg p-4 text-center"
        >
            <div class="text-4xl font-medium">
               {{ $t('questionPreparation.welcome') }}
            </div>
            <div class="w-10/12 py-1 text-sm font-light">
                {{ $t('questionPreparation.description') }}
            </div>
        </div>

        <div class="flex w-full justify-between space-x-5">
            <div
                class="flex w-5/12 flex-col items-center space-y-6 rounded-lg bg-white px-5 pt-3 pb-5 shadow-sm"
            >
                <div class="text-xl font-light">
                    {{ $t('questionPreparation.questionGeneration') }}
                </div>
                <SelectInput
                    v-model="form.assessment_type_id"
                    class="w-full"
                    :label="$t('questionPreparation.selectQuestionType')"
                    :options="filteredAssessmentType"
                    :error="form.errors.assessment_type_id"
                />
                <TextInput
                    v-if="form.assessment_type_id"
                    v-model="form.number_of_questions"
                    class="w-full"
                    :label="$t('questionPreparation.numberOfQuestions')"
                    :placeholder="$t('questionPreparation.howManyQuestions')"
                    type="number"
                    :error="form.errors.number_of_questions"
                />
                <div class="w-full flex-col space-y-1">
                    <div>
                        <label
                            for="large-range"
                            class="block text-sm font-medium"
                            >{{ $t('questionPreparation.setDifficultyLevel') }}</label
                        >
                        <p class="mb-2 py-1 text-xs font-light text-gray-600">
                            {{ $t('questionPreparation.hintForDifficulty') }}
                        </p>
                    </div>

                    <input
                        id="large-range"
                        v-model="form.difficulty_level"
                        min="1"
                        max="10"
                        type="range"
                        class="range-lg h-3 w-full cursor-pointer appearance-none rounded-lg bg-gray-100"
                    />
                </div>
                <div
                    v-if="form.number_of_questions"
                    class="flex w-full justify-evenly space-x-4"
                >
                    <QuestionSource
                        :title="$t('questionPreparation.manualInput')"
                        :description="$t('questionPreparation.manualInputDescription')"
                        source="custom"
                        :selected-source="form.question_source"
                        @click="
                            form.question_source = $event;
                            form.lessonPlanIds = null;
                        "
                    />

                    <QuestionSource
                        :title="$t('questionPreparation.lessonPlans')"
                        :description="$t('questionPreparation.lessonPlanDescription')"
                        source="lesson-plans"
                        :selected-source="form.question_source"
                        @click="form.question_source = 'lesson-plans'"
                    />
                </div>

                <TextArea
                    v-if="form.question_source === 'custom'"
                    v-model="form.manual_question"
                    class="w-full"
                    :label="$t('common.question')"
                    :placeholder="$t('questionPreparation.enterQuestion')"
                    rows="10"
                    :error="form.errors.manual_question"
                />
                <SecondaryButton
                    :title="$t('common.submit')"
                    class="w-10/12 !rounded-2xl bg-purple-600 py-2 font-medium uppercase text-white"
                    @click="submit"
                />
            </div>
            <div class="flex w-6/12 items-center">
                <LessonPlans @select="updateLessonPlanIds" />
            </div>
        </div>
    </div>
</template>
<script setup>
import SelectInput from "@/Components/SelectInput.vue";
import { computed, onMounted } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import QuestionSource from "@/Views/Teacher/Views/Copilot/QuestionSource.vue";
import LessonPlans from "@/Views/Teacher/Views/Copilot/LessonPlans.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextArea from "@/Components/TextArea.vue";
import { useUIStore } from "@/Store/ui";

const assessmentTypes = computed(() => usePage().props.assessment_types);

const questions = computed(() => usePage().props.questions);

onMounted(() => {
    loadLessonPlans();
});

const filteredAssessmentType = computed(() => {
    return assessmentTypes.value.map((assessmentType) => {
        return {
            label: assessmentType.name,
            value: assessmentType.id,
        };
    });
});

const form = useForm({
    assessment_type_id: assessmentTypes.value[0].id,
    number_of_questions: 3,
    question_source: null,
    lesson_plan_ids: [],
    manual_question: "",
    batch_subject_id: 1263,
    difficulty_level: 5,
});

const loadLessonPlans = () => {
    router.visit("/teacher/copilot", {
        only: ["lesson_plans_data"],
        preserveState: true,
    });
};

const updateLessonPlanIds = (lessonPlanIds, batchSubject) => {
    if (form.question_source !== "lesson-plans")
        form.question_source = "lesson-plans";

    form.batch_subject_id = batchSubject.id;
    form.lesson_plan_ids = lessonPlanIds;
};

const uiStore = useUIStore();
const submit = () => {
    uiStore.setQuestionGenerationLoading(true);
    form.post("/teacher/questions/create", {
        preserveState: true,
    });
};

Echo.private("question-generator").listen(".question-generator", (e) => {
    uiStore.setQuestionGenerationLoading(false);

    if (e.type === "success") uiStore.setQuestionGenerationStatus("success");

    if (e.type === "error") uiStore.setQuestionGenerationStatus("error");
});
</script>

<style>
/* For Chrome and Safari */
input[type="range"]::-webkit-slider-thumb {
    -webkit-appearance: none;
    width: 20px;
    height: 20px;
    background: mediumpurple; /* Change this to the color you want */
    cursor: pointer;
    border-radius: 50%;
}

/* For Firefox */
input[type="range"]::-moz-range-thumb {
    width: 20px;
    height: 20px;
    background: mediumpurple; /* Change this to the color you want */
    cursor: pointer;
    border-radius: 50%;
}

/* For IE */
input[type="range"]::-ms-thumb {
    width: 20px;
    height: 20px;
    background: mediumpurple; /* Change this to the color you want */
    cursor: pointer;
    border-radius: 50%;
}
</style>
