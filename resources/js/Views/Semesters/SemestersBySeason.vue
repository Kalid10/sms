<template>

    <div class="container mx-auto flex w-full flex-col items-center gap-3">
        <Heading>{{ season }} Semesters</Heading>
        <SemestersList
            :semesters="semesters.filter(semester => semester.name.toLowerCase().includes(season.toLowerCase())).slice().reverse()"
        />
    </div>

</template>

<script setup>
import {semesters} from "@/fake";
import Heading from "@/Components/Heading.vue";
import SemestersList from "@/Views/Semesters/SemestersList.vue";

defineProps({
    season: {
        type: String,
        required: true
    }
})
</script>

<style scoped>

</style>
