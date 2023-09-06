<template>
    <Teleport v-if="view" to="#top-view">
        <div
            :class="{
                'bg-black/50 backdrop-blur': backgroundColor === 'black',
                'bg-black/20 backdrop-blur': backgroundColor === 'white',
                'bg-transparent': backgroundColor === 'transparent',
                'grid place-items-center': placeItemsCenter,
            }"
            class="scrollbar-hide fixed z-50 h-screen w-full overflow-scroll p-4"
        >
            <div
                ref="modal"
                class="container mx-auto"
                :class="
                    ({ 'grid place-items-center': placeItemsCenter },
                    classStyle ? classStyle : 'max-w-3xl')
                "
            >
                <slot />
            </div>
        </div>
    </Teleport>
</template>

<script setup>
import { ref } from "vue";
import { onClickOutside } from "@vueuse/core";

const props = defineProps({
    view: {
        type: Boolean,
        default: false,
    },
    closeOnOutsideClick: {
        type: Boolean,
        default: true,
    },
    backgroundColor: {
        type: String,
        default: "black",
    },
    placeItemsCenter: {
        type: Boolean,
        default: false,
    },
    classStyle: {
        type: String,
        default: null,
    },
});

const emits = defineEmits(["update:view"]);

const modal = ref(null);

onClickOutside(modal, () => {
    if (props.closeOnOutsideClick) {
        emits("update:view", false);
    }
});
</script>

<style scoped></style>
