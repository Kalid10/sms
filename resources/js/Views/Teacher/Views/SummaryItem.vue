<template>
    <div
        class="flex w-full items-center justify-between rounded-2xl py-4 px-5"
        :class="classStyle"
    >
        <div class="flex h-full flex-col justify-center space-y-3">
            <div class="text-sm font-medium uppercase">{{ title }}</div>
            <div class="text-center text-xs font-light">
                {{ value }}
            </div>
        </div>

        <div class="rounded-xl p-2" :class="iconStyle">
            <component :is="icon" class="w-5"></component>
        </div>
    </div>
</template>
<script setup>
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
});
</script>
<style scoped></style>
