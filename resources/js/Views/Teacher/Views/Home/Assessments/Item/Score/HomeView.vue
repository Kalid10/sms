<template>
    <div
        class="flex h-full w-full flex-col items-center justify-center text-center text-2xl font-bold uppercase lg:flex-row lg:text-3xl"
    >
        {{ item.maximum_point }}
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
