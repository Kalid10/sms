<template>
    <div class="flex h-full flex-col items-start justify-evenly rounded-lg">
        <span class="text-xl font-semibold lg:text-4xl"> {{ title }}</span>
        <span class="text-xs font-light">{{
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
});
</script>

<style scoped></style>
