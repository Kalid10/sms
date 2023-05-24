<template>
    <ClassSchedule :school-schedules="schoolSchedule"/>

</template>
<script setup>
import ClassSchedule from "@/Views/Admin/Schedule/ClassSchedule.vue";
import {computed} from "vue";
import {usePage} from "@inertiajs/vue3";

const schoolSchedule = computed(() => usePage().props.school_schedule)
</script>
