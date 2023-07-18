<template>
    <Link :href="link">
        <ArrowLeftCircleIcon
            class="w-6 cursor-pointer text-brand-text-400 hover:text-black"
        />
    </Link>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import { defineProps } from "vue";
import { ArrowLeftCircleIcon } from "@heroicons/vue/20/solid";

defineProps({
    link: {
        type: String,
        required: true,
    },
});
</script>
