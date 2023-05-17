<template>
    <div class="flex h-fit w-full flex-col items-center space-y-3 py-2">
        <div class="flex w-full items-center justify-center space-x-1.5">
            <component
                :is="icon"
                class="w-5"
                :class="
                    progressType === 'up'
                        ? 'text-positive-100'
                        : 'text-negative-100'
                "
            />
            <span class="text-sm">{{ title }}</span>
        </div>
        <div
            v-for="(item, index) in progressingStudents"
            :key="index"
            class="flex h-12 w-full items-center justify-center space-x-2"
        >
            <Item
                :progress-type="progressType"
                :name="item.name"
                rank="3"
                :attendance="item.attendance"
                :progress="80"
                grade="56/60"
            />
        </div>
    </div>
</template>
<script setup>
import Item from "@/Views/Teacher/Students/PerformanceHighlights/Item.vue";

defineProps({
    progressingStudents: {
        type: Array,
        required: true,
    },
    icon: {
        type: String,
        required: true,
    },
    title: {
        type: String,
        required: true,
    },
    progressType: {
        type: String,
        required: true,
    },
});
</script>

<style scoped></style>
