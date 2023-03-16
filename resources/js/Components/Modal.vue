<template>

    <Teleport v-if="view" to="#top-view">

        <div class="fixed z-50 h-screen w-full bg-black/50 p-4">

            <div ref="modal" class="container mx-auto max-w-3xl">

                <slot/>

            </div>

        </div>

    </Teleport>

</template>

<script setup>
import {ref} from "vue"
import {onClickOutside} from "@vueuse/core";

defineProps({
    view: {
        type: Boolean,
        required: true
    }
})

const emits = defineEmits(['update:view'])

const modal = ref(null)

onClickOutside(modal, () => {
    emits('update:view', false)
})
</script>

<style scoped>

</style>
