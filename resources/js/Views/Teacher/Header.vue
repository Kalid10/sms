<template>
    <div
        class="flex h-fit flex-col items-start justify-evenly space-y-1 rounded-lg py-2"
    >
        <span class="text-xl font-semibold lg:text-4xl"> {{ title }}</span>
        <span v-if="isDateOpen" class="text-xs font-light">{{
            moment().format(" dddd MMMM D YYYY")
        }}</span>
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
});
</script>

<style scoped></style>
