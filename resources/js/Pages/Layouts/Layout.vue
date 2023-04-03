<template>

    <div id="top-view"></div>

    <div class="relative flex h-screen w-full flex-col">
        <Header @open-drawer="drawerVisible = true"/>
        <Drawer v-model:visible="drawerVisible"/>
        <div
            class="grow overflow-y-auto bg-neutral-100 p-2 md:p-6 lg:ml-64 lg:w-[calc(100%-16rem)]">
            <div
                class="container mx-auto flex flex-col gap-12">
                <slot/>
            </div>
        </div>
        <Notification/>

    </div>

</template>

<script setup>
import {ref} from "vue";
import Header from "@/Views/Header.vue";
import Drawer from "@/Views/Drawer.vue";
import Notification from "@/Components/Notification.vue";

const drawerVisible = ref(false);
</script>

<style scoped>

</style>
