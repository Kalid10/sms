<template>
    <div class="flex h-14 w-fit items-center justify-evenly py-2">
        <span class="min-h-full w-6/12 pr-2 text-3xl font-bold">{{
            item.maximum_point
        }}</span>
        <div
            class="flex w-6/12 items-end justify-center px-1 text-[0.65rem] text-gray-500"
        >
            {{ $t('common.pts')}}
        </div>
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
