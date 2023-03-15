<template>
    <div
        ref="drawer"
        :class="[ visible ? 'translate-x-0' : '-translate-x-full' ]"
        class="absolute left-0 z-0 h-full w-64 bg-white shadow-sm transition-transform duration-150">
    </div>
</template>

<script setup>

import {ref} from "vue"
import {onClickOutside} from "@vueuse/core"

const props = defineProps({
    visible: Boolean,
})

const emits = defineEmits(['update:visible'])

const drawer = ref(null)
onClickOutside(drawer, () => {
    emits('update:visible', false)
})

</script>

<style scoped>

</style>
