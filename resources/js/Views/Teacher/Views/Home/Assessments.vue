<template>
    <div class="h-fit w-full px-2 pt-2">
        <!--        Header-->
        <div v-if="assessments" class="flex w-full justify-between">
            <div
                :class="
                    view === 'class' ? 'pl-3 w-6/12' : 'font-medium lg:text-xl'
                "
            >
                {{ title }}
            </div>
            <LinkCell
                class="flex w-fit items-center justify-center"
                value="VIEW ALL"
                @click="fetchAssessments"
            />
        </div>

        <!--        Content-->
        <div class="flex w-full flex-col">
            <div
                v-if="assessments?.data.length"
                class="flex w-full flex-col justify-center divide-y pb-2"
            >
                <Item :assessments="assessments" :view="view" />
            </div>
            <div
                v-else
                class="flex h-32 flex-col items-center justify-center space-y-4 lg:h-72"
            >
                <ExclamationTriangleIcon class="h-6 w-6 text-gray-500" />
                <div class="text-xs lg:text-sm">No Assessments Found!</div>
                <LinkCell value="Go To Assessments" @click="fetchAssessments" />
            </div>
        </div>
    </div>
</template>
<script setup>
import { router, usePage } from "@inertiajs/vue3";
import { ExclamationTriangleIcon } from "@heroicons/vue/24/outline";
import Item from "@/Views/Teacher/Views/Home/Assessments/Item/Index.vue";
import LinkCell from "@/Components/LinkCell.vue";
import { computed } from "vue";
import { isAdmin } from "@/utils";

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
    return props.assessments ?? usePage().props.teacher.assessments;
});
const teacher = computed(() => {
    return usePage().props.teacher;
});

function fetchAssessments() {
    if (isAdmin()) {
        return router.get(
            "/admin/teachers/assessments",
            {
                teacher_id: teacher.value.id,
            },
            {
                preserveState: true,
            }
        );
    }

    return router.get(
        "/teacher/assessments",
        {},
        {
            preserveState: true,
        }
    );
}
</script>
<style scoped></style>
