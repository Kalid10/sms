<template>

    <div class="container mx-auto flex w-full flex-col items-center gap-3">
        <Heading>{{ seasons[season] }} Semesters</Heading>
        <SemestersList
            :semesters="semesters.filter(semester => semester.name.toLowerCase().includes(season.toLowerCase())).slice().reverse()"
        />
    </div>

</template>

<script setup>
import Heading from "@/Components/Heading.vue";
import SemestersList from "@/Views/Semesters/SemestersList.vue";
import {useSemesterStore} from "@/Store/semesters";

defineProps({
    season: {
        type: String,
        required: true
    }
})

const semesters = useSemesterStore().semesters


const seasons = {
    "1": "First",
    "2": "Second",
    "3": "Third"
}
</script>

<style scoped>

</style>
