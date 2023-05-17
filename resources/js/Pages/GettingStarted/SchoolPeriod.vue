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

<!--<template>-->
<!--    <div class="flex h-screen w-full items-center justify-center">-->
<!--        <SchoolPeriodModal />-->

<!--        <div class="flex w-10/12 justify-evenly">-->
<!--            <div-->
<!--                class="flex w-4/12 items-center justify-center rounded-md bg-gray-50/20 py-7 shadow-sm"-->
<!--            >-->
<!--                <div class="w-10/12">-->
<!--                    <PeriodForm v-model:form="schoolPeriodForm" />-->
<!--                </div>-->
<!--            </div>-->

<!--            <div v-if="schoolPeriodForm" class="w-7/12">-->
<!--                <ViewPeriod :school-periods="schoolPeriodForm" />-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</template>-->
<!--<script setup>-->
<!--import SchoolPeriodModal from "@/Views/GettingStarted/Schedule/SchoolPeriodModal.vue";-->
<!--import PeriodForm from "@/Views/GettingStarted/SchoolPeriod/PeriodForm.vue";-->
<!--import { ref } from "vue";-->
<!--import ViewPeriod from "@/Views/GettingStarted/SchoolPeriod/ViewPeriod.vue";-->

<!--const schoolPeriodForm = ref(null);-->

<!--console.log(schoolPeriodForm.value);-->
<!--</script>-->
<!--<style scoped></style>-->
