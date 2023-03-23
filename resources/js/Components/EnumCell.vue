<template>

    <div class="flex items-center justify-center">
        <p :class="color" class="scale-[.85] rounded-xl px-2 py-0.5 font-semibold">
            {{ value }}
        </p>
    </div>

</template>

<script setup>
import {computed} from "vue";

const props = defineProps({
    options: {
        type: Array, // Array of enum values
        required: true
    },
    value: {
        type: String,
        required: true
    }
})

const color = computed(() => {
    return colors[props.options.indexOf(props.value)]
})

const colors = [
    'blue',
    'purple',
    'black',
    'white',
    'red',

    'green',
    'yellow',

    'gray'
];

</script>

<style scoped>

.green {
    @apply bg-green-200 text-green-800;
}

.yellow {
    @apply bg-yellow-200 text-yellow-800;
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

.blue {
    @apply bg-blue-200 text-blue-800;
}

.purple {
    @apply bg-purple-200 text-purple-800;
}

.gray {
    @apply bg-gray-200 text-gray-800;
}

</style>
