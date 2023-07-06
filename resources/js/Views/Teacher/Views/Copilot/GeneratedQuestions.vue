<template>
    <div
        class="flex w-8/12 flex-col space-y-4 rounded-lg bg-white p-4 shadow-sm"
    >
        <div class="text-center text-3xl font-medium">Generated Questions</div>
        <div>
            <div
                v-for="(question, index) in questions"
                :key="index"
                class="group my-3 flex cursor-pointer flex-col space-y-4 rounded-lg p-3 font-medium hover:bg-zinc-800 hover:text-white"
                :class="index % 2 === 1 ? 'bg-gray-50' : ''"
            >
                <div class="flex w-full flex-col space-y-3">
                    <span>
                        {{ question.question }}
                    </span>

                    <span
                        class="rounded-lg border border-black p-4 text-xs shadow-sm group-hover:border-gray-50"
                    >
                        Answer : {{ question.answer }}</span
                    >
                    <span class="flex w-full justify-between space-x-6 text-xs">
                        <span>
                            Difficulty Level:
                            {{ question.difficulty }}/10
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const questions = computed(() => usePage().props.questions);
const difficulty = (value) => {
    switch (value) {
        case 1 - 4:
            return "Easy";
        case 5 - 7:
            return "Medium";
        case 8 - 10:
            return "Hard";
        default:
            return "Unknown";
    }
};
</script>
<style scoped></style>
