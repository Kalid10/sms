<template>

    <div id="top-view"></div>

    <div class="relative flex h-screen w-full flex-col">
        <Header @open-drawer="drawerVisible = true"/>
        <div
            class="w-full grow overflow-y-auto bg-white p-2 md:p-6">
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
