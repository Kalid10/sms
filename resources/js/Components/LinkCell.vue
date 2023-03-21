<template>

    <Link :href="href">
        {{ value }}
    </Link>

</template>

<script setup>
import {Link} from '@inertiajs/vue3'

defineProps({
    value: {
        type: String,
        required: true
    },
    href: {
        type: String,
        required: true
    }
})

</script>

<style scoped>

</style>
