<template>
    <label class="flex flex-col gap-1">
            <span v-if="label" class="">
                <span class="pl-0.5 text-sm font-semibold text-gray-500">{{ label }}</span>
                <span v-if="required" class="pl-0.5 text-xs text-red-600">*</span>
            </span>
        <input
            :disabled="disabled" :placeholder="placeholder" :required="required" :type="type"
            :value="modelValue"
            class="h-10 w-full rounded-md border border-gray-200 placeholder:text-sm placeholder:text-gray-500"
            @input="$emit('update:modelValue', $event.target.value)"/>
        <span v-if="error" class="text-lightText-200 text-[0.55rem] text-red-500">
            *{{ error }}
        </span>
    </label>
</template>

<script setup>
const props = defineProps({
    label: {
        type: String,
        default: null
    },
    required: {
        type: Boolean,
        default: false
    },
    disabled: {
        type: Boolean,
        default: false
    },
    placeholder: {
        type: String,
        required: true
    },
    modelValue: {
        type: [String, Number],
        required: true
    },
    error: {
        type: String,
        default: null,
    },
    type: {
        type: String,
        default: 'text'
    }
})

defineEmits(['update:modelValue'])
</script>

<style scoped>

</style>
