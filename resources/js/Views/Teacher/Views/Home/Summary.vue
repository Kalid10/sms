<template>
    <div class="flex w-5/12 flex-col justify-evenly space-y-4 pl-10">
        <SummaryItem
            class-style="bg-orange-100 text-black"
            icon-style="bg-orange-500/20 text-white"
            :title="'Assessments'"
            value="10 /10 Completed"
            :icon="ClipboardIcon"
            :url="
                isTeacher()
                    ? '/teacher/assessments'
                    : '/admin/teachers/assessments?teacher_id=' + teacher.id
            "
        />
        <SummaryItem
            class-style="bg-zinc-100 text-black"
            icon-style="bg-zinc-500/20 text-white"
            :title="'Students'"
            value="75 Total Students"
            :icon="UsersIcon"
            :url="
                isTeacher()
                    ? '/teacher/students'
                    : '/admin/teachers/students?teacher_id=' + teacher.id
            "
        />
        <SummaryItem
            class-style="bg-fuchsia-100 text-black"
            icon-style="bg-fuchsia-500/20 text-white"
            :title="'LessonPlans'"
            value="10 /10 Completed"
            :icon="CalendarIcon"
            :url="
                isTeacher()
                    ? '/teacher/lesson-plan'
                    : '/admin/teachers/lesson-plan?teacher_id=' + teacher.id
            "
        />
        <SummaryItem
            class-style="bg-green-100 text-black"
            icon-style="bg-green-500/20 text-white"
            :title="'Homeroom Classes'"
            value="10 /10 Completed"
            :icon="CalendarIcon"
            :url="
                isTeacher()
                    ? '/teacher/homeroom'
                    : '/admin/teachers/homeroom?teacher_id=' + teacher.id
            "
        />
    </div>
</template>
<script setup>
import {
    CalendarIcon,
    ClipboardIcon,
    UsersIcon,
} from "@heroicons/vue/24/solid";
import { isTeacher } from "@/utils";
import SummaryItem from "@/Views/Teacher/Views/SummaryItem.vue";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const teacher = computed(() => usePage().props.teacher);
</script>

<style scoped></style>
