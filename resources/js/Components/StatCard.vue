<template>
    <div
        class="flex h-[4rem] w-2/12 items-center justify-evenly rounded-lg border border-white bg-neutral-200/40 px-0.5 shadow-sm shadow-gray-100"
    >
        <div
            class="flex h-full w-4/12 items-center justify-center p-0.5 font-light"
        >
            <div
                class="flex items-center justify-center rounded-full bg-black p-2"
            >
                <component :is="icon" class="w-4 fill-white" />
            </div>
        </div>

        <div class="flex h-full w-8/12 flex-col justify-evenly py-2">
            <div
                class="text-[0.5rem] font-bold text-gray-400/80 2xl:text-[0.57rem]"
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
