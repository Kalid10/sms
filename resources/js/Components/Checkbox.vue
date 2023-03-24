<template>
    <input
        :checked="modelValue"
        class="h-3.5 w-3.5 rounded-sm border border-neutral-300 bg-gray-100 text-black ring-offset-2 focus:ring-black"
        type="checkbox" @input="input"
    >
</template>

<script setup>
defineProps({
    modelValue: {
        type: Boolean,
        required: true
    },
    label:{
        type:String,
        default:null
    }
})

const emits = defineEmits(['update:modelValue'])

function input(input) {
    emits('update:modelValue', input.target.checked)
}

</script>

<style scoped>

</style>
