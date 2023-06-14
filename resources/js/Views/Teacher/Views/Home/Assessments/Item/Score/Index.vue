<template>
    <StudentView v-if="view === 'student'" :item="item" />
    <ClassView v-else-if="view === 'class'" :item="item" />
    <HomeView v-else :item="item" />
</template>

<script setup>
import { defineProps } from "vue";
import StudentView from "./StudentView.vue";
import HomeView from "./HomeView.vue";
import ClassView from "@/Views/Teacher/Views/Home/Assessments/Item/Score/ClassView.vue";

defineProps({
    item: {
        type: Object,
        required: true,
    },
    view: {
        type: String,
        required: true,
    },
});
</script>
