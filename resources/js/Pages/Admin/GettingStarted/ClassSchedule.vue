<template>
    <div class="grid h-full max-h-full w-full grid-cols-12 border-t">
        <div
            class="relative col-span-3 flex h-full flex-col items-center justify-between overflow-auto border-r"
        >
            <BatchScheduler />
        </div>

        <SchoolPeriodScheduleViewer />
    </div>
</template>

<script setup>
import SchoolPeriodScheduleViewer from "@/Views/Admin/GettingStarted/Schedule/SchoolPeriodScheduleViewer.vue";
import BatchScheduler from "@/Views/Admin/GettingStarted/Schedule/BatchScheduler.vue";
</script>
