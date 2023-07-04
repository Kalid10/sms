<template>
    <div class="flex min-h-screen w-full justify-between py-5 px-10">
        <Messages @toggle-conversation="toggleConversation" />

        <!-- conversation -->
        <Conversation @toggle="toggleConversation" />
    </div>

    <!-- notification -->
    <div v-if="notificationClicked">
        <template>
            <div
                class="flex items-center justify-between border-b border-[#75757533] p-5 md:p-[30px]"
            >
                <h1 class="text-[28px] font-bold">Notification</h1>
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="h-6 w-6 cursor-pointer"
                    @click="notificationClicked = false"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
            </div>
        </template>

        <template>
            <div
                class="mt-5 flex flex-col px-5 text-center md:mt-[70px] md:px-8"
            >
                <img
                    :src="`https://xsgames.co/randomusers/avatar.php?g=male`"
                    alt="avatar"
                    class="w-20 rounded-md object-contain"
                />
                <h1
                    class="mt-[42px] text-2xl font-bold tracking-[0.01em] text-[#E71C25] md:text-[28px]"
                >
                    Check in on your Progress
                </h1>
                <p class="mt-5 tracking-[1px] text-[#6B6D7B]">
                    Your provider would like to check-in. It's a free visit to
                    make sure you're making progress with your Erectile
                    Dysfunction treatment.
                </p>
            </div>

            <div
                class="mx-5 mt-[42px] flex flex-col gap-[10px] rounded-lg border border-[#AEAFB733] bg-[#FBFAFC] p-[30px] md:mx-[30px] md:mt-12"
            >
                <div class="flex gap-5">
                    <svg
                        width="24"
                        height="25"
                        viewBox="0 0 24 25"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                        class="shrink-0"
                    >
                        <path
                            d="M9 22.8704H15C20 22.8704 22 20.8704 22 15.8704V9.87036C22 4.87036 20 2.87036 15 2.87036H9C4 2.87036 2 4.87036 2 9.87036V15.8704C2 20.8704 4 22.8704 9 22.8704Z"
                            stroke="#111528"
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                        <path
                            d="M7.75 12.8704L10.58 15.7004L16.25 10.0404"
                            stroke="#111528"
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>
                    <p class="font-medium tracking-[0.01em] text-[#1C2034]">
                        Answer a few quick questions about your treatment in
                        this 3 minute visit.
                    </p>
                </div>

                <div class="flex gap-5">
                    <svg
                        width="24"
                        height="25"
                        viewBox="0 0 24 25"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                        class="shrink-0"
                    >
                        <path
                            d="M9 22.8704H15C20 22.8704 22 20.8704 22 15.8704V9.87036C22 4.87036 20 2.87036 15 2.87036H9C4 2.87036 2 4.87036 2 9.87036V15.8704C2 20.8704 4 22.8704 9 22.8704Z"
                            stroke="#111528"
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                        <path
                            d="M7.75 12.8704L10.58 15.7004L16.25 10.0404"
                            stroke="#111528"
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>
                    <p class="font-medium tracking-[0.01em] text-[#1C2034]">
                        If you need to make changes or have concerns about your
                        treatment, we'll let your provider know and they will
                        reach out to you.
                    </p>
                </div>
            </div>
        </template>

        <template>
            <div class="mt-12 mb-5 flex justify-center">
                <button
                    class="h-[50px] w-[264px] rounded-lg bg-[#272A3F] text-lg font-bold text-[#F7F6F2] md:h-[60px]"
                >
                    Begin Check-in
                </button>
            </div>
        </template>
    </div>
</template>

<script setup>
import { computed, onMounted, onUnmounted, ref } from "vue";
import Messages from "@/Pages/Teacher/Chat/Messages/Messages.vue";
import Conversation from "@/Pages/Teacher/Chat/Conversation.vue";
import useMessageStore from "@/Store/message";

const messageStore = useMessageStore();

const searchField = ref(null);
const notificationClicked = ref(false);

const messages = computed(() => messageStore.messages);

async function toggleConversation(chat) {
    messageStore.activeChat = chat;

    await messageStore.fetchMessages(chat.id);
    await messageStore.markAsRead(chat.id);
    await messageStore.getContacts();
}

onMounted(() => {
    messageStore.getContacts();
    messageStore.setActiveStatus(true);
});

onUnmounted(() => {
    messageStore.setActiveStatus(false);
});
</script>
