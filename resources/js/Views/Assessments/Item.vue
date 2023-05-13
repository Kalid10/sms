<template>
    <div
        v-for="(item, index) in assessments"
        :key="index"
        class="mt-1 flex items-center justify-evenly py-1.5 lg:mt-2 lg:py-2"
    >
        <div
            class="hidden flex-col items-center justify-center space-y-6 text-center lg:flex lg:w-2/12"
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

        <div class="flex w-9/12 flex-col space-y-5 lg:w-8/12 lg:text-start">
            <div class="flex w-full flex-col justify-between space-x-4">
                <div class="text-xs font-medium lg:text-sm">
                    {{ item.title }}
                </div>
            </div>
            <div
                class="flex flex-col space-y-0.5 text-[0.65rem] font-light lg:flex-row lg:space-x-1.5 lg:space-y-0 lg:text-start lg:text-[0.7rem] 2xl:text-xs"
            >
                <span class="flex space-x-1">
                    <span>
                        {{ item.batch_subject.subject.full_name }}
                    </span>
                    <span class="font-medium">
                        {{ item.assessment_type.name }}
                    </span>
                </span>
                <span>
                    On
                    {{ moment(item.due_date).format("dddd MMMM Do") }}

                    <span class="font-semibold lg:hidden"
                        ><span class="font-light">for </span
                        >{{ item.batch_subject.batch.level.name
                        }}{{ item.batch_subject.batch.section }}</span
                    >
                </span>
            </div>
        </div>

        <div class="flex w-2/12 flex-col items-center space-y-2 lg:items-end">
            <div class="flex flex-col font-light uppercase lg:flex-row">
                <div class="mr-2 text-2xl font-bold lg:text-2xl">
                    {{ item.maximum_point }}
                </div>
                <div
                    class="flex flex-col space-y-0.5 text-[0.6rem] font-light lg:font-medium"
                >
                    <div>MAX</div>
                    <div>POINTS</div>
                </div>
            </div>
            <div
                class="hidden text-xs text-neutral-600 underline-offset-1 hover:cursor-pointer hover:text-black hover:underline lg:inline-block lg:text-[0.65rem]"
            >
                LessonPlan #{{ item.id }}
            </div>
        </div>
    </div>
</template>

<script setup>
import moment from "moment/moment";
import {
    BookOpenIcon,
    ClipboardDocumentCheckIcon,
    DocumentChartBarIcon,
    DocumentTextIcon,
    HomeIcon,
    PencilIcon,
} from "@heroicons/vue/24/solid";

defineProps({
    assessments: {
        type: Object,
        required: true,
    },
});

function getIconAndColor(name) {
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
}
</script>
<style scoped></style>
