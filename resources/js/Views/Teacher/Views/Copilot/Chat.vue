<template>
    <div class="flex h-screen w-full justify-between space-x-8">
        <div
            class="flex h-4/6 max-h-screen w-8/12 flex-col rounded-lg border border-black bg-white p-4"
        >
            <div
                ref="chatContainer"
                class="scrollbar-hide flex grow flex-col space-y-4 overflow-y-auto rounded-lg bg-gray-50 p-4 shadow"
            >
                <div
                    v-for="(message, index) in messages"
                    :key="index"
                    class="group cursor-pointer rounded-lg hover:bg-zinc-100 hover:p-4"
                >
                    <div
                        v-if="message.content"
                        class="flex w-full justify-evenly space-x-2 p-1"
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
                        <div class="hidden justify-end p-1 group-hover:flex">
                            <ClipboardDocumentIcon
                                class="w-5 cursor-pointer text-zinc-700"
                                @click="copyToClipboard(message.content)"
                            />
                        </div>
                    </div>
                </div>

                <div v-if="isLoading" class="mr-auto bg-white">
                    <Loading size="small" type="bounce" color="info" />
                </div>
            </div>

            <div
                class="mt-4 flex w-full items-center justify-center space-x-4 rounded-lg bg-gray-50/70 p-4 shadow-sm"
            >
                <TextInput
                    v-model="inputMessage"
                    type="text"
                    class="w-full"
                    class-style="
rounded-2xl ring-purple-600 ring-2 bg-gray-50 border-none bg-white placeholder:text-xs focus:ring-2 ring-black focus:ring-purple-500"
                    placeholder="Type your message here..."
                    @keyup.enter="sendMessage"
                />
                <button
                    class="rounded-md bg-purple-500 p-2 text-white"
                    @click="sendMessage"
                >
                    <PaperAirplaneIcon class="w-5" />
                </button>
            </div>
        </div>
        <div
            class="mt-5 flex h-fit w-4/12 flex-col space-y-6 rounded-lg border border-black p-5 text-center text-sm"
        >
            <h2 class="text-2xl font-bold">Getting Started with the AI Chat</h2>
            <p>
                Hello, {{ usePage().props.auth.user.name }}! We want to make
                sure you get the most out of our AI chat feature, Rigel Copilot.
                Here are some tips to guide you:
            </p>

            <div>
                <h3 class="text-xl font-semibold">Ask Specific Questions</h3>
                <p class="font-light">
                    The AI chat is more effective when you ask specific
                    questions...
                </p>
            </div>

            <div>
                <h3 class="text-xl font-semibold">
                    Experiment with Different Queries
                </h3>
                <p class="font-light">
                    Feel free to experiment with different types of questions or
                    queries...
                </p>
            </div>

            <div>
                <h3 class="text-xl font-semibold">
                    Use It as a Resource Finder
                </h3>
                <p class="font-normal">
                    Need help finding educational resources? You can ask the AI
                    chat for recommendations...
                </p>
            </div>

            <div>
                <h3 class="text-xl font-semibold">
                    Seek Clarification on Complex Topics
                </h3>
                <p class="font-light">
                    If you're dealing with complex educational topics, don't
                    hesitate to ask the AI chat...
                </p>
            </div>

            <div>
                <h3 class="text-xl font-semibold">Explore Creative Ideas</h3>
                <p class="font-light">
                    The AI chat can be a great tool to brainstorm new teaching
                    techniques...
                </p>
            </div>

            <p class="py-4 italic">
                Remember, while the AI chat is a powerful tool, it's not a
                replacement for human interaction...
            </p>
        </div>
    </div>
</template>
<script setup>
import {
    ClipboardDocumentIcon,
    PaperAirplaneIcon,
} from "@heroicons/vue/20/solid";
import { nextTick, ref, watchEffect } from "vue";
import { copyToClipboard } from "@/utils";
import Loading from "@/Components/Loading.vue";
import TextInput from "@/Components/TextInput.vue";
import { usePage } from "@inertiajs/vue3";

const isLoading = ref(false);
const messages = ref([]);
const inputMessage = ref("Who is usain bolt?");
const isChatUpdating = ref(false);
const chatContainer = ref(null);
let eventSource;

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

    // Push a new message from the assistant
    messages.value.push({
        role: "assistant",
        content: result.value,
    });

    result.value = null;

    eventSource.addEventListener(
        "error",
        (event) => {
            // TODO: Replace this with your preferred error handling method
            console.error("Error occurred: ", event);
            isLoading.value = false;
            isChatUpdating.value = false;
        },
        false
    );
};
</script>
<style scoped></style>
