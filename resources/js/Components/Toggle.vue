<template>
    <div>
        <label class="relative inline-flex cursor-pointer items-center">
            <input
                :checked="modelValue"
                type="checkbox"
                class="peer sr-only"
                @input="input"
            >
            <div class="peer h-6 w-11 rounded-full bg-gray-200 after:absolute after:top-[2px] after:left-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-blue-600 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:peer-focus:ring-blue-800"></div>
        </label>
    </div>
</template>
<script setup>
defineProps({
    modelValue: {
        type: Boolean,
        default: false,
    }
})


const emits = defineEmits(['update:modelValue'])

function input(input) {
    emits('update:modelValue', input.target.checked)
}
</script>
