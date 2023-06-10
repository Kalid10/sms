<template>
    <div
        :class="
            isFullScreen
                ? 'fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50'
                : ''
        "
    >
        <div
            class="animate-spin rounded-full border-4 border-solid border-t-transparent"
            :class="{
                'h-8 w-8 sm:h-12 sm:w-12': size === 'large',
                'h-6 w-6 sm:h-8 sm:w-8': size === 'small',
                'border-gray-100': color === 'primary',
                'border-black': color === 'secondary',
                'border-emerald-400': color === 'success',
                'border-negative-100': color === 'danger',
                'border-orange-500': color === 'warning',
                'border-sky-400': color === 'info',
                'border-neutral-200': color === 'neutral',
            }"
        ></div>
    </div>
</template>

<script setup>
import { ref } from "vue";

const props = defineProps({
    isFullScreen: {
        type: Boolean,
        default: false,
    },
    color: {
        type: String,
        default: "primary",
    },
    size: {
        type: String,
        default: "small",
    },
});

const isFullScreen = ref(props.isFullScreen);
const color = ref(props.color);
const size = ref(props.size);
</script>
