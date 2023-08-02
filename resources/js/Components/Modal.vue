<template>

    <Teleport v-if="view" to="#top-view">

        <div
            :class="{
                'bg-black/50 backdrop-blur': backgroundColor === 'black',
                'bg-black/20 backdrop-blur': backgroundColor === 'white',
                'bg-transparent': backgroundColor === 'transparent',
                'grid place-items-center': placeItemsCenter,
            }"
            class="fixed z-50 h-screen w-full p-4">

            <div
                ref="modal"
                class="container mx-auto max-w-3xl"
                :class="{ 'grid place-items-center': placeItemsCenter }"
            >

                <slot/>

            </div>

        </div>

    </Teleport>

</template>

<script setup>
import {ref} from "vue"
import {onClickOutside} from "@vueuse/core";

const props = defineProps({
    view: {
        type: Boolean,
        default: false
    },
    closeOnOutsideClick: {
        type: Boolean,
        default: true
    },
    backgroundColor: {
        type: String,
        default: 'black'
    },
    placeItemsCenter: {
        type: Boolean,
        default: false
    }
})

const emits = defineEmits(['update:view'])

const modal = ref(null)

onClickOutside(modal, () => {
    if (props.closeOnOutsideClick) {
        emits('update:view', false)
    }
})
</script>

<style scoped>

</style>
