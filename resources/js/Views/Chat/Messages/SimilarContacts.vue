<template>
    <div class="flex w-full flex-col justify-center space-y-5 bg-white pb-3">
        <div class="flex w-full justify-evenly bg-purple-500 p-3 text-white">
            <div class="flex grow justify-center text-center text-xl italic">
                <ChatBubbleOvalLeftEllipsisIcon class="w-5 text-white" />
                <span class="pl-1"> People You May Want to Connect With </span>
            </div>
            <XMarkIcon
                class="w-4 cursor-pointer text-purple-200 hover:scale-125 hover:text-white"
                @click="$emit('close')"
            />
        </div>
        <div
            v-if="similarUsers?.length"
            class="scrollbar-hide flex w-full flex-wrap justify-between overflow-y-auto px-6"
        >
            <div
                v-for="(chat, index) in similarUsers"
                :key="index"
                class="group my-3 flex h-36 w-5/12 cursor-pointer flex-col items-center justify-evenly space-y-2 rounded-lg bg-violet-50/40 p-2 shadow-sm hover:bg-purple-500 hover:text-white"
                @click="$emit('loadChat', chat)"
            >
                <!-- profile pic -->
                <div class="relative">
                    <img
                        :src="`https://xsgames.co/randomusers/avatar.php?g=male`"
                        alt="avatar"
                        class="h-12 w-12 rounded-full object-contain"
                    />
                    <div
                        class="absolute right-[0px] bottom-[4px] h-3 w-3 rounded-full border-2 border-white"
                        :class="
                            chat.active_status ? 'bg-green-500' : 'bg-gray-600'
                        "
                    ></div>
                </div>

                <h1
                    class="overflow-hidden whitespace-nowrap text-xs font-medium"
                >
                    {{ chat.name }}
                </h1>

                <SecondaryButton
                    title="Chat"
                    class="! w-7/12 rounded-xl bg-violet-500 !py-1 text-xs text-white group-hover:bg-violet-100 group-hover:text-black"
                    @click="$emit('loadChat', chat)"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { XMarkIcon } from "@heroicons/vue/20/solid";
import { ChatBubbleOvalLeftEllipsisIcon } from "@heroicons/vue/24/outline";
import { usePage } from "@inertiajs/vue3";
import useMessageStore from "@/Store/chat";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const emit = defineEmits(["loadChat", "close"]);

const messageStore = useMessageStore();
const similarUsers = computed(() => usePage().props.similar_users);
</script>
<style scoped></style>
