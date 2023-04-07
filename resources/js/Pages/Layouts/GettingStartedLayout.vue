<template>

    <div id="top-view"></div>

    <div class="relative flex h-screen w-full flex-col">
        <Header />
        <div
            class="grow overflow-y-auto p-2 md:p-6 lg:w-full">
            <div
                :class="[ isAssignSubjectsPage ? 'max-w-7xl' : 'max-w-6xl' ]"
                class="container mx-auto flex flex-col gap-4"
            >
                <slot/>
            </div>
        </div>
    </div>

</template>

<script setup>
import Header from "@/Views/Header.vue";
import { computed } from "vue";
import {usePage} from "@inertiajs/vue3";

const isAssignSubjectsPage = computed(() => usePage().url === '/getting-started/assign-subjects');

</script>

<style scoped>

</style>
