<template>
    <EmptyView :is-full-screen="true">
        <template #default>
            <Title
                :title="$t('questionEmptyView.welcometext')"
                :is-date-open="false"
                class="text-black"
            />

            <p>
                {{ $t("questionEmptyView.hintOne") }}
            </p>

            <p>
                {{ $t("questionEmptyView.hintTwo") }}
            </p>

            <p>
                {{ $t("questionEmptyView.hintThree") }}
            </p>

            <p>
                {{ $t("questionEmptyView.hintFour") }}
            </p>

            <SecondaryButton
                :title="$t('teacherQuestions.generateQuestions')"
                class="w-6/12 !rounded-2xl bg-brand-400 py-2 font-medium uppercase text-white"
                @click="$emit('route')"
            />
        </template>
    </EmptyView>
</template>
<script setup>
import Title from "@/Views/Teacher/Views/Title.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import EmptyView from "@/Views/EmptyView.vue";

defineEmits(["route"]);
</script>

<style scoped></style>
