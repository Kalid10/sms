<template>
    <div className="flex max-h-screen min-h-screen w-full justify-between">
        <Messages @toggle-conversation="toggleConversation" />

        <!-- conversation -->
        <Conversation @toggle="toggleConversation" />
    </div>
</template>

<script setup>
import { computed, onMounted, onUnmounted, watch } from "vue";
import Messages from "@/Views/Chat/Messages/Messages.vue";
import Conversation from "@/Views/Chat/Conversation/Index.vue";
import useMessageStore from "@/Store/chat";
import { useChatPusherStore } from "@/Store/chatPusher";

const messageStore = useMessageStore();
const messagePusherStore = useChatPusherStore();

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
    messagePusherStore.authenticate();
});

onUnmounted(() => {
    messageStore.setActiveStatus(false);
});

// Watch for active chat changes
watch(
    () => messageStore.activeChat,
    async (chat) => {
        if (chat) {
            await messagePusherStore.listen();
        }
    }
);
</script>
