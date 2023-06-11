<template>
    <div
        class="col-span-12 h-fit text-start font-light lg:text-3xl 2xl:text-4xl"
        :class="isSidebarOpenOnXlDevice ? ' lg:col-span-8' : ' lg:col-span-7'"
    >
        Welcome back,

        <span class="ml-0.5 font-semibold lg:ml-2">{{ user.name }}</span>
    </div>
</template>
<script setup>
import { isSidebarOpenOnXlDevice } from "@/utils";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const user = computed(() => usePage().props.auth.user);
</script>
<style scoped></style>
