<template>
    <div class="flex h-full w-fit items-center justify-evenly py-0.5">
        <span class="w-6/12 px-1 text-3xl font-bold">{{
            item.maximum_point
        }}</span>
        <span
            class="mt-1.5 flex h-5 w-6/12 items-end justify-center px-1 text-[0.6rem] text-gray-500"
            >PTS</span
        >
    </div>
</template>

<script setup>
import { defineProps } from "vue";

defineProps({
    item: {
        type: Object,
        required: true,
    },
});
</script>
