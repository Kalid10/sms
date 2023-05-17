<template>
    <div v-if="isAssessmentFound" class="flex w-full justify-between pr-2">
        <div class="font-medium 2xl:text-2xl">Recent Assessments</div>

        <LinkCell
            class="flex w-fit items-center justify-center"
            href="/teacher/assessments"
            value="SEE ALL"
        />
    </div>

    <Item
        v-if="isAssessmentFound"
        class="mt-3"
        :assessments="assessments"
        view="student"
    />
    <div
        v-else
        class="flex h-32 flex-col items-center justify-center space-y-4 lg:h-44"
    >
        <ExclamationTriangleIcon class="h-6 w-6 text-gray-500" />
        <div class="text-xs lg:text-sm">No Assessments Found!</div>
        <LinkCell href="/teacher/assessments" value="Go To Assessments" />
    </div>
</template>
<script setup>
import Item from "@/Views/Teacher/Home/Assessments/Item.vue";
import LinkCell from "@/Components/LinkCell.vue";
import { ExclamationTriangleIcon } from "@heroicons/vue/24/outline";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const assessments = computed(() => usePage().props.assessments);
const isAssessmentFound = computed(() => assessments.value.length > 0);
</script>
<style scoped></style>
