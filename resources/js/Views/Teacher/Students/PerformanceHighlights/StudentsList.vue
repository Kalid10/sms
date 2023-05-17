<template>
    <div class="flex h-fit w-full flex-col space-y-3 py-2">
        <div class="flex space-x-1.5 text-xl font-semibold">
            <component :is="icon" class="w-7" />
            <span>{{ title }}</span>
        </div>
        <div
            v-for="(item, index) in progressingStudents"
            :key="index"
            class="flex h-12 w-11/12 items-center space-x-2"
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
