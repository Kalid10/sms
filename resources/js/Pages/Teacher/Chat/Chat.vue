<template>
    <div
        class="flex max-h-screen min-h-screen w-full justify-between py-5 px-10"
    >
        <Messages @toggle-conversation="toggleConversation" />

        <!-- conversation -->
        <Conversation @toggle="toggleConversation" />
    </div>
</template>

<script setup>
import { computed, onMounted, onUnmounted } from "vue";
import Messages from "@/Pages/Teacher/Chat/Messages/Messages.vue";
import Conversation from "@/Pages/Teacher/Chat/Conversation.vue";
import useMessageStore from "@/Store/chat";

const messageStore = useMessageStore();

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
    messageStore.getFavorites();
});

onUnmounted(() => {
    messageStore.setActiveStatus(false);
});
</script>
