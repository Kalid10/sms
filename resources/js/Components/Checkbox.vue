<template>
    <input
        :for="name"
        :checked="modelValue"
        class="h-3.5 w-3.5 rounded-full border border-neutral-300 bg-brand-100 text-brand-400 ring-offset-2 focus:ring-brand-400"
        type="checkbox"
        @input="input"
    />
</template>

<script setup>
defineProps({
    modelValue: {
        type: Boolean,
        required: true,
    },
    label: {
        type: String,
        default: null,
    },
    name: {
        type: String,
        default: null,
    },
});

const emits = defineEmits(["update:modelValue"]);

function input(input) {
    emits("update:modelValue", input.target.checked);
}
</script>

<style scoped></style>
