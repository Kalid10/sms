<template>

    <div class="grid w-full grid-cols-1 gap-3 md:grid-cols-2 2xl:grid-cols-3">
        <SemesterCard v-for="(semester, index) in semesters" :key="index" :semester="semester" />
    </div>

</template>

<script setup>
import SemesterCard from "@/Views/Semesters/SemesterCard.vue";

defineProps({
    semesters: {
        type: Array,
        required: true
    }
})
</script>

<style scoped>

</style>
