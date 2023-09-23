<template>
    <div
        class="flex min-h-full w-full flex-col items-center space-y-8 rounded-lg"
    >
        <Modal v-model:view="showInfoModal">
            <div
                class="flex w-full flex-col items-center space-y-4 rounded-lg bg-white p-6 text-center"
            >
                <div class="text-2xl font-semibold">
                    {{ $t("questionPreparation.welcome") }}
                </div>
                <div class="fo w-10/12 py-1 text-sm font-medium text-gray-600">
                    {{ $t("questionPreparation.description") }}
                </div>
            </div>
        </Modal>

        <InformationCircleIcon
            class="absolute top-6 right-6 w-5 cursor-pointer text-black hover:scale-125 hover:text-brand-450"
            @click="showInfoModal = true"
        />

        <div
            class="flex w-full flex-col justify-between lg:flex-row lg:space-x-5"
        >
            <div
                class="flex w-full flex-col items-center space-y-6 rounded-lg bg-white px-5 pt-3 pb-5 shadow-sm lg:w-5/12"
            >
                <div class="text-xl font-light">
                    {{ $t("questionPreparation.questionGeneration") }}
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
                            >{{
                                $t("questionPreparation.setDifficultyLevel")
                            }}</label
                        >
                        <p
                            class="mb-2 py-1 text-xs font-light text-brand-text-100"
                        >
                            {{ $t("questionPreparation.hintForDifficulty") }}
                        </p>
                    </div>
                    <input
                        id="large-range"
                        v-model="form.difficulty_level"
                        min="1"
                        max="10"
                        type="range"
                        class="range-lg h-3 w-full cursor-pointer appearance-none rounded-lg bg-brand-100"
                    />
                </div>
                <div
                    v-if="form.number_of_questions"
                    class="flex w-full flex-col justify-evenly space-y-2 lg:flex-row lg:space-y-0 lg:space-x-4"
                >
                    <QuestionSource
                        :title="$t('questionPreparation.manualInput')"
                        :description="
                            $t('questionPreparation.manualInputDescription')
                        "
                        source="custom"
                        :selected-source="form.question_source"
                        @click="
                            form.question_source = $event;
                            form.lessonPlanIds = null;
                        "
                    />

                    <QuestionSource
                        :title="$t('questionPreparation.lessonPlans')"
                        :description="
                            $t('questionPreparation.lessonPlanDescription')
                        "
                        source="lesson-plans"
                        :selected-source="form.question_source"
                        @click="form.question_source = 'lesson-plans'"
                    />
                </div>

                <div
                    v-if="
                        subjectOptions?.length > 0 &&
                        form.question_source === 'custom'
                    "
                    class="w-full"
                >
                    <SelectInput
                        v-model="selectedSubject"
                        :options="subjectOptions"
                    />
                </div>

                <TextArea
                    v-if="selectedSubject && form.question_source === 'custom'"
                    v-model="form.manual_question"
                    class="w-full"
                    :label="$t('common.input')"
                    :placeholder="$t('questionPreparation.enterInput')"
                    rows="10"
                    :error="form.errors.manual_question"
                />

                <SecondaryButton
                    :is-disabled="isSubmitDisabled"
                    :title="$t('common.submit')"
                    class="w-10/12 !rounded-2xl bg-purple-600 py-2 font-medium uppercase text-white"
                    @click="submit"
                />
            </div>
            <div class="flex h-fit w-full lg:w-6/12">
                <LessonPlans @select="updateLessonPlanIds" />
            </div>
        </div>
    </div>
</template>
<script setup>
import SelectInput from "@/Components/SelectInput.vue";
import { computed, inject, onMounted, ref } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import QuestionSource from "@/Views/Teacher/Views/Copilot/QuestionSource.vue";
import LessonPlans from "@/Views/Teacher/Views/Copilot/LessonPlans.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextArea from "@/Components/TextArea.vue";
import { useUIStore } from "@/Store/ui";
import Modal from "@/Components/Modal.vue";
import { InformationCircleIcon } from "@heroicons/vue/24/outline";

const showNotification = inject("showNotification");
const assessmentTypes = computed(() => usePage().props.assessment_types);
const questions = computed(() => usePage().props.questions);
const batchSubjects = computed(() => usePage().props.batch_subjects);
const showInfoModal = ref(false);

const emit = defineEmits(["limit-reached"]);
onMounted(() => {
    loadLessonPlans();
});

const selectedSubject = ref();
const subjectOptions = computed(() => {
    return batchSubjects?.value?.map((item) => {
        return {
            label:
                item.batch.level.name +
                item.batch.section +
                " - " +
                item.subject.full_name,
            value: item.id,
        };
    });
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
    assessment_type_id: assessmentTypes?.value[0]?.id,
    number_of_questions: 3,
    question_source: null,
    lesson_plan_ids: [],
    manual_question: "",
    batch_subject_id: selectedSubject,
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
    uiStore.setLoading(true, "Generating questions...");

    form.post("/teacher/questions/create", {
        preserveState: true,
        onError: (error) => {
            uiStore.setLoading(false);
            emit("limit-reached");
        },
    });
};

Echo.private("question-generator").listen(".question-generator", (e) => {
    uiStore.setLoading(false);

    if (e.type === "success") {
        uiStore.setResponse("success", "Questions generated successfully!");
    }

    if (e.type === "error") {
        showNotification({
            type: "error",
            message: e.message,
            position: "top-center",
        });
        uiStore.setResponse("error", e.message);
    }

    setTimeout(() => {
        uiStore.setResponse(null, null);
    }, 5000);
});

const isSubmitDisabled = computed(() => {
    if (!form.question_source) return true;

    if (
        form.question_source === "custom" &&
        (!form.manual_question || form.manual_question.trim() === "")
    ) {
        return true;
    }

    return (
        form.question_source === "lesson-plans" &&
        (!form.lesson_plan_ids || form.lesson_plan_ids.length < 1)
    );
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
