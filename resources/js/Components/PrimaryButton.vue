<template>
    <button
        :disabled="disabled"
        :type="type"
        class="rounded-md border border-black bg-brand-400 py-1.5 px-3 text-xs text-white opacity-100 transition-opacity duration-150 disabled:opacity-50"
        @click="click"
    >
        <slot>
            {{ title }}
        </slot>
    </button>
</template>

<script setup>
defineProps({
    title: {
        type: String,
        default: null,
    },
    type: {
        type: String,
        default: "button",
    },
    disabled: {
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
