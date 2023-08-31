<template>
    <div v-if="isFullScreen" class="flex h-screen w-full justify-evenly">
        <div class="flex w-4/12 items-center justify-center">
            <QuestionMarkCircleIcon class="w-72 text-brand-450" />
        </div>

        <div
            class="flex w-5/12 flex-col items-center justify-center space-y-10"
        >
            <slot />
        </div>
    </div>
    <div
        v-else
        class="flex h-fit flex-col items-center justify-center space-y-6 text-center font-medium"
    >
        <div v-if="showStatusCode" class="text-xl font-light">404</div>
        <div v-else>
            <ExclamationTriangleIcon class="w-5 text-red-600" />
        </div>
        <div class="text-sm">{{ title }}</div>
        <div class="text-sm text-gray-700">{{ subTitle }}</div>
        <LinkCell
            v-if="linkTitle && linkUrl"
            :href="linkUrl"
            :value="linkTitle"
        />

        <slot />
    </div>
</template>
<script setup>
import LinkCell from "@/Components/LinkCell.vue";
import { ExclamationTriangleIcon } from "@heroicons/vue/24/outline";
import { QuestionMarkCircleIcon } from "@heroicons/vue/20/solid";

defineProps({
    title: {
        type: String,
        default: null,
    },
    subTitle: {
        type: String,
        default: null,
    },
    linkUrl: {
        type: String,
        default: null,
    },
    linkTitle: {
        type: String,
        default: null,
    },
    showStatusCode: {
        type: Boolean,
        default: false,
    },
    isFullScreen: {
        type: Boolean,
        default: false,
    },
});
</script>

<style scoped></style>
