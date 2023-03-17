<template>

    <button :type="type" class="rounded-md border border-black bg-black py-1.5 px-4 text-sm text-white" @click="click">
        <slot>
            {{ title }}
        </slot>
    </button>

</template>

<script setup>
defineProps({
    title: {
        type: String,
        default: null
    },
    type: {
        type: String,
        default: "button"
    }
})

const emits = defineEmits(['click'])

function click(e) {
    e.preventDefault()
    emits('click', e)
}
</script>

<style scoped>

</style>
