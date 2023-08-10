<template>
    <div
        v-if="questions?.data?.length"
        class="flex h-screen w-11/12 flex-col space-y-4 p-4"
    >
        <div class="flex w-full items-center justify-between py-3">
            <Title :title="$t('teacherQuestions.myQuestionBank')" />
        </div>

        <div class="flex w-full justify-between">
            <div
                v-if="selectedQuestion"
                class="w-5/12 rounded-lg bg-white p-3 shadow-sm"
            >
                <div
                    class="flex flex-col space-y-0.5 text-center text-xl font-semibold"
                >
                    <span>
                        {{
                            selectedQuestion.batch_subject.subject.full_name +
                            " " +
                            selectedQuestion.assessment_type.name
                        }}
                    </span>

                    <span class="text-xs font-light">
                        {{
                            moment(selectedQuestion.updated_at).format(
                                "ddd MMMM DD YYYY"
                            )
                        }}</span
                    >
                </div>

                <div
                    class="mt-4 w-full rounded-lg border border-gray-400 py-3 px-2 text-start text-sm font-normal"
                >
                    <span class="font-semibold">Input:</span>
                    {{ selectedQuestion.input }}
                </div>

                <div
                    v-for="(item, index) in selectedQuestion?.questions"
                    :key="index"
                    class="group my-3 flex cursor-pointer flex-col space-y-4 rounded-lg p-4 font-medium shadow-sm hover:bg-brand-350 hover:text-white"
                    :class="index % 2 === 1 ? 'bg-brand-50/50' : 'bg-white'"
                >
                    <div class="flex w-full flex-col space-y-3 text-sm">
                        <span>
                            {{ item }}
                        </span>

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
                class="flex h-fit w-6/12 flex-col items-center space-y-3 rounded-lg bg-white p-4 shadow-sm"
            >
                <div class="flex w-full justify-between">
                    <div class="grow text-center text-3xl font-semibold">
                        {{ $t("teacherQuestions.recentQuestions") }}
                    </div>

                    <div
                        class="flex h-fit cursor-pointer items-center justify-center space-x-1.5 rounded-2xl bg-brand-350 px-2 py-1.5 text-xs text-white shadow-sm hover:scale-105 hover:bg-brand-400"
                        @click="routeToQuestionGenerator"
                    >
                        <SquaresPlusIcon class="w-3.5" />
                        <span>Generate</span>
                    </div>
                </div>
                <TableElement
                    :filterable="false"
                    :selectable="false"
                    header-style="!bg-brand-450 text-white "
                    class="cursor-pointer !rounded-none !shadow-none"
                    :data="formattedQuestionData"
                    :columns="config"
                    :footer-style="questions.links?.length > 3 ? '' : '!p-0'"
                    row-actionable
                >
                    <template #row-actions="{ row }">
                        <EyeIcon
                            class="w-4 cursor-pointer text-brand-450 hover:scale-125"
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

    <EmptyQuestionView v-else @route="routeToQuestionGenerator" />

    <DialogBox v-model:open="showDeleteDialogBox" @confirm="deleteQuestion" />

    <Modal v-model:view="showUpdateModal">
        <div
            class="flex flex-col items-center space-y-5 rounded-lg bg-white p-4 shadow-sm"
        >
            <div class="text-xl font-semibold">
                {{ $t("teacherQuestions.updateQuestionAndAnswer") }}
            </div>
            <TextArea
                v-model="updateForm.question"
                :placeholder="$t('teacherQuestions.addQuestion')"
                :label="$t('common.question')"
                class="w-full"
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
import EmptyQuestionView from "@/Pages/Teacher/Questions/EmptyView.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Title from "@/Views/Teacher/Views/Title.vue";
import TableElement from "@/Components/TableElement.vue";
import Pagination from "@/Components/Pagination.vue";
import {
    EyeIcon,
    PencilSquareIcon,
    SquaresPlusIcon,
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
            generated_at: moment(question.created_at).format("MMM Do YYYY"),
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
        name: t("teacherQuestions.generatedAt"),
        key: "generated_at",
    },
];

const updateForm = useForm({
    question: "",
    answer: "",
    index: "",
    question_id: null,
});

function updateQuestionForm(item, index) {
    updateForm.question = item;
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
            showUpdateModal.value = false;
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
