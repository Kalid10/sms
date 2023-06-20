<template>
    <div
        class="flex h-fit flex-col items-center justify-center space-y-4 text-center"
    >
        <div class="text-xl font-light">404</div>
        <div class="text-sm font-medium">{{ title }}</div>
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
});
</script>

<style scoped></style>
