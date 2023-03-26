<template>

    <fieldset class="flex w-full flex-col">

        <label
            v-for="(option, index) in options" :key="index" :for="option.id"
            class="flex items-start justify-start gap-3 border-x border-t p-3 first:rounded-t-md last:rounded-b-md last:border-b"
            :class="{ 'border-b border-black bg-black/5 [&+label]:border-t-0' : modelValue === option.value }"
        >
                <span class="mt-0.5 flex items-baseline gap-2">
                  <input
                    :id="option.id" :checked="option.value === modelValue" :value="option.value" :name="name" type="radio"
                    class="h-3 w-3 border-neutral-300 bg-gray-100 pt-1.5 text-black checked:outline-0 focus:border-0 focus:outline-0 focus:ring-0" @change="changeSelection"
                  />
                </span>
            <span class="flex flex-col gap-0.5">
                    <span :class="{ '' : modelValue === option.value }" class="text-xs font-semibold">{{ option.label }}</span>
                    <span :class="{ '' : modelValue === option.value }" class="text-xs text-gray-500">{{ option.description }}</span>
                </span>
        </label>

    </fieldset>

</template>

<script setup>
defineProps({
    modelValue: {
        type: [String, Number],
        required: true,
    },
    name: {
        type: String,
        required: true,
    },
    options: {
        type: Array, // Array of object type { id: int, value: string, label: string, description: string }
        required: true
    }
})

const emits = defineEmits(['update:modelValue'])

function changeSelection(event) {
    emits('update:modelValue', event.target.value)
}
</script>

<style scoped>

</style>
