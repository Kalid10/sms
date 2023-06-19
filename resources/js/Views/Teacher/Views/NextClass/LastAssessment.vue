<template>
    <div
        v-if="lastAssessment"
        class="flex h-fit w-full flex-col items-center justify-between space-y-4 pt-3 lg:w-5/12"
        :class="{
            'lg:w-full  lg:space-y-4 lg:pt-3': isSidebarOpenOnXlDevice,
            'lg:w-5/12  lg:space-y-6 lg:pt-0': !isSidebarOpenOnXlDevice,
        }"
    >
        <div
            class="w-full text-center text-xs font-light opacity-70"
            :class="fontSize"
        >
            Last Assessment
        </div>
        <div class="font-medium">
            <span
                class="font-bold lg:text-4xl 2xl:text-5xl"
                :class="fontSizeLarge"
                >{{ lastAssessment.maximum_point }}</span
            >
            Pts
        </div>
        <div
            class="flex flex-col items-center justify-center space-y-1"
            :class="fontSize"
        >
            <div class="font-medium">
                {{ lastAssessment.assessment_type.name }}
            </div>
            <div class="">
                {{ lastAssessment.title }}
            </div>
        </div>
        <div
            v-if="!isSidebarOpenOnXlDevice"
            class="text-xs font-light"
            :class="fontSizeSmall"
        >
            Due On
            {{ moment(lastAssessment.due_date).format(" dddd MMMM D  YYYY") }}
        </div>
        <PrimaryButton
            class="w-8/12 !border-none bg-zinc-700 !text-xs lg:w-10/12 2xl:w-11/12"
            :class="buttonWidth"
            @click="router.visit('/teacher/assessments')"
            >View All Assessments
        </PrimaryButton>
    </div>
    <div
        v-else
        class="flex h-64 w-full flex-col items-center justify-center space-y-5 text-center font-light lg:w-5/12"
    >
        <div class="h-fit text-sm">
            No previous assessments found for this class. The most recent
            assessment will be displayed here.
        </div>
        <LinkCell value="View Assessments" href="/teacher/assessments" />
    </div>
</template>

<script setup>
import moment from "moment/moment";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { router, usePage } from "@inertiajs/vue3";
import { isSidebarOpenOnXlDevice } from "@/utils";
import { computed } from "vue";
import LinkCell from "@/Components/LinkCell.vue";

const lastAssessment = usePage().props.last_assessment;

// Computed properties for conditional styling
const fontSize = computed(() =>
    isSidebarOpenOnXlDevice.value ? "lg:text-xs" : "lg:text-sm"
);
const fontSizeLarge = computed(() =>
    isSidebarOpenOnXlDevice.value ? "lg:text-3xl" : "lg:text-4xl"
);
const fontSizeSmall = computed(() =>
    isSidebarOpenOnXlDevice.value ? "lg:text-[0.65rem]" : "lg:text-xs"
);
const buttonWidth = computed(() =>
    isSidebarOpenOnXlDevice.value ? "lg:w-8/12" : "lg:w-10/12"
);
</script>

<style scoped></style>
