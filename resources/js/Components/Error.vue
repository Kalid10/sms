<template>
    <div
        v-if="error"
        class="flex items-center justify-center space-x-1 rounded-md bg-red-500 p-2 text-center text-xs font-medium text-white"
    >
        <ExclamationCircleIcon class="w-4" />
        <span> {{ error }} ! </span>
    </div>
</template>
<script setup>
import { ExclamationCircleIcon } from "@heroicons/vue/20/solid";

defineProps({
    error: {
        type: String,
        required: true,
    },
});
</script>

<style scoped></style>
