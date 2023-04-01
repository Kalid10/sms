<template>

    {{ value }}

</template>

<script setup>
defineProps({
    value: {
        type: [String, Number],
        required: true
    }
})
</script>

<style scoped>

</style>
