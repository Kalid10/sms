<template>
    <div
        class="group flex w-full cursor-pointer justify-between p-3.5 hover:rounded-lg hover:bg-brand-400 hover:text-brand-text-500"
    >
        <div
            class="flex w-full flex-col space-y-2 border-gray-100 pl-3 text-sm font-medium"
        >
            <div>{{ announcement.title }}</div>

            <div
                class="flex w-full justify-between text-[0.62rem] font-light text-black group-hover:text-brand-text-500"
            >
                <div>
                    <div>
                        <span v-if="isAdmin()">
                            {{ $t("announcementsItem.postTargets") }}
                            <span
                                v-for="(
                                    target, index
                                ) in announcement.target_group"
                                :key="index"
                                class="p-1 text-[0.6rem] font-medium uppercase"
                            >
                                {{ target }}
                            </span>
                        </span>
                        {{ $t("announcementsItem.andExpires") }}
                        <span>
                            {{
                                moment(announcement.expires_on).fromNow()
                            }}</span
                        >
                    </div>
                </div>
                <div>
                    <div>
                        {{ announcement.author.user.name }}
                        ( {{ moment(announcement.created_at).fromNow() }} )
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import moment from "moment";
import { isAdmin } from "@/utils";

defineProps({
    announcement: {
        type: Object,
        required: true,
    },
});
</script>
<style scoped></style>
