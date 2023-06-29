<template>
    <div
        v-if="questions?.data?.length"
        class="flex h-screen w-11/12 flex-col space-y-4 bg-gray-50/60 p-4"
    >
        <Title title="My Questions" />

        <div class="flex w-full justify-between">
            <div
                v-if="selectedQuestion"
                class="w-5/12 rounded-lg border border-black p-3 shadow-sm"
            >
                <div class="text-center text-xl font-semibold">
                    Generated For
                    {{
                        selectedQuestion.batch_subject.subject.full_name +
                        " " +
                        selectedQuestion.assessment_type.name
                    }}
                </div>
                <div
                    v-for="(item, index) in selectedQuestion?.questions"
                    :key="index"
                    class="group my-3 flex cursor-pointer flex-col space-y-4 rounded-lg bg-white p-4 font-medium shadow-sm hover:bg-zinc-800 hover:text-white"
                    :class="index % 2 === 1 ? 'bg-gray-50' : ''"
                >
                    <div class="flex w-full flex-col space-y-3">
                        <span>
                            {{ item.question }}
                        </span>

                        <span
                            class="rounded-lg border border-black p-4 text-xs shadow-sm group-hover:border-gray-50"
                        >
                            Answer : {{ item.answer }}</span
                        >
                        <span
                            class="flex w-full justify-between space-x-6 pt-2 text-xs"
                        >
                            <span>
                                Difficulty Level:
                                {{ item.difficulty }}/10
                            </span>
                            <span class="flex cursor-pointer space-x-3">
                                <TrashIcon
                                    class="w-4 text-red-600 hover:scale-105 group-hover:text-red-500"
                                />
                                <PencilSquareIcon
                                    class="w-4 text-black hover:scale-105 group-hover:text-white"
                                />
                            </span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex w-6/12 flex-col items-center space-y-3 px-4">
                <div class="text-2xl font-medium">Recent Questions</div>
                <TableElement
                    :filterable="false"
                    :selectable="false"
                    header-style="bg-zinc-800 text-white"
                    :data="formattedQuestionData"
                    :columns="config"
                >
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
</template>
<script setup>
import { computed, ref, watch } from "vue";
import { usePage } from "@inertiajs/vue3";
import EmptyView from "@/Views/EmptyView.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Title from "@/Views/Teacher/Views/Title.vue";
import TableElement from "@/Components/TableElement.vue";
import Pagination from "@/Components/Pagination.vue";
import { PencilSquareIcon, TrashIcon } from "@heroicons/vue/20/solid";

const questions = computed(() => usePage().props.questions);
const selectedQuestion = ref(questions.value.data[4]);

watch(questions, (newValue) => {
    console.log("there is new value");
    console.log(newValue);
});

const formattedQuestionData = computed(() => {
    return questions.value.data.map((question) => {
        return {
            id: question.id,
            name: question.user.name,
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
</script>
<style scoped></style>
