<template>
    <button :type="type" class="rounded-md py-1.5 px-4 text-sm text-black" @click="click">
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
