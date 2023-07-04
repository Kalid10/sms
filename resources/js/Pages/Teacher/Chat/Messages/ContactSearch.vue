<template>
    <div
        class="flex h-full w-full flex-col items-center overflow-y-auto px-4 py-10"
    >
        <Loading v-if="isLoading" color="secondary" class="absolute" />
        <div v-if="searchRecords?.data?.length < 1">
            <EmptyView title="No Record Found" />
        </div>

        <div v-else-if="!isLoading" class="flex w-full flex-col space-y-3">
            <div
                v-for="(chat, index) in searchRecords"
                :key="index"
                class="flex w-full cursor-pointer items-center justify-between p-1 hover:rounded-lg hover:bg-zinc-800 hover:text-white"
                @click="$emit('toggle', chat)"
            >
                <!-- profile pic -->
                <div class="relative w-2/12">
                    <img
                        :src="chat.avatar"
                        alt="avatar"
                        class="w-10 rounded-full object-contain"
                    />
                    <div
                        v-if="chat.active_status"
                        class="absolute right-[0px] bottom-[4px] h-3 w-3 rounded-full border-2 border-white bg-[#41D37E]"
                    ></div>
                </div>

                <!-- name and last message -->
                <div class="w-10/12">
                    <h1 class="text-sm font-medium">
                        {{ chat.name }}
                    </h1>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import EmptyView from "@/Views/EmptyView.vue";
import Loading from "@/Components/Loading.vue";
import useMessageStore from "@/Store/message";

defineEmits("toggle");

const messageStore = useMessageStore();
const searchRecords = computed(() => messageStore.records);
const isLoading = computed(() => messageStore.isLoading);
</script>
<style scoped></style>
