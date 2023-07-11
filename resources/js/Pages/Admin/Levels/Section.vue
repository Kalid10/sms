<template>
    <div
        class="flex h-full w-full justify-between space-x-5 divide-x divide-gray-100 py-4 px-2"
    >
        <div class="flex h-fit w-5/12 flex-col space-y-5">
            <div class="text-2xl font-semibold uppercase text-gray-700">
                {{ $t('section.section')}} {{ batch.section }}
                <span v-if="batch?.home_room_teacher" class="font-light">
                    ( {{ batch.home_room_teacher.teacher.user.name }} )
                </span>
            </div>
            <div class="flex w-full justify-between space-x-5">
                <div class="h-32 w-6/12">
                    <ActiveSession :batch="batch" />
                </div>
                <div class="h-32 w-6/12">
                    <AbsentStudents
                        :value="
                            batch.active_session[0]
                                ? batch.active_session[0].absentees.length
                                : 0
                        "
                        :batch="batch"
                        :title="$t('section.absentStudents')"
                        class-style="bg-red-600"
                    />
                </div>
            </div>
            <div class="flex h-full w-full flex-col space-y-6">
                <SummaryItem
                    class-style="bg-orange-100 text-black"
                    icon-style="bg-orange-500/20 text-white"
                    :title="$t('common.assessments')"
                    value="10 /10 Completed"
                    :icon="ClipboardIcon"
                    :url="
                        isTeacher()
                            ? '/teacher/assessments'
                            : '/admin/teachers/assessments?teacher_id='
                    "
                />
                <SummaryItem
                    class-style="bg-fuchsia-100 text-black"
                    icon-style="bg-fuchsia-500/20 text-white"
                    :title="$t('common.lessonPlan')"
                    value="10 /10 Completed"
                    :icon="CalendarIcon"
                    :url="
                        isTeacher()
                            ? '/teacher/lesson-plan'
                            : '/admin/teachers/lesson-plan?teacher_id='
                    "
                />
                <SummaryItem
                    class-style="bg-zinc-100 text-black"
                    icon-style="bg-zinc-500/20 text-white"
                    :title="$t('common.students')"
                    value="75 Total Students"
                    :icon="UsersIcon"
                    :url="
                        isTeacher()
                            ? '/teacher/students'
                            : '/admin/teachers/students?teacher_id='
                    "
                />
                <SummaryItem
                    class-style="bg-red-50 text-black"
                    icon-style="bg-red-500/20 text-white"
                    :title="$t('common.announcements')"
                    value="10 Announcements Today"
                    :icon="ChatBubbleBottomCenterIcon"
                    :url="
                        isTeacher()
                            ? '/teacher/announcements'
                            : '/admin/teachers/announcements?teacher_id='
                    "
                />
            </div>
        </div>
        <div class="flex h-full w-8/12 flex-col justify-evenly px-8">
            <BatchPerformance :grade="batch?.grade" />
            <div
                class="flex w-full items-center justify-between divide-x divide-gray-200 rounded-lg bg-gray-50 shadow-sm"
            >
                <div class="w-6/12">
                    <StudentsList
                        progress-type="up"
                        :title="$t('section.topStudents')"
                        :icon="ArrowTrendingUpIcon"
                        :students="batch?.top_students ?? null"
                        :show-link="false"
                    />
                </div>

                <div class="w-6/12">
                    <StudentsList
                        progress-type="down"
                        :title="$t('section.studentsFallingBehind')"
                        :icon="ArrowTrendingDownIcon"
                        :students="batch?.bottom_students ?? null"
                        :show-link="false"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import ActiveSession from "@/Views/Admin/Levels/Section/ActiveSession.vue";
import AbsentStudents from "@/Views/Admin/Absentee/AbsenteeCard.vue";
import BatchPerformance from "@/Views/Teacher/Views/Batches/BatchPerformance/Index.vue";
import {
    CalendarIcon,
    ChatBubbleBottomCenterIcon,
    ClipboardIcon,
    UsersIcon,
} from "@heroicons/vue/24/solid";
import { isTeacher } from "@/utils";
import SummaryItem from "@/Views/Teacher/Views/SummaryItem.vue";
import {
    ArrowTrendingDownIcon,
    ArrowTrendingUpIcon,
} from "@heroicons/vue/24/outline";
import StudentsList from "@/Views/Teacher/Views/Batches/PerformanceHighlights/StudentsList.vue";

const props = defineProps({
    batch: {
        type: Object,
        required: true,
    },
});

const level = computed(() => {
    return usePage().props.level;
});

const activeSession = computed(() => {
    if (props.batch.active_session) {
        return {
            session: props.batch.active_session.map((s) => {
                return {
                    id: s.id,
                    name: s.name,
                    status: s.status,
                    subject: s.batch_subject.subject.full_name,
                    teacher: s.teacher.user.name,
                    period: s.school_period.name,
                };
            }),
        };
    }
    return null;
});
</script>
<style scoped></style>
