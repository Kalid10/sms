<template>
    <div
        class="flex h-28 w-full flex-col items-center justify-center space-y-3 rounded-lg bg-green-300 text-center text-sm shadow-sm"
    >
        <div class="text-center text-5xl font-bold">{{ percentage }} %</div>

        <div class="font-light">{{ $t("attendanceCard.YourAttendance") }}</div>
    </div>
</template>

<script setup>
const props = defineProps({
    percentage: {
        type: Number,
        required: true,
    },
});
</script>

<style scoped></style>
