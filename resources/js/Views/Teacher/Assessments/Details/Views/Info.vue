<template>
    <div class="flex w-full justify-between">
        <div class="flex h-20 w-6/12 flex-col items-center justify-evenly">
            <div class="text-4xl font-bold">11</div>
            <div
                class="cursor-pointer text-xs font-light underline-offset-2 hover:font-medium hover:underline"
            >
                Total Students
            </div>
        </div>
        <div class="h-full w-[0.01rem] bg-gray-200"></div>
        <div class="flex w-6/12 flex-col items-center justify-evenly">
            <div
                class="flex w-fit justify-evenly space-x-0.5 rounded-xl px-2 py-1 text-end text-xs font-medium"
                :class="statusClass"
            >
                <component
                    :is="statusIcon"
                    v-if="statusIcon"
                    class="w-3.5 text-gray-100"
                />
                <div>{{ status }}</div>
            </div>
            <div
                class="mt-2 cursor-pointer text-xs font-light underline-offset-2 hover:font-semibold hover:underline"
            >
                Mr. {{ teacherName }}
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { CheckCircleIcon, PencilIcon } from "@heroicons/vue/24/solid";

const props = defineProps({
    count: {
        type: String,
        required: true,
    },
    teacherName: {
        type: String,
        required: true,
    },
    status: {
        type: String,
        default: "Marking",
    },
});

const statusClass = computed(() => {
    switch (props.status) {
        case "Marking":
            return "bg-emerald-500";
        case "Completed":
            return "bg-yellow-400";

        case "Published":
            return "bg-emerald-400 text-black";
        default:
            return "bg-gray-500";
    }
});

const statusIcon = computed(() => {
    switch (props.status) {
        case "Marking":
            return PencilIcon;
        case "Published":
            return CheckCircleIcon;
        default:
            return "";
    }
});
</script>

<style scoped></style>
