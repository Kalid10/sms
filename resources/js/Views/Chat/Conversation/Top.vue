<template>
    <div
        class="flex min-h-[4.5rem] w-full items-center justify-between bg-brand-150 px-5"
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
                        class="text-xs text-brand-text-400"
                    >
                        Active Now
                    </h1>
                    <h1 v-else class="text-xs text-brand-text-400">Offline</h1>
                </div>
            </div>

            <div class="flex w-2/12 items-center justify-end space-x-5">
                <StarIcon
                    class="w-4 cursor-pointer hover:scale-125 hover:text-yellow-400"
                    :class="
                        isInFavorites(activeChat.id).value
                            ? 'text-yellow-400'
                            : 'text-brand-text-250'
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
                    @confirm="messageStore.deleteConversation(activeChat.id)"
                />
            </div>
        </div>
    </div>
</template>
<script setup>
import { ChevronLeftIcon, StarIcon, TrashIcon } from "@heroicons/vue/20/solid";
import DialogBox from "@/Components/DialogBox.vue";
import { computed, ref } from "vue";
import useMessageStore from "@/Store/chat";

const messageStore = useMessageStore();
const messages = computed(() => messageStore.messages.messages);
const activeChat = computed(() => messageStore.activeChat);
const favorites = computed(() => messageStore.favorites);
const showDeleteDialog = ref(false);

const isInFavorites = (id) => {
    return computed(() => {
        if (!Array.isArray(favorites.value)) {
            // return a default value if favorites.value is not an array
            return false;
        }
        return favorites.value.some((favorite) => favorite.favorite_id === id);
    });
};
</script>

<style scoped></style>
