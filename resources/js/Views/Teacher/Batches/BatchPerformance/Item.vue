<template>
    <div class="flex w-full items-center justify-center space-x-0.5">
        <div class="mt-1.5 h-full">
            <component
                :is="icon"
                v-if="value"
                class="w-3.5"
                :class="valueStyle"
            />
        </div>
        <div class="flex flex-col items-center justify-center">
            <div class="text-sm font-semibold">
                <span v-if="value">{{ value }}</span>
                <span v-else>-</span>
            </div>

            <div class="text-[0.6rem] font-light uppercase text-neutral-600">
                {{ title }}
            </div>
        </div>
    </div>
</template>
<script setup>
defineProps({
    value: {
        type: String,
        required: true,
    },
    title: {
        type: String,
        required: true,
    },
    icon: {
        type: String,
        default: null,
    },
    valueStyle: {
        type: String,
        default: "",
    },
});
</script>
<style scoped></style>
