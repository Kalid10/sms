<template>
    <div class="flex w-8/12 max-w-4xl pb-4">
        <div class="relative w-full">
            <div class="absolute inset-y-0 left-0 z-10 flex items-center">
                <FaceSmileIcon
                    class="w-8 cursor-pointer pl-3 text-brand-text-400 hover:scale-125 hover:text-purple-500"
                    @click="showEmoji = true"
                />
            </div>

            <EmojiPicker
                v-show="showEmoji"
                ref="emoji"
                class="absolute left-0 bottom-full z-20 mb-3"
                :native="true"
                @select="onSelectEmoji"
            />

            <input
                v-model="messageField"
                type="text"
                class="block w-full rounded-3xl border border-gray-300 p-3 px-10 text-sm text-brand-text-500 focus:border-none focus:outline-none focus:ring-1 focus:ring-violet-500"
                :placeholder="$t('rigelChat.typeMessage')"
                required
                @keydown.enter="sendMessage()"
            />

            <button
                v-if="!isMessageSent && messageField"
                type="button"
                class="absolute inset-y-0 right-0 flex items-center pr-3"
                @click="sendMessage()"
            >
                <svg
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M20.4322 4.0612C20.8183 3.8998 21.22 4.25585 21.1062 4.65856L16.9945 19.2003C16.9069 19.5104 16.553 19.6578 16.2711 19.5016L11.9004 17.0806L8.57687 20.0765L9.08837 15.8618L9.25236 15.6138L9.23723 15.6055L17.8407 7.21288L6.89056 14.3056L3.07739 12.1934C2.7118 11.9909 2.74124 11.4559 3.12684 11.2947L20.4322 4.0612Z"
                        fill="#111528"
                    />
                </svg>
            </button>
            <svg
                v-else-if="isMessageSent"
                class="absolute right-3 -mt-9 h-6 w-6 animate-spin text-black"
                fill="none"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
            >
                <circle
                    class="opacity-25"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="4"
                ></circle>
                <path
                    class="opacity-75"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                    fill="currentColor"
                ></path>
            </svg>
        </div>
    </div>
</template>

<script setup>
import EmojiPicker from "vue3-emoji-picker";
import { FaceSmileIcon } from "@heroicons/vue/24/outline";
import { computed, nextTick, ref } from "vue";
import { onClickOutside } from "@vueuse/core";
import useMessageStore from "@/Store/chat";

const emit = defineEmits(["toggle", "contact", "fetch"]);

const messageStore = useMessageStore();

const showEmoji = ref(false);
const isMessageSent = ref(false);

const activeChat = computed(() => messageStore.activeChat);
const messageField = ref(null);

// Refs
const chatContent = ref(null);
const emoji = ref(null);

const isSending = ref(false);

async function sendMessage() {
    if (messageField.value && !isSending.value) {
        isSending.value = true;

        isMessageSent.value = true;
        const postData = {
            id: activeChat.value.id,
            message: messageField.value,
        };

        await messageStore.sendMessage(postData);
        messageField.value = null;
        isMessageSent.value = false;
        await messageStore.getContacts();
        // scroll to bottom
        // chatContent.value.scrollTop = chatContent.value.scrollHeight
        await nextTick(() => {
            chatContent.value.scrollTop = chatContent.value.scrollHeight;
        });

        isSending.value = false;
    }
}

onClickOutside(emoji, () => {
    showEmoji.value = false;
});
const onSelectEmoji = (emoji) => {
    messageField.value += emoji.i;
    showEmoji.value = false;
};
</script>

<style scoped></style>
