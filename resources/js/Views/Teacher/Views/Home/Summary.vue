<template>
    <!--    TODO: Add value to locale -->
    <div class="flex w-5/12 flex-col items-center space-y-5">
        <SummaryItem
            v-if="isTeacher()"
            class-style="bg-sky-100 text-black"
            icon-style="bg-sky-500/20 text-white"
            :title="$t('common.aiChat')"
            :value="$t('common.aiChatDescription')"
            :icon="PaperAirplaneIcon"
            url="/teacher/copilot"
        />
        <SummaryItem
            class-style="bg-orange-100 text-black"
            icon-style="bg-orange-500/20 text-white"
            :title="$t('common.assessments')"
            :value="$t('common.addViewUpdateAssessments')"
            :icon="ClipboardIcon"
            :url="
                isTeacher()
                    ? '/teacher/assessments'
                    : '/admin/teachers/assessments?teacher_id=' + teacher.id
            "
        />
        <SummaryItem
            class-style="bg-brand-100 text-black"
            icon-style="bg-brand-300/20 text-white"
            :title="$t('common.students')"
            :value="$t('common.viewStudents')"
            :icon="UsersIcon"
            :url="
                isTeacher()
                    ? '/teacher/students'
                    : '/admin/teachers/students?teacher_id=' + teacher.id
            "
        />
        <SummaryItem
            class-style="bg-green-100 text-black"
            icon-style="bg-green-500/20 text-white"
            :title="$t('common.lessonPlans')"
            :value="$t('common.addViewUpdateLessonPlans')"
            :icon="CalendarIcon"
            :url="
                isTeacher()
                    ? '/teacher/lesson-plan'
                    : '/admin/teachers/lesson-plan?teacher_id=' + teacher.id
            "
        />
        <SummaryItem
            class-style="bg-fuchsia-100 text-black"
            icon-style="bg-fuchsia-500/20 text-white"
            :title="$t('common.homeroomClasses')"
            :value="$t('common.overseeHomeroomClasses')"
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
    PaperAirplaneIcon,
    UsersIcon,
} from "@heroicons/vue/24/solid";
import { isTeacher } from "@/utils";
import SummaryItem from "@/Views/Teacher/Views/SummaryItem.vue";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const teacher = computed(() => usePage().props.teacher);
</script>

<style scoped></style>
