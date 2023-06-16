<template>
    <div class="flex flex-col space-y-1">
        <Header :is-date-open="false" :title="'Mark Students'" class="w-fit" />
        <div class="flex h-fit w-fit items-center space-x-1 text-xs font-light">
            <span>{{ grade }} </span>
            <span>{{ assessment.batch_subject.subject.full_name }}</span> -
            <span class="bg-yellow-400 px-1 py-0.5 font-semibold text-black">
                {{ assessment.assessment_type.name }}</span
            >
        </div>
    </div>
</template>
<script setup>
import Header from "@/Views/Teacher/Views/Title.vue";
import { usePage } from "@inertiajs/vue3";

const assessment = usePage().props.assessment;
const grade =
    assessment.batch_subject.batch.level.name +
    assessment.batch_subject.batch.section;
</script>

<style scoped></style>
