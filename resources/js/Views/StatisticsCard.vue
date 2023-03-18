<template>

    <Card
        :subtitle="subtitle"
        :title="title" class="md:col-span-1 md:row-span-1 md:w-full"
        icon
    >
        <h3 class="flex flex-col">
            <slot>
                <span class="text-2xl font-semibold">
                    {{ data }}
                </span>
            </slot>
        </h3>
        <template #icon>
            <slot name="icon"/>
        </template>
    </Card>

</template>

<script setup>
import Card from "@/Components/Card.vue"

defineProps({
    title: {
        type: String,
        required: true,
    },
    subtitle: {
        type: String,
        required: true,
    },
    data: {
        type: String,
        default: null
    }
})
</script>

<style scoped>

</style>
