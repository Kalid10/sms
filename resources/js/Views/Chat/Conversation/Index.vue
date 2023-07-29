<template>
    <Loading v-if="showLoading" is-full-screen color="secondary" />
    <div
        v-if="activeChat?.id !== null"
        style="background-image: url('/assets/chat.png')"
        class="flex h-full max-h-full w-9/12 flex-col items-center justify-between overflow-hidden border-l border-white bg-brand-100 shadow-md"
    >
        <Top class="w-full" />

        <Content class="scrollbar-hide w-full grow overflow-y-auto" />

        <Bottom class="w-full" />
    </div>

    <!--    Landing view-->
    <div
        v-else
        style="background-image: url('/assets/chat.png')"
        class="flex max-h-screen w-8/12 flex-col items-center justify-center space-y-2 rounded-lg border-zinc-500 bg-brand-100 shadow-sm"
    >
        <div class="text-3xl font-medium">
            {{ $t("rigelChat.welcome") }}
        </div>
        <div class="text-sm font-light">
            {{ $t("rigelChat.selectChat") }}
        </div>
    </div>
</template>
<script setup>
import { computed } from "vue";
import useMessageStore from "@/Store/chat";
import Loading from "@/Components/Loading.vue";
import "vue3-emoji-picker/css";
import Top from "@/Views/Chat/Conversation/Top.vue";
import Bottom from "@/Views/Chat/Conversation/Bottom.vue";
import Content from "@/Views/Chat/Conversation/Content.vue";

const emit = defineEmits(["toggle", "contact", "fetch"]);

const messageStore = useMessageStore();
const messages = computed(() => messageStore.messages.messages);
const activeChat = computed(() => messageStore.activeChat);

const showLoading = computed(() => messageStore.isLoading);
</script>

<style scoped></style>
