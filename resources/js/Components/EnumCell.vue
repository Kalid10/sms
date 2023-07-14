<template>
    <div class="flex items-center justify-center">
        <p
            :class="color"
            class="scale-[.85] rounded-xl px-2 py-0.5 font-semibold"
        >
            {{ value }}
        </p>
    </div>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
    options: {
        type: Array, // Array of enum values
        required: true,
    },
    value: {
        type: String,
        required: true,
    },
});

const color = computed(() => {
    return colors[props.options.indexOf(props.value)];
});

const colors = [
    "blue",
    "purple",
    "black",
    "white",
    "red",
    "green",
    "yellow",
    "gray",
    "blue-darker",
    "purple-darker",
    "yellow-darker",
    "red-darker",
    "green-darker",
];
</script>

<style scoped>
.green {
    @apply bg-green-200 text-green-800;
}

.green-darker {
    @apply bg-green-400 text-green-900;
}

.yellow {
    @apply bg-yellow-200 text-yellow-800;
}

.yellow-darker {
    @apply bg-yellow-400 text-yellow-900;
}

.black {
    @apply bg-black text-white;
}

.white {
    @apply bg-white text-black border border-black;
}

.red {
    @apply bg-red-200 text-red-800;
}

.red-darker {
    @apply bg-red-400 text-red-900;
}

.blue {
    @apply bg-blue-200 text-blue-800;
}

.blue-darker {
    @apply bg-blue-400 text-blue-900;
}

.purple {
    @apply bg-purple-200 text-purple-800;
}

.purple-darker {
    @apply bg-purple-400 text-purple-900;
}

.gray {
    @apply bg-brand-150 text-brand-text-450;
}
</style>
