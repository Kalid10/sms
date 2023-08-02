<template>
    <div
        v-if="questions?.data?.length"
        class="flex h-screen w-11/12 flex-col space-y-4 p-4"
    >
        <div class="flex w-full items-center justify-between py-3">
            <Title :title="$t('teacherQuestions.myQuestionBank')" />
            <p
                class="w-8/12 rounded-lg bg-gradient-to-tr from-violet-500 to-purple-500 p-4 text-center text-xs text-white"
            >
                {{ $t("teacherQuestions.questionGeneratorDescription") }}
            </p>
        </div>

        <div class="flex w-full justify-between">
            <div
                v-if="selectedQuestion"
                class="w-5/12 rounded-lg bg-white p-3 shadow-sm"
            >
                <div class="flex justify-between px-2">
                    <div
                        class="flex flex-col space-y-0.5 text-center text-xl font-semibold"
                    >
                        <span>
                            {{ $t("teacherQuestions.questionsFor") }}
                            {{
                                selectedQuestion.batch_subject.subject
                                    .full_name +
                                " " +
                                selectedQuestion.assessment_type.name
                            }}
                        </span>
                        <span class="text-start text-xs font-light">
                            @
                            {{
                                moment(selectedQuestion.updated_at).format(
                                    "ddd MMMM DD YYYY"
                                )
                            }}</span
                        >
                    </div>
                    <PrinterIcon
                        class="w-5 cursor-pointer text-brand-text-350 hover:scale-125 hover:text-black"
                    />
                </div>
                <div
                    v-for="(item, index) in selectedQuestion?.questions"
                    :key="index"
                    class="group my-3 flex cursor-pointer flex-col space-y-4 rounded-lg p-4 font-medium shadow-sm hover:bg-brand-350 hover:text-white"
                    :class="index % 2 === 1 ? 'bg-brand-50' : 'bg-brand-50/50'"
                >
                    <div class="flex w-full flex-col space-y-3 text-sm">
                        <span>
                            {{ item.question }}
                        </span>

                        <span
                            class="rounded-lg border border-black p-4 text-xs shadow-sm group-hover:border-gray-50"
                        >
                            {{ $t("teacherQuestions.answer") }}
                            {{ item.answer }}</span
                        >
                        <span
                            class="flex w-full justify-end space-x-6 pt-2 text-xs"
                        >
                            <span class="flex cursor-pointer space-x-3">
                                <TrashIcon
                                    class="w-4 text-red-600 hover:scale-105 group-hover:text-red-500"
                                    @click="
                                        selectedIndex = index;
                                        selectedRow = null;
                                        showDeleteDialogBox = true;
                                    "
                                />
                                <PencilSquareIcon
                                    class="w-4 text-black hover:scale-105 group-hover:text-white"
                                    @click="updateQuestionForm(item, index)"
                                />
                            </span>
                        </span>
                    </div>
                </div>
            </div>
            <div
                class="flex h-fit w-6/12 flex-col items-center space-y-3 rounded-lg bg-white px-4 py-6 shadow-sm"
            >
                <div class="text-3xl font-semibold">
                    {{ $t("teacherQuestions.recentQuestions") }}
                </div>
                <TableElement
                    :filterable="false"
                    :selectable="false"
                    header-style="bg-brand-450 text-white "
                    class="cursor-pointer !rounded-none !shadow-none"
                    :data="formattedQuestionData"
                    :columns="config"
                    :footer-style="questions.links?.length > 3 ? '' : '!p-0'"
                    row-actionable
                >
                    <template #row-actions="{ row }">
                        <EyeIcon
                            class="w-4 cursor-pointer text-brand-text-450 hover:scale-125"
                            @click="setSelectedQuestion(row.id)"
                        />
                        <TrashIcon
                            class="w-4 cursor-pointer text-red-600 hover:scale-125"
                            @click="
                                selectedIndex = null;
                                selectedRow = row.id;
                                showDeleteDialogBox = true;
                            "
                        />
                    </template>
                    <template #footer>
                        <Pagination
                            :links="questions.links"
                            preserve-state
                            position="center"
                        />
                    </template>
                </TableElement>
            </div>
        </div>
    </div>
    <div
        v-else
        class="flex h-screen w-full flex-col items-center justify-center space-y-3"
    >
        <EmptyView :title="$t('teacherQuestions.noQuestionsGenerated')" />
        <SecondaryButton
            :title="$t('teacherQuestions.generateQuestions')"
            class="w-2/12 !rounded-2xl bg-purple-600 py-2 font-medium uppercase text-white"
            @click="routeToQuestionGenerator"
        />
    </div>

    <DialogBox v-model:open="showDeleteDialogBox" @confirm="deleteQuestion" />

    <Modal v-model:view="showUpdateModal">
        <div
            class="flex flex-col items-center space-y-5 rounded-lg bg-white p-4 shadow-sm"
        >
            <div class="text-xl font-semibold">
                {{ $t("teacherQuestions.updateQuestion") }}
            </div>
            <TextArea
                v-model="updateForm.question"
                :placeholder="$t('teacherQuestions.addQuestion')"
                :label="$t('common.question')"
                class="w-full"
            />
            <TextArea
                v-model="updateForm.answer"
                class="w-full"
                :placeholder="$t('teacherQuestions.addAnswer')"
                :label="$t('teacherQuestions.answer')"
            />

            <TextArea
                v-model="updateForm.answer"
                class="w-full"
                :placeholder="$t('teacherQuestions.addAnswer')"
                :label="$t('teacherQuestions.answer')"
            />
            <SecondaryButton
                :title="$t('teacherQuestions.update')"
                class="w-3/12 !rounded-2xl bg-brand-450 text-2xl text-white"
                @click="updateQuestion()"
            />
        </div>
    </Modal>
</template>
<script setup>
import { computed, ref } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import EmptyView from "@/Views/EmptyView.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Title from "@/Views/Teacher/Views/Title.vue";
import TableElement from "@/Components/TableElement.vue";
import Pagination from "@/Components/Pagination.vue";
import {
    EyeIcon,
    PencilSquareIcon,
    PrinterIcon,
    TrashIcon,
} from "@heroicons/vue/20/solid";
import Modal from "@/Components/Modal.vue";
import TextArea from "@/Components/TextArea.vue";
import DialogBox from "@/Components/DialogBox.vue";
import moment from "moment";
import { useI18n } from "vue-i18n";

const { t } = useI18n();
const showUpdateModal = ref(false);
const showDeleteDialogBox = ref(false);
const selectedIndex = ref(null);
const selectedRow = ref(null);

const questions = computed(() => usePage().props.questions);
const selectedQuestion = ref(
    questions.value.data[questions.value.data.length - 1]
);

const formattedQuestionData = computed(() => {
    return questions.value.data.map((question) => {
        return {
            id: question.id,
            name:
                question.user.name === usePage().props.auth.user.name
                    ? "Self"
                    : question.user.name,
            type: question.assessment_type.name,
            subject: question.batch_subject.subject.full_name,
            no_of_questions: question.no_of_questions,
            difficulty_level: question.difficulty_level,
        };
    });
});
const config = [
    {
        name: t("teacherQuestions.assessmentType"),
        key: "type",
        class: "!py-4 !text-xs",
    },
    {
        name: t("common.subject"),
        key: "subject",
    },
    {
        name: t("common.by"),
        key: "name",
    },
    {
        name: t("teacherQuestions.noOfQuestions"),
        key: "no_of_questions",
    },
    {
        name: t("teacherQuestions.difficultyLevel"),
        key: "difficulty_level",
    },
];

const updateForm = useForm({
    question: "",
    answer: "",
    index: "",
    question_id: null,
});

function updateQuestionForm(item, index) {
    updateForm.question = item.question;
    updateForm.answer = item.answer;
    updateForm.index = index;
    updateForm.question_id = selectedQuestion.value.id;
    showUpdateModal.value = true;
}

function deleteQuestion() {
    router.post(
        "/teacher/questions/delete",
        {
            index: selectedIndex.value,
            question_id: selectedRow.value ?? selectedQuestion.value.id,
        },
        {
            preserveState: true,
            onSuccess: () => {
                router.visit("/teacher/questions", {
                    only: ["questions"],
                });
            },
        }
    );
}

const updateQuestion = () => {
    updateForm.post("/teacher/questions", {
        preserveState: true,
        onSuccess: () => {
            showUpdateModal.value = true;
        },
    });
};

const setSelectedQuestion = (id) => {
    selectedQuestion.value = questions.value.data.find(
        (item) => item.id === id
    );
};

const questionsTab = t("common.questions");
const routeToQuestionGenerator = () => {
    router.get(
        "/teacher/copilot",
        {
            active_tab: questionsTab,
        },
        {
            preserveState: true,
        }
    );
};
</script>
<style scoped></style>
