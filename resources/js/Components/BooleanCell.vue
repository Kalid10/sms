<template>

    <div class="flex items-center justify-center">
        <CheckCircleIcon v-if="value" class="h-5 w-5 stroke-2 text-green-600"/>
        <XCircleIcon v-else class="h-5 w-5 stroke-2 text-red-600"/>
    </div>

</template>

<script setup>
import {CheckCircleIcon, XCircleIcon} from "@heroicons/vue/24/outline";

defineProps({
    value: {
        type: Boolean,
        required: true
    }
})
</script>

<style scoped>

</style>
