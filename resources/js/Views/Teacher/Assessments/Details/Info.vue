<template>
    <div class="flex w-full justify-between">
        <div class="flex h-20 w-6/12 flex-col items-center justify-evenly">
            <div class="text-4xl font-bold">{{ count }}</div>
            <div
                class="cursor-pointer text-xs font-light underline-offset-2 hover:font-medium hover:underline"
            >
                Total Students
            </div>
        </div>
        <div class="h-full w-[0.01rem] bg-gray-200"></div>
        <div class="flex w-6/12 flex-col items-center justify-evenly">
            <div
                class="w-fit rounded-xl bg-emerald-500 px-2 py-1 text-end text-xs text-white"
            >
                {{ status }}
            </div>
            <div
                class="mt-2 cursor-pointer text-xs font-light underline-offset-2 hover:font-semibold hover:underline"
            >
                Mr. {{ teacherName }}
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    count: {
        type: String,
        required: true,
    },
    teacherName: {
        type: String,
        required: true,
    },
    status: {
        type: String,
        default: "Marking",
    },
});
</script>

<style scoped></style>
