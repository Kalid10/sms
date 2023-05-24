<template>
    <div id="top-view"></div>

    <div
        v-if="
            activePage === '/getting-started/school-schedule' ||
            activePage === '/getting-started/class-schedule' ||
            activePage === '/login' ||
            activePage === '/signup'
        "
        class="relative flex h-screen w-full flex-col"
    >
        <slot />
    </div>
    <div v-else class="relative flex h-screen max-h-screen w-full flex-col">
        <Header />
        <div class="grow overflow-y-auto lg:w-full">
            <slot />
        </div>
    </div>

    <Notification />
</template>

<script setup>
import Header from "@/Views/Admin/Header.vue";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import Notification from "@/Components/Notification.vue";

const activePage = computed(() => usePage().url);
</script>

<style scoped></style>
