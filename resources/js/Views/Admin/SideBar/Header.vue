<template>
    <div
        v-if="header && isOpen"
        class="flex w-full flex-col items-center justify-center space-x-4 space-y-2 py-2 px-1 lg:flex-row lg:space-y-1"
    >
        <div class="h-full w-fit lg:w-3/12">
            <img
                :src="`https://xsgames.co/randomusers/avatar.php?g=${header.user.gender}`"
                alt="avatar"
                class="mx-auto h-16 w-full rounded-full object-cover md:w-11/12 lg:h-24 lg:rounded-lg"
            />
        </div>
        <div
            class="flex w-8/12 flex-col space-y-2 text-xs font-light lg:text-xs"
        >
            <Heading class="text-xs lg:text-base"
                >{{ header.user.name }}
            </Heading>
            <div class="flex w-full items-center space-x-2 lg:space-x-1.5">
                <EnvelopeIcon class="h-3.5 lg:h-5" />
                <span class="w-8/12 break-words">{{ header.user.email }}</span>
            </div>
            <div class="flex items-center space-x-2 lg:space-x-1.5">
                <PhoneIcon class="h-3.5 lg:h-5" />
                <span> 0943104396</span>
            </div>
        </div>
    </div>
</template>

<script setup>
import Heading from "@/Components/Heading.vue";
import { EnvelopeIcon, PhoneIcon } from "@heroicons/vue/20/solid";
import { useSidebarStore } from "@/Store/sidebar";
import { computed } from "vue";

const props = defineProps({
    header: {
        type: Object,
        required: true,
    },
});

const isOpen = computed(() => useSidebarStore().isOpen);
</script>
<style scoped></style>
