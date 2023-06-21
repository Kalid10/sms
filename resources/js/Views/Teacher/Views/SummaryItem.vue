<template>
    <div
        class="flex w-full cursor-pointer items-center justify-between rounded-2xl py-4 px-5 hover:scale-105"
        :class="classStyle"
        @click="url ? router.get(url, {}, { preserveState: true }) : null"
    >
        <div class="flex h-full flex-col justify-center space-y-3">
            <div class="text-sm font-medium capitalize">{{ title }}</div>
            <div class="w-fit text-center text-xs font-light">
                {{ value }}
            </div>
        </div>

        <div class="rounded-xl p-2" :class="iconStyle">
            <component :is="icon" class="w-5"></component>
        </div>
    </div>
</template>
<script setup>
import { router } from "@inertiajs/vue3";

defineProps({
    title: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        default: "",
    },
    value: {
        type: String,
        default: null,
    },
    icon: {
        type: String,
        default: null,
    },
    classStyle: {
        type: String,
        default: "bg-zinc-700",
    },
    iconStyle: {
        type: String,
        default: "",
    },
    url: {
        type: String,
        default: null,
    },
});
</script>
<style scoped></style>
