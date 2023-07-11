<template>
    <div
        class="scrollbar-hide relative flex h-full w-4/12 flex-col items-center space-y-5 overflow-y-auto bg-white shadow-sm"
    >
        <div
            class="!sticky top-0 z-40 flex w-full flex-col items-center justify-center space-y-5 bg-white"
        >
            <div
                class="flex min-h-[4.5rem] w-full items-center justify-center space-x-2 bg-zinc-200"
            >
                <ChatBubbleBottomCenterIcon class="w-5" />
                <h1 class="text-1xl text-center font-medium">My Messages</h1>
            </div>

            <TextInput
                v-model="searchKey"
                placeholder="Search Teacher, Admin..."
                class="!w-11/12"
                class-style="!w-full  rounded-2xl bg-zinc-50/80 border-none placeholder:text-xs focus:bg-white text  focus:ring-1 focus:ring-zinc-400"
                @click.
            />

            <!--        Favorites-->

            <div
                v-if="favorites.length"
                class="flex h-fit w-11/12 justify-evenly space-x-4 overflow-x-auto whitespace-nowrap py-1 hover:scale-105"
            >
                <div
                    v-for="(item, index) in favorites"
                    :key="index"
                    class="flex w-fit cursor-pointer flex-col items-center justify-center"
                >
                    <img
                        :src="item.user.avatar"
                        alt="avatar"
                        class="h-12 w-12 rounded-full border-2 border-violet-400 object-contain"
                        @click="$emit('toggleConversation', item.user)"
                    />
                    <div class="text-[0.6rem]">{{ item.user.name }}</div>
                </div>
            </div>
        </div>

        <!--        Contact Search-->
        <div v-if="searchKey" class="w-full">
            <ContactSearch
                :show-loading="showLoading"
                @toggle="toggleConversation"
            />
        </div>

        <!--        Chat History-->
        <div v-else class="flex w-full justify-center">
            <div
                v-if="contacts?.length"
                class="scrollbar-hide flex w-full flex-col space-y-2 overflow-y-auto"
            >
                <div
                    v-for="(chat, index) in contacts"
                    :key="index"
                    class="flex h-20 w-full cursor-pointer items-center justify-between px-5 hover:bg-gray-100"
                    :class="
                        messageStore.activeChat.id === chat.id
                            ? 'bg-gray-200/50'
                            : 'bg-white'
                    "
                    @click="$emit('toggleConversation', chat)"
                >
                    <div class="flex h-full w-full items-center space-x-3">
                        <!-- profile pic -->
                        <div class="relative">
                            <img
                                :src="`https://xsgames.co/randomusers/avatar.php?g=male`"
                                alt="avatar"
                                class="w-10 rounded-full object-contain"
                            />
                            <div
                                class="absolute right-[0px] bottom-[4px] h-3 w-3 rounded-full border-2 border-white"
                                :class="
                                    chat.active_status
                                        ? 'bg-green-500'
                                        : 'bg-gray-600'
                                "
                            ></div>
                        </div>

                        <!-- name and last message -->
                        <div class="flex flex-col space-y-2">
                            <h1
                                class="overflow-hidden whitespace-nowrap text-sm font-medium"
                            >
                                {{ chat.name }}
                            </h1>
                            <h1
                                class="block w-[200px] overflow-hidden text-ellipsis whitespace-nowrap text-xs text-gray-400"
                            >
                                {{ chat?.latest_message?.body }}
                            </h1>
                        </div>
                    </div>

                    <!-- time, unread counter and seen -->
                    <div class="flex w-4/12 flex-col items-end space-y-3">
                        <StarIcon
                            class="w-4 cursor-pointer hover:scale-125 hover:text-yellow-400"
                            :class="
                                isInFavorites(chat.id).value
                                    ? 'text-yellow-400'
                                    : 'text-gray-400'
                            "
                            @click="messageStore.toggleFavorite(chat.id)"
                        />
                        <h1
                            v-if="chat?.latest_message"
                            class="text-right text-xs font-medium text-[#A9ABAD]"
                        >
                            {{
                                moment(
                                    chat?.latest_message?.updated_at
                                ).fromNow()
                            }}
                        </h1>
                        <div v-if="chat?.latest_message?.seen">
                            <svg
                                width="16"
                                height="8"
                                viewBox="0 0 16 8"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M9.77093 0.231252C9.70478 0.157976 9.62608 0.0998154 9.53938 0.0601249C9.45267 0.0204345 9.35966 0 9.26573 0C9.1718 0 9.07879 0.0204345 8.99208 0.0601249C8.90538 0.0998154 8.82668 0.157976 8.76053 0.231252L3.45949 6.06341L1.23235 3.60859C1.16367 3.5357 1.08259 3.47838 0.993752 3.43991C0.904911 3.40145 0.810045 3.38258 0.714569 3.3844C0.619093 3.38621 0.524877 3.40867 0.437301 3.45049C0.349725 3.49231 0.270503 3.55267 0.204159 3.62813C0.137815 3.70359 0.085649 3.79267 0.050638 3.89028C0.015627 3.98789 -0.00154303 4.09212 0.000108809 4.19703C0.00176064 4.30193 0.0222019 4.40544 0.0602651 4.50166C0.0983283 4.59789 0.153268 4.68493 0.221948 4.75782L2.95429 7.7599C3.02044 7.83317 3.09914 7.89134 3.18585 7.93103C3.27256 7.97072 3.36556 7.99115 3.45949 7.99115C3.55343 7.99115 3.64643 7.97072 3.73314 7.93103C3.81985 7.89134 3.89855 7.83317 3.96469 7.7599L9.77093 1.38049C9.84315 1.30728 9.9008 1.21842 9.94022 1.11953C9.97965 1.02063 10 0.913836 10 0.805869C10 0.697902 9.97965 0.591105 9.94022 0.492209C9.9008 0.393313 9.84315 0.304461 9.77093 0.231252Z"
                                    fill="#41D37E"
                                />
                                <path
                                    d="M15.7709 0.231252C15.7048 0.157976 15.6261 0.0998154 15.5394 0.0601249C15.4527 0.0204345 15.3597 0 15.2657 0C15.1718 0 15.0788 0.0204345 14.9921 0.0601249C14.9054 0.0998154 14.8267 0.157976 14.7605 0.231252L9.45949 6.06341L7.23235 3.60859C7.16367 3.5357 7.08259 3.47838 6.99375 3.43991C6.90491 3.40145 6.81004 3.38258 6.71457 3.3844C6.61909 3.38621 6.52488 3.40867 6.4373 3.45049C6.34972 3.49231 6.2705 3.55267 6.20416 3.62813C6.13782 3.70359 6.08565 3.79267 6.05064 3.89028C6.01563 3.98789 5.99846 4.09212 6.00011 4.19703C6.00176 4.30193 6.0222 4.40544 6.06027 4.50166C6.09833 4.59789 6.15327 4.68493 6.22195 4.75782L8.95429 7.7599C9.02044 7.83317 9.09914 7.89134 9.18585 7.93103C9.27256 7.97072 9.36556 7.99115 9.45949 7.99115C9.55343 7.99115 9.64643 7.97072 9.73314 7.93103C9.81985 7.89134 9.89855 7.83317 9.96469 7.7599L15.7709 1.38049C15.8432 1.30728 15.9008 1.21842 15.9402 1.11953C15.9796 1.02063 16 0.913836 16 0.805869C16 0.697902 15.9796 0.591105 15.9402 0.492209C15.9008 0.393313 15.8432 0.304461 15.7709 0.231252Z"
                                    fill="#41D37E"
                                />
                            </svg>
                        </div>
                        <div
                            v-else-if="chat?.unread_messages > 0"
                            class="flex h-4 w-4 justify-center rounded-full bg-[#FF6370] text-xs font-bold text-white"
                        >
                            {{ chat.unread_messages }}
                        </div>
                    </div>
                </div>
            </div>
            <div
                v-else
                class="flex w-10/12 items-center justify-center px-4 py-10"
            >
                <EmptyView
                    class="!font-normal"
                    title="
There's no chat history at the moment. Your conversations will appear here as soon as you begin chatting."
                    :show-status-code="false"
                />
            </div>
        </div>
    </div>
</template>
<script setup>
import moment from "moment/moment";
import EmptyView from "@/Views/EmptyView.vue";
import { computed, ref, watch } from "vue";
import { ChatBubbleBottomCenterIcon } from "@heroicons/vue/24/outline";
import TextInput from "@/Components/TextInput.vue";
import { debounce } from "lodash";
import ContactSearch from "@/Views/Chat/Messages/ContactSearch.vue";
import useMessageStore from "@/Store/chat";
import { StarIcon } from "@heroicons/vue/20/solid";

const messageStore = useMessageStore();

const emit = defineEmits(["toggleConversation"]);
const contacts = computed(() => messageStore.contacts);
const favorites = computed(() => messageStore.favorites);
const searchKey = ref();
const showLoading = ref(false);

async function searchContacts(query) {
    showLoading.value = true;
    await messageStore.searchContacts(query);
    showLoading.value = false;
}

const search = debounce(() => {
    searchContacts(searchKey.value);
}, 300);

watch([searchKey], () => {
    search();
});

const toggleConversation = (chatId) => {
    emit("toggleConversation", chatId);
    searchKey.value = null;
};

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
