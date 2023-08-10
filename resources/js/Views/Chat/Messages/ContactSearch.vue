<template>
    <div class="flex h-full w-full flex-col items-center overflow-y-auto py-10">
        <Loading v-if="isLoading" color="secondary" class="absolute" />
        <div v-if="searchRecords?.data?.length < 1">
            <EmptyView :title="$t('rigelChat.noRecordFound')" />
        </div>

        <div
            v-else-if="!isLoading"
            class="flex w-full flex-col items-center justify-center divide-y"
        >
            <div
                v-for="(chat, index) in searchRecords"
                :key="index"
                class="flex h-16 w-11/12 cursor-pointer items-center justify-between p-1 px-4 hover:rounded-lg hover:bg-gradient-to-br hover:from-violet-500 hover:to-purple-500 hover:text-white"
                :class="index % 2 === 1 ? 'bg-violet-50/50' : 'bg-white'"
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
                <div
                    class="flex h-full w-10/12 flex-col justify-center space-y-1 text-sm font-medium"
                >
                    <span>
                        {{ chat.name }}
                    </span>
                    <div class="flex w-full space-x-1 text-xs">
                        <span class="font-light capitalize">
                            {{ chat.type }} {{ $t("common.for") }}
                        </span>
                        <span v-if="chat.type === 'guardian'">
                            <span
                                v-for="(item, index) in chat.guardian.children"
                                :key="index"
                            >
                                {{ item.user.name }}({{
                                    item.guardian_relation
                                }})
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import EmptyView from "@/Views/EmptyView.vue";
import Loading from "@/Components/Loading.vue";
import useMessageStore from "@/Store/chat";

defineEmits("toggle");

const messageStore = useMessageStore();
const searchRecords = computed(() => messageStore.records);
const isLoading = computed(() => messageStore.isLoading);
</script>
<style scoped></style>
