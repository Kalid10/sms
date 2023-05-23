<template>
    <Header :is-date-open="false" :title="'Mark Students'" class="w-fit" />
    <div class="flex h-fit w-fit items-center space-x-1 text-xs font-light">
        <span>{{ grade }} </span>
        <span>{{ assessment.batch_subject.subject.full_name }}</span>
        <span> {{ assessment.title }}</span>
    </div>
</template>
<script setup>
import Header from "@/Views/Teacher/Header.vue";
import { usePage } from "@inertiajs/vue3";

const assessment = usePage().props.assessment;
const grade =
    assessment.batch_subject.batch.level.name +
    assessment.batch_subject.batch.section;
</script>

<style scoped></style>
