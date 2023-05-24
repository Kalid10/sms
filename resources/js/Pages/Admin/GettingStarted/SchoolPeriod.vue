<template>
    <div class="grid h-full max-h-full w-full grid-cols-12 border-t">
        <SchoolPeriodFormWrapper class="pl-6" />

        <SchoolPeriodScheduleViewer />
    </div>
</template>

<script setup>
import SchoolPeriodFormWrapper from "@/Views/Admin/GettingStarted/Schedule/SchoolPeriodFormWrapper.vue";
import SchoolPeriodScheduleViewer from "@/Views/Admin/GettingStarted/Schedule/SchoolPeriodScheduleViewer.vue";
</script>
