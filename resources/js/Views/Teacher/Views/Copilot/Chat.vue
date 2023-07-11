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
                v-if="messages.length > 0 && !isLoading"
                :title="
                    isChatUpdating
                        ? 'Stop Generating'
                        : 'Regenerate Last Message'
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
                <TextInput
                    v-model="inputMessage"
                    type="text"
                    class="w-full"
                    class-style="rounded-2xl ring-purple-600 ring-2 bg-gray-50 border-none bg-white placeholder:text-xs focus:ring-2 ring-black focus:ring-purple-500"
                    :placeholder="$t('chat.typeYourMessageHere')"
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
            v-if="showGettingStarted"
            class="mt-5 hidden h-fit w-4/12 flex-col space-y-6 rounded-lg border border-black p-5 text-center text-sm lg:block"
        >
            <h2 class="text-2xl font-bold">{{ $t('chat.gettingStarted')}}</h2>
            <p>{{ $t('chat.helloWeWant', { name: usePage().props.auth.user.name }) }}</p>

            <p>
<!--                Hello, {{ usePage().props.auth.user.name }}! We want to make-->
<!--                sure you get the most out of our AI chat feature, Rigel Copilot.-->
<!--                Here are some tips to guide you:-->
            </p>

            <div>
                <h3 class="text-xl font-semibold">{{ $t('chat.askSpecificQuestions')}} </h3>
                <p class="font-light">
                    {{ $t('chat.theAiChat')}}
                </p>
            </div>

            <div>
                <h3 class="text-xl font-semibold">
                    {{ $t('chat.experimentWithDifferent')}}
                </h3>
                <p class="font-light">
                    {{ $t('chat.feelFree')}}
                </p>
            </div>

            <div>
                <h3 class="text-xl font-semibold">
                    {{ $t('chat.useItAsResource')}}
                </h3>
                <p class="font-normal">
                    {{ $t('chat.needHelpFinding')}}
                </p>
            </div>

            <div>
                <h3 class="text-xl font-semibold">
                    {{ $t('chat.seekClarification')}}
                </h3>
                <p class="font-light">
                    {{ $t('chat.ifYourAreDealing')}}
                </p>
            </div>

            <div>
                <h3 class="text-xl font-semibold">{{ $t('chat.exploreCreativeIdeas')}} </h3>
                <p class="font-light">
                    {{ $t('chat.theAIChat')}}
                </p>
            </div>

            <p class="py-4 italic">
                {{ $t('chat.RememberWhile')}}
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
import SecondaryButton from "@/Components/SecondaryButton.vue";

defineProps({
    showGettingStarted: {
        type: Boolean,
        default: true,
    },
});
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
</script>
<style scoped></style>
