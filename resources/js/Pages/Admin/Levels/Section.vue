<template>
    <div
        class="flex h-full w-full flex-col justify-between divide-x divide-gray-100 py-4 lg:flex-row lg:space-x-5 lg:px-2"
    >
        <div class="flex h-fit w-full flex-col space-y-5 lg:w-5/12">
            <div class="text-2xl font-semibold uppercase text-black">
                {{ $t("section.section") }} {{ batch.section }}
                <span v-if="batch?.home_room_teacher" class="font-light">
                    ( {{ batch.home_room_teacher.teacher.user.name }} )
                </span>
            </div>
            <div class="flex w-full justify-between space-x-5">
                <div class="h-32 w-6/12">
                    <ActiveSessions :batch="batch" />
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
                    :value="levelAssessments.data.length + ' Assessments'"
                    :icon="ClipboardIcon"
                    :url="assessmentUrl"
                />

                <SummaryItem
                    v-if="isTeacher()"
                    class-style="bg-brand-100 text-black"
                    icon-style="bg-brand-300/20 text-white"
                    :title="$t('common.students')"
                    value="75 Total Students"
                    :icon="UsersIcon"
                />
                <SummaryItem
                    class-style="bg-red-50 text-black"
                    icon-style="bg-red-500/20 text-white"
                    :title="$t('common.announcements')"
                    :value="
                        announcements
                            ? announcements.length + ' Announcements'
                            : 0 + ' Announcements'
                    "
                    :icon="ChatBubbleBottomCenterIcon"
                    @click="showModal = true"
                />
            </div>
        </div>
        <div
            class="flex h-full w-full flex-col justify-evenly lg:w-8/12 lg:px-8"
        >
            <BatchPerformance :grade="batch?.grade" />
            <div
                class="flex w-full flex-col items-center justify-between divide-x divide-gray-200 rounded-lg bg-brand-50 shadow-sm lg:flex-row"
            >
                <div class="w-full lg:w-6/12">
                    <StudentsList
                        progress-type="up"
                        :title="$t('section.topStudents')"
                        :icon="ArrowTrendingUpIcon"
                        :students="batch?.top_students ?? null"
                        :show-link="false"
                    />
                </div>

                <div class="w-full lg:w-6/12">
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

    <Modal v-model:view="showModal">
        <div class="rounded bg-white p-4">
            <Heading value="Student-Related Announcements" size="2xl" />

            <div
                v-if="announcements.length > 0"
                :class="'h-fit w-full space-y-2 py-4 rounded-lg bg-white px-2 shadow-sm'"
            >
                <Item
                    v-for="(item, index) in announcements"
                    :key="index"
                    :announcement="item"
                    :class="index % 2 === 0 ? 'bg-brand-50/50' : ''"
                />
            </div>
            <div v-else class="h-full rounded-lg bg-white py-10 shadow-sm">
                <EmptyView
                    :title="$t('announcementsIndex.noAnnouncementsFound')"
                    class="flex w-full justify-center py-2"
                />
            </div>
        </div>
    </Modal>
</template>
<script setup>
import { computed, ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import ActiveSessions from "@/Views/Admin/Levels/Section/ActiveSession.vue";
import AbsentStudents from "@/Views/Admin/Absentee/AbsenteeCard.vue";
import BatchPerformance from "@/Views/Teacher/Views/Batches/BatchPerformance/Index.vue";
import {
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
import Modal from "@/Components/Modal.vue";
import EmptyView from "@/Views/EmptyView.vue";
import Item from "@/Views/Announcements/Item.vue";
import Heading from "@/Components/Heading.vue";

const props = defineProps({
    batch: {
        type: Object,
        required: true,
    },
});

const showModal = ref(false);

const level = computed(() => {
    return usePage().props.level;
});

const announcements = computed(() => {
    return usePage().props.announcements;
});

const levelAssessments = computed(() => {
    return usePage().props.level_assessments;
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

const assessmentUrl = computed(() => {
    if (isTeacher()) {
        return "/teacher/assessments";
    }
    return "/levels/assessments/" + props.batch.id;
});
</script>
<style scoped></style>
