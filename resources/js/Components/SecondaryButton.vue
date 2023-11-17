<template>
    <button
        :type="type"
        class="rounded-md px-4 py-1.5 text-sm text-black"
        :class="isDisabled ? 'opacity-70' : 'opacity-100'"
        :disabled="isDisabled"
        @click="click"
    >
        {{ title }}
    </button>
</template>

<script setup>
defineProps({
    title: {
        type: String,
        required: true,
    },
    type: {
        type: String,
        default: "button",
    },
    isDisabled: {
        type: Boolean,
        default: false,
    },
});

const emits = defineEmits(["click"]);

function click(e) {
    e.preventDefault();
    emits("click", e);
}
</script>

<style scoped></style>
