<template>
    <div class="flex h-full w-full flex-col items-center justify-center p-4">
        <div class="mb-2 flex items-center justify-center space-x-1 text-3xl">
            <component
                :is="icon"
                class="w-5"
                :class="
                    type === 'positive'
                        ? 'text-positive-100'
                        : ' text-negative-100'
                "
            />
            <div
                class="text-4xl font-bold"
                :class="
                    type === 'positive'
                        ? 'text-green-600'
                        : ' text-negative-100'
                "
            >
                {{ value.toFixed(2) }}%
            </div>
        </div>
        <div class="text-[0.65rem] font-light">
            {{ title }}
        </div>
    </div>
</template>

<script setup>
defineProps({
    icon: {
        type: String,
        required: true,
    },
    type: {
        type: String,
        required: true,
    },
    title: {
        type: String,
        required: true,
    },
    value: {
        type: Number,
        required: true,
    },
});
</script>
