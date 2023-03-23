<template>

    {{ value }}

</template>

<script setup>
defineProps({
    value: {
        type: String,
        required: true
    }
})
</script>

<style scoped>

</style>
