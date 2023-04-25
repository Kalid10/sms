<template>
    <h3
        :class="{
            'text-lg': size === 'lg',
            'text-base': size === 'md',
            'text-sm': size === 'sm',
            'text-xs': size === 'xs'
        }"
        class="font-semibold capitalize"
    >
        <slot>
            {{ value }}
        </slot>
    </h3>
</template>

<script setup>
defineProps({
    size: {
        type: String,
        default: 'md'
    },
    value: {
        type: String,
        default: null
    }
})
</script>

<style scoped>

</style>
