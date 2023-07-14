<template>
    <div
        class="flex h-screen w-full flex-col py-10 lg:flex-row lg:space-x-8"
        :class="showGettingStarted ? 'justify-between' : 'justify-center'"
    >
        <div
            class="flex h-5/6 max-h-screen flex-col items-center space-y-2 rounded-lg border border-black bg-white p-4"
            :class="
                showGettingStarted ? 'w-full lg:w-8/12' : 'w-full lg:w-11/12'
            "
        >
            <div
                ref="chatContainer"
                class="scrollbar-hide flex w-full grow flex-col space-y-4 overflow-y-auto rounded-lg bg-gray-50 p-4 shadow"
            >
                <div
                    v-for="(message, index) in messages"
                    :key="index"
                    class="group flex w-full cursor-pointer rounded-lg"
                    :class="
                        message.role !== 'assistant'
                            ? 'justify-end'
                            : 'justify-start'
                    "
                >
                    <div
                        v-if="message.content"
                        class="flex w-fit max-w-3xl space-x-2 p-1"
                    >
                        <div
                            :class="
                                message.role === 'user'
                                    ? 'ml-auto bg-zinc-700'
                                    : 'mr-auto bg-purple-500'
                            "
                            class="w-fit max-w-5xl rounded-lg px-4 py-1 text-sm leading-6 text-white"
                        >
                            {{ message.content }}
                            <span
                                v-if="
                                    isChatUpdating &&
                                    message.role === 'assistant'
                                "
                                class="animate-blink"
                                >|</span
                            >
                        </div>
                    </div>

                    <div
                        v-if="message.role === 'assistant'"
                        class="hidden px-1 group-hover:flex"
                    >
                        <ClipboardDocumentIcon
                            class="w-3 cursor-pointer text-zinc-700"
                            @click="copyToClipboard(message.content)"
                        />
                    </div>
                </div>

                <div v-if="isLoading" class="mr-auto bg-white">
                    <Loading size="small" type="bounce" color="info" />
                </div>
            </div>

            <SecondaryButton
                v-if="messages.length > 0 && !isLoading && !openAILimitReached"
                :title="
                    isChatUpdating
                        ? 'Stop Generating'
                        : 'Regenerate Last Response'
                "
                class="w-fit !rounded-2xl !text-xs font-semibold"
                :class="
                    isChatUpdating
                        ? 'bg-red-600 text-white'
                        : 'bg-violet-100 text-zinc-700'
                "
                @click="
                    regenerateResponseAndStopStreaming(
                        isChatUpdating ? 'stop' : 'regenerate'
                    )
                "
            />
            <div
                class="mt-4 flex w-full items-center justify-center space-x-4 rounded-lg bg-gray-50/70 p-4 shadow-sm"
            >
                <textarea
                    ref="inputRef"
                    v-model="inputMessage"
                    :disabled="openAILimitReached"
                    class="scrollbar-hide w-full rounded-2xl border-none bg-gray-50 ring-2 ring-purple-600 placeholder:text-xs focus:ring-2 focus:ring-purple-500"
                    :placeholder="$t('chat.typeYourMessageHere')"
                    :style="{ maxHeight: `${maxRows * lineHeight}px` }"
                    @input="autoResize"
                />

                <button
                    :disabled="openAILimitReached"
                    :class="
                        openAILimitReached
                            ? 'bg-zinc-600 cursor-not-allowed'
                            : 'cursor-pointer bg-purple-500'
                    "
                    class="rounded-md p-2 text-white"
                    @click="sendMessage"
                >
                    <PaperAirplaneIcon class="w-5" />
                </button>
            </div>
        </div>
        <GettingStarted :show-getting-started="showGettingStarted" />
    </div>
</template>
<script setup>
import {
    ClipboardDocumentIcon,
    PaperAirplaneIcon,
} from "@heroicons/vue/20/solid";
import { nextTick, onMounted, ref, watchEffect } from "vue";
import { copyToClipboard } from "@/utils";
import Loading from "@/Components/Loading.vue";
import { usePage } from "@inertiajs/vue3";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import GettingStarted from "@/Views/Teacher/Views/Copilot/Chat/GettingStarted.vue";

const emit = defineEmits(["limit-reached"]);
defineProps({
    showGettingStarted: {
        type: Boolean,
        default: true,
    },
});
const isLoading = ref(false);
const messages = ref([]);
const inputMessage = ref("");
const isChatUpdating = ref(false);
const chatContainer = ref(null);
const openAIDailyUsage = ref();
const openAILimitReached = ref(false);
let eventSource;

onMounted(() => {
    openAIDailyUsage.value = usePage().props.auth.user.openai_daily_usage;
    nextTick(() => {
        autoResize();
    });
});

const sendMessage = () => {
    isLoading.value = true;
    messages.value.push({ role: "user", content: inputMessage.value });

    // Scroll to the bottom of chatContainer after state updates
    nextTick(() => {
        chatContainer.value.scrollTop = chatContainer.value.scrollHeight;
    });

    startStreaming();

    inputMessage.value = "";
};

watchEffect(() => {
    if (
        messages.value.length > 0 &&
        messages.value[messages.value.length - 1].role === "assistant"
    ) {
        startStreaming();
    }
});

const startStreaming = () => {
    isLoading.value = true;
    // Close any existing event source
    if (eventSource) {
        eventSource.close();
    }

    // Create a new event source
    eventSource = new EventSource(
        "/teacher/copilot/chat?messages=" +
            encodeURIComponent(JSON.stringify(messages.value))
    );

    const result = ref(""); // Using const as the variable is never reassigned

    eventSource.addEventListener(
        "update",
        (event) => {
            if (event.data === "<END_STREAMING_SSE>") {
                isLoading.value = false;
                isChatUpdating.value = false;
                eventSource.close();
            } else {
                isLoading.value = false;
                isChatUpdating.value = false;
                // Check if there are any messages before updating the latest one
                if (event.data && messages.value.length > 0) {
                    isChatUpdating.value = true;
                    // Scroll to the bottom of chatContainer after state updates
                    nextTick(() => {
                        chatContainer.value.scrollTop =
                            chatContainer.value.scrollHeight;
                    });

                    messages.value[messages.value.length - 1].content +=
                        event.data;
                }
            }
        },
        false
    );
    eventSource.addEventListener(
        "usage_update",
        (event) => {
            // update usage
            openAIDailyUsage.value = event.data;
        },
        false
    );

    // Push a new message from the assistant
    messages.value.push({
        role: "assistant",
        content: result.value,
    });

    result.value = null;

    eventSource.onerror = function (event) {
        isLoading.value = false;
        isChatUpdating.value = false;

        fetch(eventSource.url)
            .then((response) => {
                if (!response.ok) {
                    return response.json().then(() => {
                        openAILimitReached.value = true;
                        emit("limit-reached", openAIDailyUsage.value);
                    });
                }
            })
            .catch((error) => {
                console.error("Error:", error);
            });
    };

    // Handle Error
    eventSource.addEventListener(
        "error",
        (event) => {
            // TODO: Add error handling method
            isLoading.value = false;
            isChatUpdating.value = false;
        },
        false
    );
};

const stopStreaming = () => {
    if (eventSource) {
        eventSource.close();
        isChatUpdating.value = false;
        isLoading.value = false;
    }
};

const regenerateResponse = () => {
    // Set last message from messages with role user to inputMessage
    const lastMessageFromUser = messages.value
        .slice()
        .reverse()
        .find((message) => message.role === "user");
    inputMessage.value = lastMessageFromUser.content;
    sendMessage();
};

//Merge the regenerateResponse function with the stopStreaming function
const regenerateResponseAndStopStreaming = (param) => {
    if (param === "regenerate") {
        regenerateResponse();
    } else if (param === "stop") {
        stopStreaming();
    }
};

// Input resizing section
const maxRows = 10;
const lineHeight = 40;
const inputRef = ref(null);

const autoResize = () => {
    if (inputRef.value) {
        // Reset the text-area's height to a smaller value
        inputRef.value.style.height = "1px";
        // Set textarea height according to scrollHeight, but limit it to maxRows
        let newHeight = Math.min(
            inputRef.value.scrollHeight,
            lineHeight * maxRows
        );
        // If there's only one line or no content, set the height to one line height
        if (inputRef.value.value.split(/\r\n|\r|\n/).length <= 1) {
            newHeight = lineHeight;
        }
        inputRef.value.style.height = `${newHeight}px`;
    }
};
watchEffect(() => {
    autoResize();
});
</script>
<style scoped></style>
