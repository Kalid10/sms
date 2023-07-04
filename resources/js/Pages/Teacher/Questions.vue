<template>
    <div
        v-if="questions?.data?.length"
        class="flex h-screen w-11/12 flex-col space-y-4 bg-gray-50/60 p-4"
    >
        <div class="flex w-full items-center justify-between py-3">
            <Title title="My Question Bank (Beta)" />
            <p
                class="w-8/12 rounded-lg bg-gradient-to-tr from-violet-500 to-purple-500 p-4 text-center text-xs text-white"
            >
                Welcome to our innovative AI Question Generator. This is
                currently a beta version, and we're actively working to refine
                and improve it. Please note that while our AI strives to produce
                high-quality, relevant questions, there may be occasional
                inaccuracies or unexpected results. We appreciate your
                understanding and patience during this testing phase. Your
                feedback is invaluable in helping us make this tool the best it
                can be.
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
                            Questions For
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
                        class="w-5 cursor-pointer text-zinc-600 hover:scale-125 hover:text-black"
                    />
                </div>
                <div
                    v-for="(item, index) in selectedQuestion?.questions"
                    :key="index"
                    class="group my-3 flex cursor-pointer flex-col space-y-4 rounded-lg p-4 font-medium shadow-sm hover:bg-zinc-600 hover:text-white"
                    :class="index % 2 === 1 ? 'bg-gray-50' : 'bg-gray-50/50'"
                >
                    <div class="flex w-full flex-col space-y-3 text-sm">
                        <span>
                            {{ item.question }}
                        </span>

                        <span
                            class="rounded-lg border border-black p-4 text-xs shadow-sm group-hover:border-gray-50"
                        >
                            Answer : {{ item.answer }}</span
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
                <div class="text-3xl font-semibold">Recent Questions</div>
                <TableElement
                    :filterable="false"
                    :selectable="false"
                    header-style="bg-zinc-800 text-white "
                    class="cursor-pointer !rounded-none !shadow-none"
                    :data="formattedQuestionData"
                    :columns="config"
                    :footer-style="questions.links?.length > 3 ? '' : '!p-0'"
                    row-actionable
                >
                    <template #row-actions="{ row }">
                        <EyeIcon
                            class="w-4 cursor-pointer text-gray-800 hover:scale-125"
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
        <EmptyView title="No Questions Generated Yet" />
        <SecondaryButton
            title="Generate Questions"
            class="w-2/12 !rounded-2xl bg-purple-600 py-2 font-medium uppercase text-white"
        />
    </div>

    <DialogBox v-model:open="showDeleteDialogBox" @confirm="deleteQuestion" />

    <Modal v-model:view="showUpdateModal">
        <div
            class="flex flex-col items-center space-y-5 rounded-lg bg-white p-4 shadow-sm"
        >
            <div class="text-xl font-semibold">Update Question</div>
            <TextArea
                v-model="updateForm.question"
                placeholder="Add Question"
                class="w-full"
                label="Question"
            />
            <TextArea
                v-model="updateForm.answer"
                class="w-full"
                placeholder="Add answer"
                label="Answer"
            />

            <TextArea
                v-model="updateForm.answer"
                class="w-full"
                placeholder="Add answer"
                label="Answer"
            />
            <SecondaryButton
                title="Update"
                class="w-3/12 !rounded-2xl bg-zinc-800 text-2xl text-white"
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
        name: "Assessment Type",
        key: "type",
        class: "!py-4 !text-xs",
    },
    {
        name: "Subject",
        key: "subject",
    },
    {
        name: "By",
        key: "name",
    },
    {
        name: "No Of Questions",
        key: "no_of_questions",
    },
    {
        name: "Difficulty Level",
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
</script>
<style scoped></style>
