<template>
    <div
        class="scrollbar-hide flex h-full w-full flex-col space-y-5 overflow-y-auto rounded-lg border border-zinc-500 p-5"
    >
        <div>
            <div class="mb-1 text-2xl font-bold">Rigel Copilot</div>
            <div class="w-8/12 text-sm text-gray-700">
                I'm your Rigel co-pilot. I will assist you as you create your
                lesson plan.
            </div>
        </div>
        <Loading
            v-if="showLoading"
            color="info"
            class="absolute z-50 flex h-full w-4/12 items-center justify-center"
        />
        <div
            v-if="noteSuggestions"
            ref="selectedTextPopUp"
            class="flex flex-col space-y-1 p-3"
        >
            <div class="px-1 pb-1">Notes</div>
            <div
                class="rounded-lg bg-zinc-100 p-3 text-sm text-black shadow-sm"
                @mouseup="
                    showPopup = true;
                    selectedText = getSelectedText();
                    setPosition($event);
                "
            >
                {{ noteSuggestions }}
                <span v-if="isNoteUpdating" class="animate-blink">|</span>

                <div class="flex w-full justify-end">
                    <ClipboardDocumentIcon
                        class="w-4 cursor-pointer text-zinc-400 hover:text-black"
                        @click="copyToClipboard(noteSuggestions)"
                    />
                </div>
            </div>
        </div>

        <div v-if="questionSuggestions" class="flex flex-col space-y-1 p-3">
            <div class="pb-3 text-zinc-800">Potential Questions</div>
            <div class="flex flex-col space-y-2">
                <div
                    v-for="(item, index) in questionSuggestions"
                    :key="index"
                    class="group flex cursor-pointer justify-between rounded-lg bg-zinc-100 p-3 text-sm font-medium text-zinc-800 hover:bg-zinc-200 hover:text-zinc-900"
                >
                    {{ item }}
                    <ClipboardDocumentIcon
                        class="w-4 text-zinc-400 group-hover:text-black"
                        @click="copyToClipboard(item)"
                    />
                </div>
            </div>
        </div>

        <div
            v-if="showPopup && selectedText"
            ref="selectedTextPopUp"
            :style="{ left: `${x}px`, top: `${y}px` }"
            class="fixed z-50 flex flex-col items-center space-y-3 rounded border border-gray-100 bg-gradient-to-t from-purple-500 to-violet-500 px-4 py-3 text-white shadow"
        >
            <p class="max-w-3xl px-2 py-1 text-xs">
                {{ selectedText }}
            </p>
            <div class="flex w-full justify-center space-x-3">
                <div
                    class="flex w-fit cursor-pointer items-center space-x-1 !rounded-2xl bg-gray-100 px-3 py-1 text-black hover:scale-105"
                >
                    <ClipboardDocumentIcon class="w-3" />
                    <div
                        class="!py-0.5 !px-0 text-xs font-medium"
                        @click="addToDescription"
                    >
                        Copy To Lesson Plan
                    </div>
                </div>
                <div
                    class="flex w-fit cursor-pointer items-center space-x-1 !rounded-2xl bg-gray-100 px-2 py-1 text-black hover:scale-105"
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
                        Search Google
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {
    ClipboardDocumentIcon,
    MagnifyingGlassIcon,
} from "@heroicons/vue/20/solid";
import Loading from "@/Components/Loading.vue";
import { computed, ref, watch } from "vue";
import { onClickOutside } from "@vueuse/core";
import { router, usePage } from "@inertiajs/vue3";
import { copyToClipboard } from "@/utils";

const emit = defineEmits(["selectedText", "finish"]);
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
});
const showLoading = ref(false);
const noteSuggestions = ref("");
const isNoteUpdating = ref(false);
const questionSuggestions = computed(() => usePage().props.questions);

const updateNoteSuggestion = () => {
    showLoading.value = true;
    if (noteSuggestions.value) {
        noteSuggestions.value = "";
    }
    if (props.generateNoteSuggestions) {
        let es = new EventSource(
            "/teacher/lesson-plan/ai/note?prompt=" + props.topic
        );
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
                // TODO: Handle error
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
</script>
<style scoped></style>
