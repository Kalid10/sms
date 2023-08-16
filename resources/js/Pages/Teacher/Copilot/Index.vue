<template>
    <div class="flex min-h-screen w-full flex-col space-y-4 p-5">
        <div class="flex w-full space-x-5">
            <Title
                class="w-4/12 pl-8"
                :title="$t('copilotIndex.rigelCopilot')"
            />

            <div v-if="openAILimitReached" class="z-50 flex w-10/12">
                <span
                    class="flex w-fit items-center space-x-2 rounded-lg bg-red-600 p-4 text-center text-white shadow-sm"
                >
                    <ExclamationCircleIcon class="w-5 text-white" />
                    <span>
                        {{ $t("copilotIndex.dailyLimit") }}
                    </span>
                </span>
            </div>
        </div>
        <div class="flex h-fit w-full justify-center px-4">
            <TabElement v-model:active="activeTab" class="h-full" :tabs="tabs">
                <template #[chatTab]>
                    <div class="h-full py-5">
                        <Chat @limit-reached="setLimitInfo" />
                    </div>
                </template>
                <template #[questionsTab]>
                    <QuestionPreparation @limit-reached="setLimitInfo" />
                </template>
            </TabElement>
        </div>
    </div>
</template>
<script setup>
import Title from "@/Views/Teacher/Views/Title.vue";
import Chat from "@/Views/Teacher/Views/Copilot/Chat/Index.vue";
import TabElement from "@/Components/TabElement.vue";
import { computed, ref } from "vue";
import QuestionPreparation from "@/Views/Teacher/Views/Copilot/QuestionPreparation.vue";
import { ExclamationCircleIcon } from "@heroicons/vue/20/solid";

import { useI18n } from "vue-i18n";
import { toUnderscore } from "@/utils";
import { usePage } from "@inertiajs/vue3";

const { t } = useI18n();
const chatTab = toUnderscore(t("common.chat"));
const questionsTab = toUnderscore(t("common.questions"));
const tabs = [chatTab, questionsTab];

const activeTabFromQuery = computed(() => usePage().props.active_tab);
const activeTab = ref(activeTabFromQuery.value ?? chatTab);

const openAILimitReached = ref(false);
const openAIDailyUsage = ref();

const setLimitInfo = (usage) => {
    openAILimitReached.value = true;
    openAIDailyUsage.value = usage;
};
</script>

<style scoped></style>
