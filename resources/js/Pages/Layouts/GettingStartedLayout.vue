<template>

    <div id="top-view"></div>

    <div class="relative flex h-screen w-full flex-col">
        <Header />
        <div
            class="grow overflow-y-auto p-2 md:p-6 lg:w-full">
            <div
                class="container mx-auto flex max-w-6xl flex-col gap-4">
                <slot/>
            </div>
        </div>
    </div>

</template>

<script setup>
import Header from "@/Views/Header.vue";
</script>

<style scoped>

</style>
