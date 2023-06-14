<template>
    <div class="h-fit w-full">
        <Schedule schedule-url="/admin/?school_schedule_date=" />
    </div>
</template>
<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import Schedule from "@/Views/SchoolSchedule/Index.vue";

const schoolSchedule = computed(() => usePage().props.school_schedule);
</script>
<style scoped></style>
