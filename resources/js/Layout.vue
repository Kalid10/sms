<template>

    <div id="top-view"></div>

    <div class="relative flex min-h-screen w-full flex-col">
        <Header @open-drawer="drawerVisible = true"/>
        <Drawer v-model:visible="drawerVisible"/>
        <div class="flex grow flex-col gap-4 overflow-y-auto bg-neutral-50 p-2">
            <slot/>
        </div>
    </div>

</template>

<script setup>
import {ref} from "vue";
import Header from "@/Views/Header.vue";
import Drawer from "@/Views/Drawer.vue";

const drawerVisible = ref(false);
</script>

<style scoped>

</style>
