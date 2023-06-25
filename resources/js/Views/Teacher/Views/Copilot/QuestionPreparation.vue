<template>
    <div
        class="flex min-h-full w-full flex-col items-center space-y-6 rounded-lg p-4"
    >
        <div
            class="flex w-9/12 flex-col items-center rounded-lg p-4 text-center"
        >
            <div class="text-4xl font-medium">
                Welcome to our AI-Powered Assessment Preparation Platform
            </div>
            <div class="w-10/12 py-1 text-sm font-light">
                Embrace our AI-powered platform that creates custom assessments
                from your lesson plans, facilitating a streamlined teaching
                experience. Immerse yourself in a process where you teach more
                and prepare less, helping reshape the future of education.
            </div>
        </div>

        <div class="flex w-full justify-between space-x-5">
            <div
                class="flex w-5/12 flex-col items-center space-y-6 rounded-lg bg-white px-5 pt-3 pb-5 shadow-sm"
            >
                <div class="text-xl font-light">
                    Question Generation Customization
                    {{ form.errors }}
                </div>
                <SelectInput
                    v-model="form.assessment_type_id"
                    class="w-full"
                    label="Select Question Type"
                    :options="filteredAssessmentType"
                />
                <TextInput
                    v-if="form.assessment_type_id"
                    v-model="form.number_of_questions"
                    class="w-full"
                    label="Number Of Questions?"
                    placeholder="How Many Questions?"
                    type="number"
                />
                <div
                    v-if="form.number_of_questions"
                    class="flex w-full justify-evenly space-x-4"
                >
                    <QuestionSource
                        title="Manual Input"
                        description="Opt for manual input if you prefer to generate questions based on your unique inputs and parameters. This option allows for greater control and specificity."
                        source="input"
                        :selected-source="form.question_source"
                        @click="form.question_source = $event"
                    />

                    <QuestionSource
                        title="Lesson Plans"
                        description="Select 'Lesson Plans' to automatically generate questions from your existing plans. Upon selection, we'll load your plans, and you can choose one for us to craft tailored questions."
                        source="lesson-plans"
                        :selected-source="form.question_source"
                        @click="form.question_source = 'lesson-plans'"
                    />
                </div>
                <SecondaryButton
                    title="Submit"
                    class="w-10/12 !rounded-2xl bg-zinc-800 py-2 font-medium uppercase text-white"
                    @click="submit"
                />
            </div>
            <div class="flex w-6/12 items-center">
                <LessonPlans @select="updateLessonPlanIds" />
            </div>
        </div>
    </div>
    <Loading v-if="isLoading" is-full-screen type="bounce" />
</template>
<script setup>
import SelectInput from "@/Components/SelectInput.vue";
import { computed, onMounted, ref } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import Loading from "@/Components/Loading.vue";
import QuestionSource from "@/Views/Teacher/Views/Copilot/QuestionSource.vue";
import LessonPlans from "@/Views/Teacher/Views/Copilot/LessonPlans.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const isLoading = ref(false);
const assessmentTypes = computed(() => usePage().props.assessment_types);

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
    lessonPlanIds: [],
});

const loadLessonPlans = () => {
    isLoading.value = true;
    router.visit("/teacher/copilot", {
        only: ["lesson_plans_data"],
        preserveState: true,
        onFinish: () => {
            isLoading.value = false;
        },
    });
};

const updateLessonPlanIds = (value) => {
    form.lessonPlanIds = value;
};

const submit = () => {
    isLoading.value = true;
    form.post("/teacher/copilot/generate-questions", {
        preserveState: true,
        onFinish: () => {
            isLoading.value = false;
        },
    });
};
</script>

<style scoped></style>
