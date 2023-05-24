<template>
    <div class="flex w-full flex-col items-center">
        <div
            class="flex w-full flex-row justify-end space-x-0.5 text-center font-light uppercase lg:text-center"
        >
            <div class="flex items-center text-3xl font-bold">
                <span v-if="!item.point" class="text-base font-light">NM</span>
                <span>{{ item.point }}</span>
            </div>
            <div class="text-4xl">/</div>
            <div class="flex items-end text-2xl font-medium lg:text-2xl">
                {{ item.maximum_point }}
            </div>
        </div>
        <span
            class="-mt-1 w-full text-end text-[0.5rem] font-light text-gray-500"
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
