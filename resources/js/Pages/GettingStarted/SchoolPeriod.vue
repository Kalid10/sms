<template>

    <div class="grid h-full max-h-full w-full grid-cols-12 border-t">

        <SchoolPeriodFormWrapper class="pl-6" />

        <SchoolPeriodScheduleViewer />

    </div>

</template>

<script setup>
import SchoolPeriodFormWrapper from "@/Views/GettingStarted/Schedule/SchoolPeriodFormWrapper.vue";
import SchoolPeriodScheduleViewer from "@/Views/GettingStarted/Schedule/SchoolPeriodScheduleViewer.vue";
</script>
