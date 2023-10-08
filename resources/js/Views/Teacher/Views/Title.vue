<template>
    <div class="flex h-fit flex-col items-start justify-evenly space-y-1 py-2">
        <span class="text-xl font-semibold lg:text-4xl"> {{ title }}</span>
        <span v-if="isDateOpen" class="text-xs font-light">{{
            moment().format(" dddd MMMM D YYYY")
        }}</span>
        <span v-if="subTitle" class="text-xs font-medium">{{ subTitle }}</span>
    </div>
</template>

<script setup>
import moment from "moment";

defineProps({
    title: {
        type: String,
        required: true,
    },
    isDateOpen: {
        type: Boolean,
        default: true,
    },
    subTitle: {
        type: String,
        default: null,
    },
});
</script>

<style scoped></style>
