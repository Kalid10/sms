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

        <span class="w-full text-sm font-light leading-7 text-black"
            >{{ truncatedText }}
        </span>
        <span
            class="flex w-full cursor-pointer space-x-2 text-sm font-medium text-brand-text-150 hover:font-semibold hover:underline hover:underline-offset-2"
            @click="$emit('continue-reading', selectedAnnouncement)"
        >
            {{ $t("selectedAnnouncement.continueReading") }}
            <ArrowSmallRightIcon class="w-5" />
        </span>
        <div class="flex w-full items-center space-x-2 text-sm font-medium">
            <div>
                <img
                    :src="
                        auth?.user?.profile_image ??
                        'https://avatars.dicebear.com/api/open-peeps/' +
                            auth.user.name +
                            '.svg'
                    "
                    alt="avatar"
                    class="mx-auto h-6 w-6 rounded-full object-contain"
                />
            </div>

            <div>
                {{ selectedAnnouncement?.author.user.name }}
                <span
                    v-if="selectedAnnouncement?.author?.user?.admin?.position"
                >
                    , {{ selectedAnnouncement?.author?.user?.admin?.position }}
                </span>
            </div>
        </div>
    </div>
</template>
<script setup>
import moment from "moment";
import { ArrowSmallRightIcon } from "@heroicons/vue/20/solid";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

defineEmits(["continue-reading"]);
const props = defineProps({
    selectedAnnouncement: {
        type: Object,
        required: true,
    },
});

const auth = computed(() => usePage().props.auth);

const truncatedText = computed(() => {
    const maxLength = 420; // You can set your desired length
    return props.selectedAnnouncement?.body.length > maxLength
        ? props.selectedAnnouncement?.body.substring(0, maxLength) + "..."
        : props.selectedAnnouncement?.body;
});
</script>
<style scoped></style>
