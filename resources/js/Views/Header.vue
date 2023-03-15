<template>

    <header class="relative z-10 flex min-h-10 w-full items-center bg-white px-2 shadow-sm">
        <Bars3Icon class="h-6 w-6 stroke-2 text-neutral-700" @click="$emit('openDrawer')"/>
    </header>

</template>

<script setup>
import {Bars3Icon} from "@heroicons/vue/24/outline"

const emits = defineEmits(['openDrawer'])
</script>

<style scoped>

</style>
