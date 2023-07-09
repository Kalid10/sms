<template>
    <div
        class="flex h-fit flex-col items-center justify-center space-y-4 text-center font-medium"
    >
        <div v-if="showStatusCode" class="text-xl font-light">404</div>
        <div v-else>
            <ExclamationTriangleIcon class="w-5 text-red-600" />
        </div>
        <div class="text-sm">{{ title }}</div>
        <div class="text-sm font-light">{{ subTitle }}</div>
        <LinkCell
            v-if="linkTitle && linkUrl"
            :href="linkUrl"
            :value="linkTitle"
        />
    </div>
</template>
<script setup>
import LinkCell from "@/Components/LinkCell.vue";
import { ExclamationTriangleIcon } from "@heroicons/vue/24/outline";

defineProps({
    title: {
        type: String,
        required: true,
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
});
</script>

<style scoped></style>
