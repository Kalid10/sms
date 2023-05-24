<script setup>
defineProps({
    title: {
        type: String,
        required: true,
    },
    formattedDate: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div>
        <div class="text-2xl font-medium">{{ title }}</div>
        <div class="pl-1 text-[0.6rem] font-light">{{ formattedDate }}</div>
    </div>
</template>

<style scoped></style>
