<template>
    <div class="flex min-h-screen w-full flex-col space-y-2 bg-gray-50 p-5">
        <div class="flex w-full justify-between px-5">
            <Title class="w-6/12" title="Rigel Copilot" />
            <div
                class="flex w-5/12 space-x-4 rounded-sm bg-gradient-to-tl from-purple-500 to-violet-500 p-3"
            >
                <ExclamationCircleIcon class="w-5 text-white" />
                <p class="w-11/12 text-sm text-white shadow-sm">
                    Please note: Copilot's suggestions are not guaranteed to be
                    100% accurate. It's a tool for idea generation, not a
                    substitute for professional judgement. Always review and
                    verify AI-generated content before use. Thank you.
                </p>
            </div>
        </div>

        <div class="flex h-full w-6/12 px-4">
            <div class="flex h-5/6 max-h-screen w-full flex-col rounded-lg p-4">
                <div
                    class="scrollbar-hide flex grow flex-col space-y-4 overflow-y-auto rounded-lg bg-white p-4 shadow"
                >
                    <div
                        v-for="(message, index) in messages"
                        :key="index"
                        :class="
                            message.role === 'user'
                                ? 'ml-auto bg-zinc-700'
                                : 'mr-auto bg-purple-500'
                        "
                        class="group cursor-pointer rounded-lg"
                    >
                        <div
                            v-if="message.content"
                            class="flex w-full flex-col p-1"
                        >
                            <div
                                class="w-full px-4 py-1 text-sm leading-6 text-white"
                            >
                                {{ message.content }}
                            </div>
                            <div
                                class="hidden justify-end p-1 group-hover:flex"
                            >
                                <ClipboardDocumentIcon
                                    class="w-4 cursor-pointer text-zinc-200 hover:text-white"
                                    @click="copyToClipboard(message.content)"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="mt-4 flex items-center rounded-lg bg-white p-4 shadow"
                >
                    <input
                        v-model="inputMessage"
                        type="text"
                        class="mr-2 grow rounded-lg bg-white p-2"
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
        </div>
    </div>
</template>
<script setup>
import Title from "@/Views/Teacher/Views/Title.vue";
import {
    ClipboardDocumentIcon,
    ExclamationCircleIcon,
    PaperAirplaneIcon,
} from "@heroicons/vue/20/solid";
import { ref, watchEffect } from "vue";
import { copyToClipboard } from "@/utils";

const messages = ref([]);
const inputMessage = ref("Who is usain bolt?");
let eventSource;

const sendMessage = () => {
    messages.value.push({ role: "user", content: inputMessage.value });
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
    if (eventSource) {
        eventSource.close();
    }

    eventSource = new EventSource(
        "/teacher/copilot/chat?messages=" +
            encodeURIComponent(JSON.stringify(messages.value))
    );

    let result = ref("");
    eventSource.addEventListener(
        "update",
        (event) => {
            if (event.data === "<END_STREAMING_SSE>") {
                eventSource.close();
            } else {
                if (event.data) {
                    messages.value[messages.value.length - 1].content +=
                        event.data;
                }
            }
        },
        false
    );

    messages.value.push({
        role: "assistant",
        content: result.value,
    });

    result.value = null;

    eventSource.addEventListener(
        "error",
        (event) => {
            // TODO: Handle error
            console.error("Error occurred: ", event);
        },
        false
    );
};
</script>

<style scoped></style>
