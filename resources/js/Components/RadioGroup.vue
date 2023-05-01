<template>

    <fieldset class="flex h-10 w-full items-center overflow-hidden rounded-md border text-sm xl:w-fit [&>*]:border-r [&>:last-child]:border-none">
        <label v-for="(option, index) in options" :key="index" :class="[ modelValue === option.value ? 'bg-black text-white' : '' ]" class="flex h-full grow cursor-pointer items-center justify-center xl:grow-0">
            <span class="h-fit grow px-4 text-center">{{ option.label }}</span>
            <input type="radio" :name="name" class="sr-only" :value="option.value" @change="changeSelection" />
        </label>
    </fieldset>

</template>

<script setup>
defineProps({
    modelValue: {
        type: [null, String, Number],
        default: null,
    },
    name: {
        type: String,
        required: true,
    },
    options: {
        type: Array, // Array of object type { value: string, label: string }
        required: true
    },
})

const emits = defineEmits(['update:modelValue'])

function changeSelection(event) {
    emits('update:modelValue', event.target.value)
}
</script>

<style scoped>

</style>
