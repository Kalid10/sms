<template>
    <div
        class="flex flex-col items-center justify-between rounded-lg bg-white p-3 px-4 shadow-sm"
    >
        <span class="w-full text-xs font-thin">
            {{ moment().format("ddd MMM DD, YYYY") }}
        </span>

        <span class="w-full text-2xl font-semibold">
            {{ selectedAnnouncement?.title }}</span
        >

        <span class="text-sm leading-7 text-gray-500"
            >{{ selectedAnnouncement?.body }}
        </span>
        <span
            class="flex w-full cursor-pointer space-x-2 text-sm font-medium text-violet-600 hover:font-semibold hover:underline hover:underline-offset-2"
        >
            Continue reading
            <ArrowSmallRightIcon class="w-5" />
        </span>
        <div class="flex w-full items-center space-x-2 text-sm font-medium">
            <div>
                <img
                    :src="`https://xsgames.co/randomusers/avatar.php?g=male`"
                    alt="avatar"
                    class="mx-auto h-6 w-6 rounded-full object-contain"
                />
            </div>

            <div>
                {{ selectedAnnouncement?.author.user.name }} ,
                {{ selectedAnnouncement?.author?.user?.admin?.position }}
            </div>
        </div>
    </div>
</template>
<script setup>
import moment from "moment";
import { ArrowSmallRightIcon } from "@heroicons/vue/20/solid";

defineProps({
    selectedAnnouncement: {
        type: Object,
        required: true,
    },
});
</script>
<style scoped></style>
