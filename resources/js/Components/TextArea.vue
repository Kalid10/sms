<template>
    <label class="flex flex-col gap-1">
                <span class="">
                    <span class="pl-0.5 text-sm font-semibold text-gray-500"
                    >{{ label }}</span
                    >
                    <span class="pl-0.5 text-xs text-red-600">*</span>
                </span>
        <textarea
            :rows="rows"
            :placeholder="placeholder" :disabled="disabled" :required="required" :value="modelValue"
            class="w-full rounded-md border border-gray-200 text-sm placeholder:text-sm placeholder:text-gray-500"
            @input="$emit('update:modelValue', $event.target.value)"
        />
        <span v-if="error" class="text-xs text-negative-50">
            * {{ error }}
        </span>
    </label>
</template>
<script setup>

const props = defineProps({
    label: {
        type: String,
        required: true
    },
    required: {
        type: Boolean,
        default: false
    },
    placeholder: {
        type: String,
        required: true
    },
    modelValue: {
        type: [String, Number],
        default: null,
    },
    error: {
        type: String,
        default: null,
    },
    disabled: {
        type: Boolean,
        default: false
    },
    rows: {
        type: Number,
        default: 5
    }
})

defineEmits(['update:modelValue'])
</script>
