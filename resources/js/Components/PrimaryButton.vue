<template>

    <button :type="type" class="rounded-md bg-black py-1.5 px-4 text-sm text-white" @click="click">
        {{ title }}
    </button>

</template>

<script setup>
defineProps({
    title: {
        type: String,
        required: true
    },
    type: {
        type: String,
        default: "button"
    },
    click: {
        type: Function,
        required: true
    }
})
</script>

<style scoped>

</style>
