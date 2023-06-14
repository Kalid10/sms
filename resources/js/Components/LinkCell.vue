<template>
    <Link
        :href="href"
        class="cursor-pointer text-xs font-light underline decoration-neutral-500 underline-offset-2 hover:font-medium lg:text-xs 2xl:text-sm"
    >
        {{ value }}
    </Link>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    value: {
        type: String,
        required: true,
    },
    href: {
        type: String,
        default: null,
    },
});
</script>

<style scoped></style>
