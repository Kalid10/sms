<template>
    <button
        :type="type"
        class="rounded-md py-1.5 px-4 text-sm text-black"
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
