<template>
    <div class="h-fit w-full">
        <!--        <div class="text-center text-xl font-semibold lg:text-2xl">-->
        <!--            Today's Calendar-->
        <!--        </div>-->
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
