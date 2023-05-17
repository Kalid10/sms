<template>
    <div
        class="relative grid place-items-center"
        :style="{
      width: diameter + (strokeWidth ? strokeWidth / 2 : 0) + 'px',
      height: diameter + (strokeWidth ? strokeWidth / 2 : 0) + 'px',
    }"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            version="1.1"
            :width="diameter + (strokeWidth ? strokeWidth / 2 : 0)"
            :height="diameter + (strokeWidth ? strokeWidth / 2 : 0)"
            class="absolute top-0 left-0 -rotate-90"
        >
            <circle
                :style="{
          strokeDasharray: dasharray,
          strokeDashoffset: 0,
          strokeWidth: (strokeWidth ?? diameter * 0.15) + 'px',
        }"
                :class="[color, background ?? 'background']"
                class="fill-none transition-colors duration-150"
                :cx="(diameter + (strokeWidth ? strokeWidth / 2 : 0)) / 2"
                :cy="(diameter + (strokeWidth ? strokeWidth / 2 : 0)) / 2"
                :r="diameter * 0.4"
                stroke-linecap="round"
            />
        </svg>
        <svg
            v-if="percentage > 0"
            xmlns="http://www.w3.org/2000/svg"
            version="1.1"
            :width="diameter + (strokeWidth ? strokeWidth / 2 : 0)"
            :height="diameter + (strokeWidth ? strokeWidth / 2 : 0)"
            class="absolute top-0 left-0 -rotate-90"
        >
            <circle
                v-if="percentage > 0"
                :style="{
          strokeDasharray: dasharray,
          strokeDashoffset: anticlockwise ? 0 : dasharray,
          strokeWidth: (strokeWidth ?? diameter * 0.15) + 'px',
        }"
                :class="color"
                class="value fill-none transition-colors delay-500 duration-150"
                :cx="(diameter + (strokeWidth ? strokeWidth / 2 : 0)) / 2"
                :cy="(diameter + (strokeWidth ? strokeWidth / 2 : 0)) / 2"
                :r="diameter * 0.4"
                stroke-linecap="round"
            />
        </svg>
        <label class="opacity-0">
            <slot />
        </label>
    </div>
</template>
<script setup>
import { computed } from "vue";
const props = defineProps({
    percentage: {
        type: [Number, null],
        required: true,
        default: 24,
    },
    diameter: {
        type: Number,
        default: 40,
    },
    dasharray: {
        type: Number,
        required: true,
        default: 100,
    },
    strokeWidth: {
        type: [Number, null],
        default: null,
    },
    color: {
        type: String,
        default: "positive",
    },
    background: {
        type: String,
        default: null,
    },
    anticlockwise: {
        type: Boolean,
        default: false,
    },
});
const coverage = computed(
    () =>
        (props.dasharray - props.dasharray * (props.percentage / 100)) *
        (props.anticlockwise ? -1 : 1)
);
const coverageElapse = computed(() =>
    props.anticlockwise
        ? `${700 - (700 * props.percentage) / 100}ms`
        : `${(700 * props.percentage) / 100}ms`
);
</script>
<style scoped>
.positive.value {
    @apply stroke-positive-50;
}
.positive.background {
    @apply stroke-positive-50/25;
}
.negative.value {
    @apply stroke-negative-50;
}
.negative.background {
    @apply stroke-negative-50/25;
}
.default.value {
    @apply stroke-default-100;
}
.default.background {
    @apply stroke-default-100/25;
}
.positive\/transparent.value {
    @apply stroke-positive-50;
}
.positive\/transparent.background {
    @apply stroke-default-100/0;
}
.negative\/transparent.value {
    @apply stroke-negative-50;
}
.negative\/transparent.background {
    @apply stroke-default-100/0;
}
.default\/transparent.value {
    @apply stroke-default-100;
}
.default\/transparent.background {
    @apply stroke-default-100/0;
}
circle.value {
    animation: load v-bind(coverageElapse) ease-in forwards;
    animation-delay: 500ms;
}
label {
    animation: display v-bind(coverageElapse) ease-in forwards;
    animation-delay: 500ms;
}
@keyframes load {
    100% {
        stroke-dashoffset: v-bind(coverage);
    }
}
@keyframes display {
    100% {
        opacity: 100%;
    }
    0% {
        opacity: 0;
    }
}
</style>
