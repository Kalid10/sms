<template>
    <div class="flex h-full w-fit items-center justify-evenly py-0.5">
        <span class="w-6/12 px-1 text-3xl font-bold">{{
            item.maximum_point
        }}</span>
        <span class="w-6/12 pr-1 pl-0.5 text-xs">PTS</span>
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
