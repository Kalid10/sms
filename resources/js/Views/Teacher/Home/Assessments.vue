<template>
    <div class="h-fit w-full rounded-lg">
        <!--        Header-->
        <div v-if="assessments" class="flex w-full justify-between">
            <div class="font-medium lg:text-xl 2xl:text-2xl">{{ title }}</div>
            <LinkCell
                class="flex w-fit items-center justify-center"
                href="/teacher/assessments"
                value="SEE ALL"
            />
        </div>

        <!--        Content-->
        <div class="flex w-full flex-col">
            <div
                v-if="assessments"
                class="mt-1 flex w-full flex-col justify-center divide-y-2 lg:mt-2 lg:py-2"
            >
                <Item :assessments="assessments" :view="view" />
            </div>
            <div
                v-else
                class="flex h-32 flex-col items-center justify-center space-y-4 lg:h-72"
            >
                <ExclamationTriangleIcon class="h-6 w-6 text-gray-500" />
                <div class="text-xs lg:text-sm">No Assessments Found!</div>
                <LinkCell
                    href="/teacher/assessments"
                    value="Go To Assessments"
                />
            </div>
        </div>
    </div>
</template>
<script setup>
import { usePage } from "@inertiajs/vue3";
import { ExclamationTriangleIcon } from "@heroicons/vue/24/outline";
import Item from "@/Views/Teacher/Home/Assessments/Item.vue";
import LinkCell from "@/Components/LinkCell.vue";
import { computed } from "vue";

const props = defineProps({
    assessments: {
        type: Array,
        default: null,
    },
    title: {
        type: String,
        default: "Recent Assessments",
    },
    view: {
        type: String,
        default: "teacher",
    },
});
const assessments = computed(() => {
    return props.assessments ?? usePage().props.teacher;
});
</script>
<style scoped></style>
