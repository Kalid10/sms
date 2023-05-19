<template>
    <div
        class="hidden w-full flex-col items-center justify-center space-y-6 text-center lg:flex"
    >
        <component
            :is="getIconAndColor(item.assessment_type.name).icon"
            :class="getIconAndColor(item.assessment_type.name).color"
            class="w-4 2xl:w-5"
        />
        <div class="text-[0.65rem] font-light uppercase">
            {{ item.batch_subject.batch.level.name }}
            {{ item.batch_subject.batch.section }}
        </div>
    </div>
</template>

<script setup>
import { defineProps } from "vue";
import {
    BookOpenIcon,
    ClipboardDocumentCheckIcon,
    DocumentChartBarIcon,
    DocumentTextIcon,
    HomeIcon,
    PencilIcon,
} from "@heroicons/vue/24/solid";

defineProps({
    item: {
        type: Object,
        required: true,
    },
});

const getIconAndColor = (name) => {
    switch (name) {
        case "Tests":
            return { icon: DocumentTextIcon, color: "fill-orange-500" };
        case "Homework":
            return { icon: ClipboardDocumentCheckIcon, color: "fill-red-500" };
        case "Classwork":
            return { icon: PencilIcon, color: "fill-cyan-600" };
        case "Final Quarterly Exam":
            return { icon: DocumentChartBarIcon, color: "fill-yellow-500" };
        case "Final Exam":
            return { icon: BookOpenIcon, color: "fill-teal-600" };
        default:
            return { icon: HomeIcon, color: "fill-emerald-600" };
    }
};
</script>
