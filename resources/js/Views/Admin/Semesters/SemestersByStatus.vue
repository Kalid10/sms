<template>
    <div class="container mx-auto flex w-full flex-col items-center gap-3">
        <Heading>{{ status }} {{ $t('semesterByStatus.semesters')}}</Heading>
        <SemestersList
            :semesters="
                semesters
                    .filter((semester) => semester.status === status)
                    .slice()
                    .reverse()
            "
        />
    </div>
</template>

<script setup>
import { semesters } from "@/fake";
import Heading from "@/Components/Heading.vue";
import SemestersList from "@/Views/Admin/Semesters/SemestersList.vue";

defineProps({
    status: {
        type: String,
        required: true,
    },
});
</script>

<style scoped></style>
