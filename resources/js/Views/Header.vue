<template>

    <header class="relative z-10 flex min-h-10 w-full items-center bg-white px-2 shadow-sm">
        <div class="flex flex-col gap-0.5 rounded-md lg:hidden" @click="$emit('openDrawer')">
            <div class="h-0.5 w-5 bg-black"/>
            <div class="h-0.5 w-4 bg-black"/>
            <div class="h-0.5 w-3 bg-black"/>
        </div>
    </header>

</template>

<script setup>
const emits = defineEmits(['openDrawer'])
</script>

<style scoped>

</style>
