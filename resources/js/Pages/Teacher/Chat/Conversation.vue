<template>
    <div
        v-if="activeChat?.id !== null"
        class="flex max-h-max w-8/12 flex-col items-center justify-between space-y-2 rounded-lg border border-zinc-300 bg-gray-50 p-4 shadow-sm"
    >
        <!-- top -->
        <div
            class="flex w-full items-center justify-between rounded-lg border border-zinc-100 bg-zinc-800 p-4 text-white"
        >
            <div class="flex items-center space-x-3">
                <ChevronLeftIcon
                    class="w-8 cursor-pointer text-zinc-200 hover:scale-125"
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
                        class="w-fit overflow-hidden text-ellipsis whitespace-nowrap text-sm font-medium text-zinc-100"
                    >
                        {{ activeChat.name }}
                    </h1>
                    <h1
                        v-if="activeChat.active_status"
                        class="text-xs text-zinc-400"
                    >
                        Active Now
                    </h1>
                    <h1 v-else class="text-xs text-zinc-400">Offline</h1>
                </div>
            </div>

            <div>
                <svg
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M10 19C10 20.1 10.9 21 12 21C13.1 21 14 20.1 14 19C14 17.9 13.1 17 12 17C10.9 17 10 17.9 10 19Z"
                        fill="#292D32"
                    />
                    <path
                        d="M10 5C10 6.1 10.9 7 12 7C13.1 7 14 6.1 14 5C14 3.9 13.1 3 12 3C10.9 3 10 3.9 10 5Z"
                        fill="#292D32"
                    />
                    <path
                        d="M10 12C10 13.1 10.9 14 12 14C13.1 14 14 13.1 14 12C14 10.9 13.1 10 12 10C10.9 10 10 10.9 10 12Z"
                        fill="#292D32"
                    />
                </svg>
            </div>
        </div>

        <!-- content -->
        <div
            v-if="messages"
            ref="chatContent"
            class="scrollbar-hide h-full w-full overflow-y-auto"
        >
            <div
                class="scrollbar-hide mt-1 flex flex-col space-y-4 overflow-y-auto p-4"
            >
                <div
                    v-for="(text, index) in messages.slice().reverse()"
                    :key="index"
                >
                    <div
                        class="flex gap-[14px]"
                        :class="
                            text.from_id === usePage().props.auth.user.id
                                ? 'flex-row-reverse'
                                : ''
                        "
                    >
                        <img
                            :src="`https://xsgames.co/randomusers/avatar.php?g=male`"
                            alt="avatar"
                            class="h-8 w-8 rounded-full object-contain"
                        />
                        <div
                            class="rounded-b-[10px] px-[14px] py-[10px]"
                            :class="
                                text.from_id === usePage().props.auth.user.id
                                    ? 'bg-[#111528] text-white rounded-l-[10px] ml-11'
                                    : 'bg-[#F5F6FA] rounded-r-[10px] mr-11'
                            "
                        >
                            <p class="break-all text-[13px]">
                                {{ text.body }}
                            </p>

                            <span
                                class="flex items-center justify-center space-x-1"
                            >
                                <h1
                                    class="text-right text-xs font-medium text-[#A4A5A6]"
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
                    </div>
                </div>
            </div>
        </div>

        <!-- bottom -->
        <div class="flex w-full justify-between px-5">
            <div class="relative w-full">
                <button
                    type="button"
                    class="absolute inset-y-0 left-0 flex items-center pl-3"
                >
                    <svg
                        width="18"
                        height="20"
                        viewBox="0 0 18 20"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M15.2711 10.3978L9.09108 16.5878C8.28089 17.3078 7.22622 17.691 6.1428 17.6591C5.05938 17.6272 4.02908 17.1826 3.26265 16.4162C2.49622 15.6497 2.05159 14.6195 2.01969 13.536C1.9878 12.4526 2.37106 11.3979 3.09108 10.5878L11.0911 2.58775C11.5687 2.13405 12.2023 1.88108 12.8611 1.88108C13.5198 1.88108 14.1535 2.13405 14.6311 2.58775C15.0964 3.05934 15.3573 3.69522 15.3573 4.35775C15.3573 5.02028 15.0964 5.65616 14.6311 6.12775L7.73108 13.0178C7.66279 13.0913 7.58069 13.1507 7.48945 13.1925C7.39822 13.2343 7.29965 13.2577 7.19936 13.2614C7.09907 13.2651 6.99904 13.2491 6.90496 13.2141C6.81089 13.1792 6.72462 13.126 6.65108 13.0578C6.57754 12.9895 6.51817 12.9074 6.47636 12.8161C6.43455 12.7249 6.41112 12.6263 6.4074 12.526C6.40369 12.4257 6.41976 12.3257 6.45471 12.2316C6.48966 12.1376 6.54279 12.0513 6.61108 11.9778L11.7411 6.85775C11.9294 6.66945 12.0352 6.41405 12.0352 6.14775C12.0352 5.88145 11.9294 5.62605 11.7411 5.43775C11.5528 5.24945 11.2974 5.14366 11.0311 5.14366C10.7648 5.14366 10.5094 5.24945 10.3211 5.43775L5.19108 10.5778C4.93438 10.8325 4.73064 11.1355 4.59161 11.4693C4.45257 11.8031 4.38099 12.1611 4.38099 12.5228C4.38099 12.8844 4.45257 13.2424 4.59161 13.5762C4.73064 13.91 4.93438 14.213 5.19108 14.4678C5.71545 14.9672 6.41189 15.2458 7.13608 15.2458C7.86027 15.2458 8.55671 14.9672 9.08108 14.4678L15.9711 7.56775C16.766 6.7147 17.1987 5.58642 17.1781 4.42062C17.1576 3.25481 16.6853 2.1425 15.8608 1.31802C15.0363 0.493542 13.924 0.0212704 12.7582 0.000701082C11.5924 -0.0198682 10.4641 0.412871 9.61108 1.20775L1.61108 9.20775C0.532277 10.4026 -0.0438939 11.9676 0.00261124 13.5767C0.0491163 15.1859 0.714711 16.715 1.86072 17.8455C3.00672 18.9761 4.54475 19.6208 6.15436 19.6454C7.76397 19.6701 9.32101 19.0727 10.5011 17.9778L16.6911 11.7978C16.7843 11.7045 16.8583 11.5938 16.9087 11.472C16.9592 11.3502 16.9852 11.2196 16.9852 11.0878C16.9852 10.9559 16.9592 10.8253 16.9087 10.7035C16.8583 10.5817 16.7843 10.471 16.6911 10.3778C16.5978 10.2845 16.4872 10.2106 16.3653 10.1601C16.2435 10.1096 16.1129 10.0837 15.9811 10.0837C15.8492 10.0837 15.7187 10.1096 15.5968 10.1601C15.475 10.2106 15.3643 10.2845 15.2711 10.3778V10.3978Z"
                            fill="#757575"
                        />
                    </svg>
                </button>
                <input
                    id="voice-search"
                    v-model="messageField"
                    type="text"
                    class="block w-full rounded-lg border border-gray-300 p-2.5 px-10 py-[13px] text-sm text-gray-900 focus:ring-0 focus:ring-zinc-500"
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
</template>
<script setup>
import { usePage } from "@inertiajs/vue3";
import moment from "moment/moment";
import { computed, nextTick, onMounted, ref } from "vue";
import { CheckIcon, ChevronLeftIcon } from "@heroicons/vue/20/solid";
import useMessageStore from "@/Store/message";

const emit = defineEmits(["toggle", "contact", "fetch"]);
const chatContent = ref(null);
const messageField = ref(null);
const isMessageSent = ref(false);

const messageStore = useMessageStore();
const messages = computed(() => messageStore.messages.messages);

const activeChat = computed(() => messageStore.activeChat);

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

onMounted(() => {
    // nextTick(() => {
    //     chatContent.value.scrollTop = chatContent.value.scrollHeight;
    // });
});
</script>

<style scoped></style>
