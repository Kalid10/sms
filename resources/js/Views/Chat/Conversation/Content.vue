<template>
    <div
        v-if="messages?.length"
        ref="chatContent"
        class="scrollbar-hide flex h-full w-full flex-col items-center justify-between overflow-y-auto pb-1"
    >
        <div
            ref="chatContainer"
            class="scrollbar-hide flex w-full flex-col space-y-4 overflow-y-auto p-4"
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
                            text.from_id === usePage().props.auth.user.id
                                ? 'bg-gradient-to-bl from-violet-500 to-purple-500 text-white rounded-l-[10px] ml-11'
                                : 'bg-zinc-500 text-white rounded-r-[10px] mr-11'
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
                        @click="messageStore.deleteMessage(text.id, text.to_id)"
                    />
                </div>
            </div>
        </div>
    </div>
    <div
        v-else
        class="flex h-5/6 w-full items-center justify-center text-sm font-light"
    >
        <span class="rounded-2xl bg-gray-200 px-6 py-1">
            No previous messages found!
        </span>
    </div>
</template>
<script setup>
import { usePage } from "@inertiajs/vue3";
import moment from "moment";
import useMessageStore from "@/Store/chat";
import { computed, onUpdated, ref } from "vue";
import { CheckIcon, TrashIcon } from "@heroicons/vue/20/solid";

const messageStore = useMessageStore();
const messages = computed(() => messageStore.messages.messages);

const chatContainer = ref(null);

onUpdated(() => {
    const container = chatContainer.value;
    if (!container) return;
    container.scrollTop = container.scrollHeight;
});
</script>
<style scoped></style>
