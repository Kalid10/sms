<template>
    <div
        class="scrollbar-hide flex h-full w-full flex-col space-y-5 overflow-y-auto border-r border-zinc-100 p-2"
    >
        <Loading v-if="showLoading" :is-full-screen="true" type="bounce">
            <template #description>
                <div
                    class="flex flex-col items-center justify-center space-y-2 rounded-lg bg-brand-350 p-5 text-brand-150 shadow-md"
                >
                    <SparklesIcon class="w-6 animate-bounce" />
                    <div class="text-sm font-medium">Generating AI Note!</div>
                </div>
            </template>
        </Loading>
        <div class="flex justify-evenly">
            <div class="w-7/12">
                <div class="mb-1 text-2xl font-bold">
                    {{ $t("lessonPlanCopilot.rigelCopilot") }}
                </div>
                <div class="w-full text-sm text-gray-600">
                    {{ $t("lessonPlanCopilot.intro") }}
                </div>
            </div>

            <AIUsageProgress
                class="w-4/12 rounded-lg bg-brand-150 px-5 py-3 shadow-sm"
                :update-usage="updateAIUsage"
                page="/teacher/lesson-plan"
            />
        </div>

        <div v-if="!openAILimitReached" class="min-h-screen px-5">
            <TabElement
                v-model:active="activeTab"
                class="min-h-screen"
                :tabs="tabs"
            >
                <template #[chatTab]>
                    <Chat
                        class="!h-[40rem] !w-full"
                        :show-getting-started="false"
                        page="/teacher/lesson-plan"
                        @limit-reached="setLimitInfo"
                        @update-ai-usage="setAIUsageUpdateValue"
                    />
                </template>

                <template #[notesTab]>
                    <div
                        v-if="noteSuggestion.content"
                        ref="selectedTextPopUp"
                        class="flex flex-col space-y-2.5 rounded-lg bg-violet-100 p-3 py-6 text-sm text-black shadow-sm"
                    >
                        <div class="text-center text-xl font-semibold">
                            {{
                                noteSuggestion.title ??
                                $t("lessonPlanCopilot.lessonPlanExplained")
                            }}
                        </div>
                        <div
                            class="px-4"
                            @mouseup="
                                showPopup = true;
                                selectedText = getSelectedText();
                                setPosition($event);
                            "
                        >
                            {{ noteSuggestion.content }}
                            <span v-if="isNoteUpdating" class="animate-blink"
                                >|</span
                            >

                            <div class="flex w-full justify-end px-2 pt-2">
                                <ClipboardDocumentIcon
                                    class="w-4 cursor-pointer text-brand-text-250 hover:text-black"
                                    @click="
                                        copyToClipboardAndShowToast(
                                            noteSuggestion.content,
                                            $event
                                        )
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
                        <div
                            v-if="!isNoteUpdating"
                            class="flex w-full px-4 text-sm font-medium"
                        >
                            {{ noteSuggestion.date }}
                        </div>
                    </div>

                    <div
                        v-else-if="!showLoading"
                        class="flex h-96 w-full flex-col items-center justify-center space-y-2 px-3"
                    >
                        <ExclamationTriangleIcon class="w-7 text-red-600" />
                        <div class="text-center text-2xl font-semibold">
                            No AI notes have been created for this lesson plan
                            yet. To generate notes, click the AI icon next to
                            the title input on the right side.
                        </div>
                    </div>

                    <div
                        v-if="previousNoteSuggestions.length > 1"
                        class="mt-4 py-4"
                    >
                        <div class="flex w-full justify-evenly">
                            <div
                                class="flex w-5/12 items-center justify-center"
                            >
                                <PrimaryButton
                                    :disabled="selectedNoteIndex === 0"
                                    class="flex items-center justify-center space-x-1 !rounded-full text-sm"
                                    @click="selectedNoteIndex--"
                                >
                                    <ChevronLeftIcon class="w-4" />
                                    <div>Previous</div>
                                </PrimaryButton>
                            </div>
                            <div
                                class="flex w-5/12 items-center justify-center"
                            >
                                <PrimaryButton
                                    :disabled="
                                        selectedNoteIndex ===
                                        previousNoteSuggestions.length - 1
                                    "
                                    class="flex items-center justify-center space-x-1 !rounded-full text-sm"
                                    @click="selectedNoteIndex++"
                                >
                                    <div>Next</div>
                                    <ChevronRightIcon class="w-4" />
                                </PrimaryButton>
                            </div>
                        </div>
                    </div>
                </template>
            </TabElement>
        </div>

        <div
            v-else
            class="flex h-5/6 w-full flex-col items-center justify-center space-y-2 px-3"
        >
            <ExclamationTriangleIcon class="w-7 text-red-600" />
            <div class="text-center text-2xl font-semibold">
                You have reached you AI daily limit. Please get in touch with
                the administrator to increase limit.
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
                        class="!px-0 !py-0.5 text-xs font-medium"
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
                        class="!px-0 !py-0.5 text-xs font-medium"
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
} from "@heroicons/vue/20/solid";
import Loading from "@/Components/Loading.vue";
import { computed, inject, onMounted, ref, watch } from "vue";
import { onClickOutside } from "@vueuse/core";
import { router, usePage } from "@inertiajs/vue3";
import { copyToClipboard, toUnderscore } from "@/utils";
import Chat from "@/Views/Teacher/Views/Copilot/Chat/Index.vue";
import Toast from "@/Components/Toast.vue";
import TabElement from "@/Components/TabElement.vue";
import { useI18n } from "vue-i18n";
import AIUsageProgress from "@/Views/Teacher/AIUsageProgress.vue";
import {
    ChevronLeftIcon,
    ChevronRightIcon,
    SparklesIcon,
} from "@heroicons/vue/24/solid";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import moment from "moment";

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
    batchSession: {
        type: Number,
        required: true,
    },
});

const showLoading = ref(false);
const noteSuggestion = ref({
    title: "",
    content: "",
    date: moment().format("dddd, MMMM DD, YYYY"),
});
const isNoteUpdating = ref(false);
const questionSuggestions = computed(() => usePage().props.questions);
const showNotification = inject("showNotification");
const updateAIUsage = ref(false);
const previousNoteSuggestions = computed(() => props.batchSession.ai_notes);

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
const notesTab = toUnderscore(t("common.notes"));
const tabs = [chatTab, notesTab];

const activeTabFromQuery = computed(() => usePage().props.active_tab);
const activeTab = ref(activeTabFromQuery.value ?? notesTab);

const openAILimitReached = ref(false);
const openAIDailyUsage = ref();

const setLimitInfo = (usage) => {
    openAILimitReached.value = true;
    openAIDailyUsage.value = usage;
};

const updateNoteSuggestion = () => {
    if (showLoading.value) {
        return;
    }
    showLoading.value = true;
    updateAIUsage.value = false;

    // Remove previous suggestions
    if (noteSuggestion.value.content) {
        noteSuggestion.value.content = "";
        noteSuggestion.value.title = "";
    }

    if (props.generateNoteSuggestions) {
        activeTab.value = notesTab;

        noteSuggestion.value.title = props.topic;
        let es = new EventSource(
            "/teacher/lesson-plan/ai/generate-note?prompt=" +
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

            updateAIUsage.value = true;
            openAILimitReached.value = true;
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
                        saveNoteSuggestion();
                    } else noteSuggestion.value.content += event.data;
                }
                updateAIUsage.value = true;
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
                updateAIUsage.value = true;
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
                updateAIUsage.value = true;
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

const setAIUsageUpdateValue = (value) => {
    updateAIUsage.value = value;
};

const saveNoteSuggestion = () => {
    router.post(
        "/teacher/lesson-plan/ai/save-note",
        {
            content: noteSuggestion.value.content,
            title: props.topic,
            lesson_plan_id: props.lessonPlanId,
            batch_session_id: props.batchSession?.id,
        },
        {
            preserveState: true,
        }
    );
};

const selectedNoteIndex = ref(0);
onMounted(() => {
    if (previousNoteSuggestions.value.length) {
        setNoteSuggestion(
            previousNoteSuggestions.value[selectedNoteIndex.value].content,
            previousNoteSuggestions.value[selectedNoteIndex.value].title
        );
    }
});

watch(selectedNoteIndex, (newValue) => {
    if (
        previousNoteSuggestions.value.length &&
        selectedNoteIndex.value < previousNoteSuggestions.value.length &&
        selectedNoteIndex.value >= 0
    ) {
        setNoteSuggestion(
            previousNoteSuggestions.value[selectedNoteIndex.value].content,
            previousNoteSuggestions.value[selectedNoteIndex.value].title
        );
    }
});

const setNoteSuggestion = (content, title) => {
    noteSuggestion.value.content = content;
    noteSuggestion.value.title = title;
};
</script>
<style scoped></style>
