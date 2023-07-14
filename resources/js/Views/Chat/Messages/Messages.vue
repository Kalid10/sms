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

            <div class="flex w-full items-center justify-evenly px-2">
                <TextInput
                    v-model="searchKey"
                    placeholder="Search Teacher, Admin..."
                    class="!w-9/12"
                    class-style="!w-full  rounded-2xl bg-zinc-50/80 border-none placeholder:text-xs focus:bg-white text  focus:ring-1 focus:ring-zinc-400"
                    @click.
                />

                <div
                    class="group flex w-fit cursor-pointer items-center space-x-1 rounded-lg bg-violet-600 px-2 py-1.5 text-center text-xs shadow-sm"
                    @click="loadSimilarContacts"
                >
                    <ChatBubbleOvalLeftEllipsisIcon
                        class="w-4 cursor-pointer text-white hover:scale-125"
                    />
                </div>
            </div>

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
        <ChatHistory v-else @load-chat="loadChat" />

        <SimilarContacts
            v-if="showSimilarUsers"
            @close="showSimilarUsers = false"
            @load-chat="loadChat"
        />
    </div>
</template>
<script setup>
import { computed, ref, watch } from "vue";
import {
    ChatBubbleBottomCenterIcon,
    ChatBubbleOvalLeftEllipsisIcon,
} from "@heroicons/vue/24/outline";
import TextInput from "@/Components/TextInput.vue";
import { debounce } from "lodash";
import ContactSearch from "@/Views/Chat/Messages/ContactSearch.vue";
import useMessageStore from "@/Store/chat";
import { router } from "@inertiajs/vue3";
import ChatHistory from "@/Views/Chat/Messages/ChatHistory.vue";
import SimilarContacts from "@/Views/Chat/Messages/SimilarContacts.vue";

const emit = defineEmits(["toggleConversation"]);
const messageStore = useMessageStore();

const favorites = computed(() => messageStore.favorites);
const contacts = computed(() => messageStore.contacts);
const searchKey = ref();
const showLoading = ref(false);
const showSimilarUsers = ref(false);

watch([contacts], () => {
    if (contacts.value.length < 1) {
        loadSimilarContacts();
    }
});

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

const loadChat = (chat) => {
    emit("toggleConversation", chat);
    searchKey.value = null;
};

const toggleConversation = (chatId) => {
    emit("toggleConversation", chatId);
    searchKey.value = null;
};

function loadSimilarContacts() {
    if (showSimilarUsers.value) {
        return;
    }
    router.get(
        "/chat",
        {},
        {
            preserveState: true,
            only: ["similar_users"],
            onSuccess: () => {
                showSimilarUsers.value = true;
            },
        }
    );
}
</script>
<style scoped></style>
