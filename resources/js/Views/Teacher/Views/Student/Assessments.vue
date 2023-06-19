<template>
    <div v-if="isAssessmentFound" class="flex w-full justify-between pr-2">
        <div class="text-xl font-medium">Recent Assessments</div>

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
        <EmptyView title="No Assessments Found!" />
    </div>
</template>
<script setup>
import Item from "@/Views/Teacher/Views/Home/Assessments/Item/Index.vue";
import LinkCell from "@/Components/LinkCell.vue";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import EmptyView from "@/Views/EmptyView.vue";

const assessments = computed(() => usePage().props.assessments);
const isAssessmentFound = computed(() => assessments.value.data.length > 0);
</script>
<style scoped></style>
