<template>
    <div
        class="flex h-[4rem] w-3/12 items-center justify-evenly rounded-lg border border-white bg-black/90 px-0.5 text-white shadow-sm shadow-gray-100"
    >
        <div
            class="flex h-full w-4/12 items-center justify-center p-0.5 font-light"
        >
            <div
                class="flex items-center justify-center rounded-full bg-brand-50 p-1.5"
            >
                <component :is="icon" class="w-6 fill-black" />
            </div>
        </div>

        <div class="flex h-full w-8/12 flex-col justify-evenly py-2">
            <div
                class="text-[0.5rem] font-bold text-brand-text-200/90 2xl:text-[0.57rem]"
            >
                {{ title }}
            </div>
            <div class="text-2xl font-semibold 2xl:text-3xl">
                {{ value }}
            </div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    icon: {
        type: String,
        required: true,
    },
    title: {
        type: String,
        required: true,
    },
    value: {
        type: String,
        required: true,
    },
});
</script>
