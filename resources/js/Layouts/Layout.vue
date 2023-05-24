<template>
    <div id="top-view"></div>

    <div class="relative flex h-screen w-full flex-col">
        <Header @open-drawer="drawerVisible = true" />
        <div
            class="hide-scrollbar container mx-auto flex w-full grow flex-col gap-12 overflow-y-auto bg-white p-2"
        >
            <slot />
        </div>
        <Notification />
    </div>
</template>

<script setup>
import { ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import Header from "@/Views/Admin/Header.vue";
import Notification from "@/Components/Notification.vue";

const drawerVisible = ref(false);
const page = usePage();
</script>

<style scoped>
.hide-scrollbar::-webkit-scrollbar {
    display: none; /* for Chrome, Safari, and Opera */
}

.hide-scrollbar {
    -ms-overflow-style: none; /* for Internet Explorer and Edge */
    scrollbar-width: none; /* for Firefox */
}
</style>
