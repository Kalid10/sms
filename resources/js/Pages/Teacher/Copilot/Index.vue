<template>
    <div class="flex min-h-screen w-full flex-col space-y-4 bg-gray-50 p-5">
        <Title class="w-4/12 pl-8" :title="$t('copilotIndex.rigelCopilot')"/>

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
