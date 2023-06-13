<template>
    <div class="flex h-fit flex-col items-center justify-center space-y-4">
        <ExclamationTriangleIcon class="h-6 w-6 text-gray-500" />
        <div class="text-xs lg:text-sm">{{ title }}</div>
        <LinkCell
            v-if="linkTitle && linkUrl"
            :href="linkUrl"
            :value="linkTitle"
        />
    </div>
</template>
<script setup>
import { ExclamationTriangleIcon } from "@heroicons/vue/24/outline";
import LinkCell from "@/Components/LinkCell.vue";

defineProps({
    title: {
        type: String,
        required: true,
    },
    linkUrl: {
        type: String,
        default: null,
    },
    linkTitle: {
        type: String,
        default: null,
    },
});
</script>

<style scoped></style>
