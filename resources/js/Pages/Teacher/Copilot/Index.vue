<template>
    <div class="flex min-h-screen w-full flex-col space-y-2 bg-gray-50 p-5">
        <div class="flex w-full justify-between px-5">
            <Title class="w-6/12" title="Rigel Copilot" />

            <!--            <div-->
            <!--                class="flex w-5/12 space-x-4 rounded-sm bg-gradient-to-tl from-purple-500 to-violet-500 p-3"-->
            <!--            >-->
            <!--                <ExclamationCircleIcon class="w-5 text-white" />-->
            <!--                <p class="w-11/12 text-sm text-white shadow-sm">-->
            <!--                    Please note: Copilot's suggestions are not guaranteed to be-->
            <!--                    100% accurate. It's a tool for idea generation, not a-->
            <!--                    substitute for professional judgement. Always review and-->
            <!--                    verify AI-generated content before use. Thank you.-->
            <!--                </p>-->
            <!--            </div>-->
        </div>

        <div class="flex h-full w-full justify-center px-4">
            <TabElement v-model:active="activeTab" class="h-full" :tabs="tabs">
                <template #chat>
                    <div class="h-screen">
                        <Chat />
                    </div>
                </template>
                <template #questions>
                    <QuestionPreparation />
                </template>
            </TabElement>
        </div>
    </div>
</template>
<script setup>
import Title from "@/Views/Teacher/Views/Title.vue";
import Chat from "@/Views/Teacher/Views/Copilot/Chat.vue";
import TabElement from "@/Components/TabElement.vue";
import { ref } from "vue";
import QuestionPreparation from "@/Views/Teacher/Views/Copilot/QuestionPreparation.vue";

const tabs = ["Chat", "Questions"];
const activeTab = ref("Questions");
</script>

<style scoped></style>
