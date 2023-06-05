<template>
    <div class="h-full w-full overflow-hidden">
        <MonthView/>
        <ClassSchedule :school-schedules="schoolSchedule"/>
    </div>

</template>
<script setup>
import ClassSchedule from "@/Views/Admin/Schedule/ClassSchedule.vue";
import {computed} from "vue";
import {usePage} from "@inertiajs/vue3";
import MonthView from "@/Views/Admin/Calendar/MonthView.vue";

const schoolSchedule = computed(() => usePage().props.school_schedule)
</script>
