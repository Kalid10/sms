<template>
    <div id="top-view"></div>

    <div
        v-if="
            activePage === '/getting-started/school-schedule' ||
            activePage === '/login' ||
            activePage === '/signup'
        "
        class="relative flex h-screen w-full flex-col"
    >
        <slot />
    </div>
    <div v-else class="relative flex h-screen w-full flex-col">
        <Header />
        <div class="grow overflow-y-auto p-2 md:p-6 lg:w-full">
            <div
                :class="maxWidth"
                class="container mx-auto flex flex-col gap-4"
            >
                <slot />
            </div>
        </div>
    </div>

    <Notification />
</template>

<script setup>
import Header from "@/Views/Header.vue";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import Notification from "@/Components/Notification.vue";

const activePage = computed(() => usePage().url);

const maxWidth = computed(() => {
    switch (activePage.value) {
        case "/getting-started/assign-subjects":
            return "max-w-7xl";
        default:
            return "max-w-6xl";
    }
});
</script>

<style scoped></style>
