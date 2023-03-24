<template>
    <button class="flex items-center gap-2 rounded-full border border-black py-1 px-2" @click="$emit('click')">
        <XCircleIcon class="h-4 w-4" />
        <span class="text-sm leading-none">{{ value }}</span>
    </button>
</template>

<script setup>
import { XCircleIcon } from "@heroicons/vue/20/solid";

defineProps({
    value: {
        type: String,
        required: true
    }
})

defineEmits(['click'])
</script>

<style scoped>

</style>
