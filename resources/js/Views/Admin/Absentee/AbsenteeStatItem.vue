<template>
    <div
        class="flex w-full flex-col items-center justify-center space-y-1 text-center"
    >
        <div class="w-full text-2xl font-semibold">{{ value }}</div>
        <div class="w-full text-[0.65rem] font-light">{{ title }}</div>
    </div>
</template>

<script setup>
defineProps({
    value: {
        type: String,
        default: "",
    },
    title: {
        type: String,
        default: "",
    },
});
</script>
<style scoped></style>
