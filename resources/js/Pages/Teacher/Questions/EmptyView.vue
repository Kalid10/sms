<template>
    <div
        class="flex h-screen w-full items-center justify-evenly space-y-5 font-semibold"
    >
        <div class="flex w-3/12 justify-center">
            <question-mark-circle-icon class="h-60 text-brand-400" />
        </div>
        <div
            class="flex h-5/6 w-6/12 flex-col items-center justify-evenly px-2 text-gray-700"
        >
            <Title
                title="Welcome to Rigel Question Generator"
                :is-date-open="false"
                class="text-black"
            />

            <p>
                A feature specifically designed to simplify and enhance your
                question creation process. Here, we've streamlined the task of
                crafting effective, relevant, and challenging educational
                questions to an art of few clicks.
            </p>

            <p>
                Whether you're preparing classwork, homework, or even tests, our
                Question Generator has got you covered. Generate questions
                directly from your lesson plans, ensuring that your assessments
                are perfectly aligned with your lessons, while also reducing
                preparation time significantly. This feature serves as your
                ultimate educational companion, freeing up your time to focus on
                what matters the most: teaching and inspiring your students.
            </p>

            <p>
                But we didn't stop there. For those seeking a more personalized
                touch, you can also manually input your questions. This gives
                you the opportunity to customize every aspect of your
                question-creation process, thereby creating a unique and
                engaging learning experience for each student.
            </p>

            <p>
                Our Question Generator is more than just a tool. It's a stepping
                stone to enhancing the learning journey of your students. So why
                wait? Step into a world of seamless question creation today, and
                elevate your teaching experience to greater heights.
            </p>

            <SecondaryButton
                :title="$t('teacherQuestions.generateQuestions')"
                class="w-6/12 !rounded-2xl bg-brand-400 py-2 font-medium uppercase text-white"
                @click="$emit('route')"
            />
        </div>
        <!--        <EmptyView :title="$t('teacherQuestions.noQuestionsGenerated')" />-->
    </div>
</template>
<script setup>
import Title from "@/Views/Teacher/Views/Title.vue";
import { QuestionMarkCircleIcon } from "@heroicons/vue/20/solid/index.js";
import SecondaryButton from "@/Components/SecondaryButton.vue";

defineEmits(["route"]);
</script>

<style scoped></style>
