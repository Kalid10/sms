<template>
    <input
        :checked="modelValue"
        class="h-3.5 w-3.5 rounded-sm"
        type="checkbox" @input="input"
    >
</template>

<script setup>
defineProps({
    modelValue: {
        type: Boolean,
        required: true
    }
})

const emits = defineEmits(['update:modelValue'])

function input(input) {
    emits('update:modelValue', input.target.checked)
}

</script>

<style scoped>

</style>
