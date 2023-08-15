<template>
    <div
        class="scrollbar-hide flex h-full w-full flex-col space-y-5 overflow-y-auto border-r border-zinc-100 px-5 py-2"
    >
        <Loading
            v-if="showLoading"
            color="info"
            class="absolute z-50 flex h-full w-4/12 items-center justify-center"
        />

        <div class="flex justify-between">
            <div>
                <div class="mb-1 text-2xl font-bold">
                    {{ $t("lessonPlanCopilot.rigelCopilot") }}
                </div>
                <div class="w-full text-sm text-gray-600">
                    {{ $t("lessonPlanCopilot.intro") }}
                </div>
            </div>

            <XMarkIcon
                class="w-5 cursor-pointer text-black hover:scale-125 hover:text-red-600"
                @click="emit('close')"
            />
        </div>

        <div
            v-if="
                !generateNoteSuggestions &&
                !noteSuggestions &&
                !showLoading &&
                !openAILimitReached
            "
            class="min-h-screen"
        >
            <TabElement
                v-model:active="activeTab"
                class="min-h-screen"
                :tabs="tabs"
            >
                <template #[chatTab]>
                    <Chat
                        class="!h-[40rem] !w-full"
                        :show-getting-started="false"
                        @limit-reached="setLimitInfo"
                    />
                </template>

                <template #[questionsTab]>
                    <QuestionPreparation
                        :batch-subject-id="batchSubjectId"
                        :lesson-plan-id="lessonPlanId"
                        @limit-reached="setLimitInfo"
                    />
                </template>
            </TabElement>
        </div>

        <div
            v-if="openAILimitReached"
            class="flex h-5/6 w-full flex-col items-center justify-center space-y-2 px-3"
        >
            <ExclamationTriangleIcon class="w-7 text-red-600" />
            <div class="text-center text-2xl font-semibold">
                You have reached you AI daily limit. Please get in touch with
                the administrator to increase limit.
            </div>
        </div>

        <div
            v-if="noteSuggestions"
            ref="selectedTextPopUp"
            class="flex flex-col space-y-2.5 rounded-lg bg-violet-100 p-3 text-sm text-black shadow-sm"
        >
            <div class="text-center text-xl font-semibold">
                {{ $t("lessonPlanCopilot.lessonPlanExplained") }}
            </div>
            <div
                class="px-4"
                @mouseup="
                    showPopup = true;
                    selectedText = getSelectedText();
                    setPosition($event);
                "
            >
                {{ noteSuggestions }}
                <span v-if="isNoteUpdating" class="animate-blink">|</span>

                <div class="flex w-full justify-end px-2 pt-2">
                    <ClipboardDocumentIcon
                        class="w-4 cursor-pointer text-brand-text-250 hover:text-black"
                        @click="
                            copyToClipboardAndShowToast(noteSuggestions, $event)
                        "
                    />
                    <Toast
                        :show-toast="showCopyToast"
                        class="!bg-purple-500 !text-white"
                        :event="toastEvent"
                        @copied="showCopyToast = false"
                    />
                </div>
            </div>
        </div>

        <div v-if="questionSuggestions" class="flex flex-col space-y-1 p-3">
            <div class="pb-3 text-brand-text-450">
                {{ $t("lessonPlanCopilot.potentialQuestions") }}
            </div>
            <div class="flex flex-col space-y-2">
                <div
                    v-for="(item, index) in questionSuggestions"
                    :key="index"
                    class="group flex cursor-pointer justify-between rounded-lg bg-brand-100 p-3 text-sm font-medium text-brand-text-450 hover:bg-brand-150 hover:text-brand-text-500"
                >
                    {{ item }}
                    <ClipboardDocumentIcon
                        class="w-4 text-brand-text-250 group-hover:text-black"
                        @click="copyToClipboardAndShowToast(item, $event)"
                    />
                    <Toast
                        :show-toast="showCopyToast"
                        class="!bg-purple-500 !text-white"
                        :event="toastEvent"
                        @copied="showCopyToast = false"
                    />
                </div>
            </div>
        </div>

        <div
            v-if="showPopup && selectedText"
            ref="selectedTextPopUp"
            :style="{ left: `${x}px`, top: `${y}px` }"
            class="fixed z-50 flex flex-col items-center space-y-3 rounded border border-gray-100 bg-gradient-to-tl from-purple-500 to-violet-500 px-4 py-3 text-white shadow"
        >
            <p class="max-w-3xl px-2 py-1 text-xs">
                {{ selectedText }}
            </p>
            <div class="flex w-full justify-center space-x-3">
                <div
                    class="flex w-fit cursor-pointer items-center space-x-1 !rounded-2xl bg-brand-100 px-3 py-1 text-black hover:scale-105"
                >
                    <ClipboardDocumentIcon class="w-3" />
                    <div
                        class="!py-0.5 !px-0 text-xs font-medium"
                        @click="addToDescription"
                    >
                        {{ $t("lessonPlanCopilot.copyToLessonPlan") }}
                    </div>
                </div>
                <div
                    class="flex w-fit cursor-pointer items-center space-x-1 !rounded-2xl bg-brand-100 px-2 py-1 text-black hover:scale-105"
                >
                    <MagnifyingGlassIcon class="w-3" />
                    <div
                        class="!py-0.5 !px-0 text-xs font-medium"
                        @click="
                            router.visit(
                                `https://www.google.com/search?q=${encodeURIComponent(
                                    selectedText
                                )}`
                            )
                        "
                    >
                        {{ $t("lessonPlanCopilot.searchGoogle") }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {
    ClipboardDocumentIcon,
    ExclamationTriangleIcon,
    MagnifyingGlassIcon,
    XMarkIcon,
} from "@heroicons/vue/20/solid";
import Loading from "@/Components/Loading.vue";
import { computed, inject, ref, watch } from "vue";
import { onClickOutside } from "@vueuse/core";
import { router, usePage } from "@inertiajs/vue3";
import { copyToClipboard, toUnderscore } from "@/utils";
import QuestionPreparation from "@/Views/Teacher/Views/LessonPlans/QuestionPreparation.vue";
import Chat from "@/Views/Teacher/Views/Copilot/Chat/Index.vue";
import Toast from "@/Components/Toast.vue";
import TabElement from "@/Components/TabElement.vue";
import { useI18n } from "vue-i18n";

const emit = defineEmits(["selectedText", "finish", "close"]);
const props = defineProps({
    topic: {
        type: String,
        default: null,
    },
    generateNoteSuggestions: {
        type: Boolean,
        default: false,
    },
    generateQuestionSuggestions: {
        type: Boolean,
        default: false,
    },
    lessonPlanId: {
        type: Number,
        required: true,
    },
    batchSubjectId: {
        type: Number,
        required: true,
    },
});

const showLoading = ref(false);
const noteSuggestions = ref("");
const isNoteUpdating = ref(false);
const questionSuggestions = computed(() => usePage().props.questions);
const showNotification = inject("showNotification");

const updateNoteSuggestion = () => {
    if (showLoading.value) {
        return;
    }
    showLoading.value = true;

    // Remove previous suggestions
    if (noteSuggestions.value) {
        noteSuggestions.value = "";
    }
    if (props.generateNoteSuggestions) {
        let es = new EventSource(
            "/teacher/lesson-plan/ai/note?prompt=" +
                props.topic +
                "&batch_subject_id=" +
                props.batchSubjectId
        );

        es.addEventListener("limit_reached", (event) => {
            showLoading.value = false;
            showNotification({
                type: "error",
                message: "You Have Reached Your Daily AI Limit", // "Limit reached"
                position: "top-center",
            });
            es.close();
        });

        es.addEventListener(
            "update",
            (event) => {
                if (event.data) {
                    isNoteUpdating.value = true;
                    showLoading.value = false;
                    if (event.data === "<END_STREAMING_SSE>") {
                        showLoading.value = false;
                        isNoteUpdating.value = false;
                        es.close();
                    } else noteSuggestions.value += event.data;
                }
            },
            false
        );

        es.addEventListener(
            "error",
            (event) => {
                showLoading.value = false;
                showNotification({
                    type: "error",
                    message: "Something went wrong.Please try again!",
                    position: "top-center",
                });
            },
            false
        );
    }
    if (props.generateQuestionSuggestions) {
        router.visit("/teacher/lesson-plan?prompt=" + props.topic, {
            only: ["questions"],
            preserveState: true,
            onFinish: () => {
                showLoading.value = false;
            },
        });
    }
    emit("finish");
};
watch(
    () => [props.generateQuestionSuggestions, props.generateNoteSuggestions],
    () => {
        if (props.generateNoteSuggestions || props.generateQuestionSuggestions)
            updateNoteSuggestion();
    }
);

const showPopup = ref(false);
const selectedText = ref("");
const x = ref(0);
const y = ref(0);

const getSelectedText = () => window.getSelection().toString();

const setPosition = (event) => {
    x.value = event.clientX;
    y.value = event.clientY;
};

const addToDescription = () => {
    emit("selectedText", selectedText.value);
    selectedText.value = "";
    showPopup.value = false;
};
const selectedTextPopUp = ref(null);

onClickOutside(selectedTextPopUp, () => {
    showPopup.value = !showPopup.value;
    if (!showPopup.value) selectedText.value;
});

const showCopyToast = ref(false);
const toastEvent = ref(null);
const copyToClipboardAndShowToast = (value, event) => {
    copyToClipboard(value);
    showCopyToast.value = true;
    toastEvent.value = event;
};

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
