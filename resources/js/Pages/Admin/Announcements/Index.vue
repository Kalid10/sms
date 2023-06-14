<template>
    <div
        class="flex min-h-screen w-full justify-evenly divide-x divide-gray-100 bg-gray-50/50"
    >
        <div class="w-7/12">
            <Announcements />
        </div>
        <div class="flex w-4/12 flex-col items-center">Something Goes Here</div>
    </div>
</template>
<script setup>
import Announcements from "@/Views/Announcements/Index.vue";
</script>
<style scoped></style>
