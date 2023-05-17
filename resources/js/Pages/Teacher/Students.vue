<template>
    <div
        class="flex min-h-screen w-full flex-col space-y-4 bg-stone-100/70 p-5 2xl:px-5"
    >
        <!--        Header-->
        <div
            class="flex h-fit flex-col items-start justify-between space-y-1 rounded-lg 2xl:space-y-1.5"
        >
            <span class="text-xl font-semibold lg:text-4xl"> My Students</span>
            <span class="text-xs font-light">{{
                moment().format(" dddd MMMM D YYYY")
            }}</span>
        </div>

        <!--        Performance-->
        <Performance />
    </div>
</template>

<script setup>
import moment from "moment/moment";
import Performance from "@/Views/Teacher/Students/PerformanceHighlights/Index.vue";
</script>

<style scoped></style>
