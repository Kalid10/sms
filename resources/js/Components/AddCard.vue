<template>
    <div
        class="flex w-fit cursor-pointer flex-col items-center justify-center space-y-3 rounded-xl bg-brand-100 p-4 shadow-sm hover:scale-105"
    >
        <PlusCircleIcon class="w-12 text-brand-250" />
        <div class="text-xs font-medium uppercase text-brand-500">
            {{ title }}
        </div>
    </div>
</template>

<script setup>
import { PlusCircleIcon } from "@heroicons/vue/24/solid";

defineProps({
    title: {
        type: String,
        default: "",
    },
});
</script>
<style scoped></style>
