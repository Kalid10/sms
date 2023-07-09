<template>
    <Loading v-if="showLoading" is-full-screen color="secondary" />
    <div
        v-if="activeChat?.id !== null"
        class="scrollbar-hide flex h-full max-h-full w-9/12 flex-col items-center justify-between overflow-y-auto border-l border-white bg-white shadow-md"
    >
        <!-- top -->
        <div
            class="flex min-h-[4.5rem] w-full items-center justify-between bg-gray-100 px-5"
        >
            <div class="flex h-full w-full items-center justify-between pr-3">
                <div class="flex w-6/12 items-center space-x-4">
                    <ChevronLeftIcon
                        class="w-8 cursor-pointer hover:scale-125"
                        @click="messageStore.activeChat.id = null"
                    />

                    <div class="relative">
                        <img
                            :src="`https://xsgames.co/randomusers/avatar.php?g=male`"
                            alt="avatar"
                            class="w-12 rounded-full object-contain"
                        />
                        <div
                            v-if="activeChat.active_status"
                            class="absolute right-[0px] bottom-[4px] h-3 w-3 rounded-full border-2 border-white bg-[#41D37E]"
                        ></div>
                    </div>

                    <div>
                        <h1
                            class="w-fit overflow-hidden text-ellipsis whitespace-nowrap text-sm font-medium"
                        >
                            {{ activeChat.name }}
                        </h1>
                        <h1
                            v-if="activeChat.active_status"
                            class="text-xs text-violet-100"
                        >
                            Active Now
                        </h1>
                        <h1 v-else class="text-xs text-zinc-400">Offline</h1>
                    </div>
                </div>

                <div class="flex w-2/12 items-center justify-end space-x-5">
                    <StarIcon
                        class="w-4 cursor-pointer hover:scale-125 hover:text-yellow-400"
                        :class="
                            isInFavorites(activeChat.id).value
                                ? 'text-yellow-400'
                                : 'text-gray-300'
                        "
                        @click="messageStore.toggleFavorite(null)"
                    />
                    <TrashIcon
                        class="w-4 cursor-pointer text-red-500 hover:scale-125 hover:text-red-600"
                        @click="showDeleteDialog = true"
                    />

                    <!--                    TODO: Change message-->
                    <DialogBox
                        v-model:open="showDeleteDialog"
                        class="!z-[1000]"
                        @confirm="
                            messageStore.deleteConversation(activeChat.id)
                        "
                    />
                </div>
            </div>
        </div>

        <!-- content -->
        <div
            class="scrollbar-hide flex h-full w-full flex-col items-center justify-between bg-gray-100 bg-contain pb-4"
            style="background-image: url('/assets/chat.png')"
        >
            <div
                v-if="messages.length"
                ref="chatContent"
                class="scrollbar-hide flex h-full w-full flex-col items-center justify-between overflow-y-auto pb-4"
            >
                <div
                    class="scrollbar-hide mt-1 flex w-full flex-col space-y-4 overflow-y-auto p-4"
                >
                    <div
                        v-for="(text, index) in messages.slice().reverse()"
                        :key="index"
                    >
                        <div
                            class="group flex cursor-pointer space-x-5"
                            :class="
                                text.from_id === usePage().props.auth.user.id
                                    ? 'flex-row-reverse'
                                    : ''
                            "
                        >
                            <img
                                :src="`https://xsgames.co/randomusers/avatar.php?g=male`"
                                alt="avatar"
                                class="mx-1 h-8 w-8 rounded-full object-contain"
                            />
                            <div
                                class="rounded-b-[10px] px-[14px] py-[10px]"
                                :class="
                                    text.from_id ===
                                    usePage().props.auth.user.id
                                        ? 'bg-gradient-to-bl from-violet-500 to-purple-500 text-white rounded-l-[10px] ml-11'
                                        : 'bg-[#F5F6FA] rounded-r-[10px] mr-11'
                                "
                            >
                                <p class="break-all text-sm">
                                    {{ text.body }}
                                </p>

                                <span
                                    class="flex items-center justify-center space-x-1"
                                >
                                    <h1
                                        class="text-right text-[0.6rem] font-medium text-violet-200"
                                    >
                                        {{ moment(text.created_at).fromNow() }}
                                    </h1>

                                    <CheckIcon
                                        v-if="
                                            text.from_id ===
                                                usePage().props.auth.user.id &&
                                            text.seen
                                        "
                                        class="w-3 text-gray-200"
                                    />
                                </span>
                            </div>
                            <TrashIcon
                                class="hidden w-4 cursor-pointer group-hover:inline-block"
                                @click="
                                    messageStore.deleteMessage(
                                        text.id,
                                        text.to_id
                                    )
                                "
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div v-else class="flex h-5/6 items-center text-sm font-light">
                <span class="rounded-2xl bg-gray-200 px-6 py-1">
                    No previous messages found!
                </span>
            </div>

            <!-- bottom -->
            <div class="flex w-11/12 px-5">
                <div class="relative w-full">
                    <div
                        class="absolute inset-y-0 left-0 z-10 flex items-center"
                    >
                        <FaceSmileIcon
                            class="w-8 cursor-pointer pl-3 text-zinc-700 hover:scale-125 hover:text-purple-500"
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
                        class="block w-full rounded-3xl border border-gray-300 p-2.5 px-10 py-[13px] text-sm text-gray-900 focus:ring-0 focus:ring-violet-500"
                        placeholder="Type a message..."
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
        </div>
    </div>
    <div
        v-else
        style="background-image: url('/assets/chat.png')"
        class="flex max-h-screen w-8/12 flex-col items-center justify-center space-y-2 rounded-lg border-zinc-500 bg-gray-100 shadow-sm"
    >
        <div class="text-3xl font-medium">Welcome back to rigel chat.</div>
        <div class="text-sm font-light">
            Please select chat to start messaging
        </div>
    </div>
</template>
<script setup>
import { usePage } from "@inertiajs/vue3";
import moment from "moment/moment";
import { computed, nextTick, ref } from "vue";
import {
    CheckIcon,
    ChevronLeftIcon,
    StarIcon,
    TrashIcon,
} from "@heroicons/vue/20/solid";
import { FaceSmileIcon } from "@heroicons/vue/24/outline";
import useMessageStore from "@/Store/chat";
import DialogBox from "@/Components/DialogBox.vue";
import Loading from "@/Components/Loading.vue";
import EmojiPicker from "vue3-emoji-picker";
import "vue3-emoji-picker/css";
import { onClickOutside } from "@vueuse/core";

const emit = defineEmits(["toggle", "contact", "fetch"]);
const chatContent = ref(null);
const messageField = ref(null);
const isMessageSent = ref(false);

const messageStore = useMessageStore();
const messages = computed(() => messageStore.messages.messages);
const activeChat = computed(() => messageStore.activeChat);
const favorites = computed(() => messageStore.favorites);
const showDeleteDialog = ref(false);
const showLoading = computed(() => messageStore.isLoading);
const showEmoji = ref(false);
const emoji = ref(null);

async function sendMessage() {
    if (messageField.value) {
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
    }
}

const isInFavorites = (id) => {
    return computed(() => {
        if (!Array.isArray(favorites.value)) {
            // return a default value if favorites.value is not an array
            return false;
        }
        return favorites.value.some((favorite) => favorite.favorite_id === id);
    });
};
onClickOutside(emoji, () => {
    showEmoji.value = false;
});
const onSelectEmoji = (emoji) => {
    messageField.value += emoji.i;
    showEmoji.value = false;
};
</script>

<style scoped></style>
