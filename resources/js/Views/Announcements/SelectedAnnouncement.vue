<template>
    <div
        class="flex flex-col items-center space-y-6 rounded-lg bg-gray-50/50 p-3"
    >
        <span class="w-full text-sm font-thin text-gray-800">
            {{ moment().format("ddd MMM DD, YYYY") }}
        </span>

        <span class="w-full text-3xl font-semibold">
            {{ selectedAnnouncement.title }}</span
        >

        <span class="leading-7 text-gray-600"
            >{{ selectedAnnouncement.body }}
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
                {{ selectedAnnouncement.author.user.name }} ,
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
