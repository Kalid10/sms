<template>
    <div class="flex w-full flex-col items-center space-y-2 bg-orange-500">
        <div
            class="flex w-full flex-row justify-between bg-gray-600 text-center font-light uppercase lg:text-center"
        >
            <div class="mr-0.5 flex items-center text-3xl font-bold">
                <span v-if="!item.point" class="text-base font-light">NM</span>
                <span>{{ item.point }}</span>
            </div>
            <div class="text-4xl">/</div>
            <div class="flex items-end text-2xl font-medium lg:text-2xl">
                {{ item.maximum_point }}
                <span class="pl-1 text-[0.7rem] font-light">PTS</span>
            </div>
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
