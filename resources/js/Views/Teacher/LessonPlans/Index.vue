<template>
    <div
        class="flex h-screen max-h-full w-full flex-col space-y-5 overflow-auto border-l border-zinc-500 bg-white py-5 px-3 text-white"
    >
        <div class="w-8/12">
            <Header
                title="My Lesson Plans"
                :select-input-options="months"
                :selected-input="selectedMonth"
                :show-current-class="false"
                @change="handleMonthChange"
            />
        </div>

        <div class="flex h-full max-h-full w-full grow flex-col">
            <ul class="flex gap-3 py-4">
                <li v-for="(subject, s) in subjects" :key="s">
                    <Link
                        :href="`/teacher/lesson-plan?batch_subject_id=${subject.id}&month=${selectedMonth}`"
                        :class="
                            lessonPlanSubject.batch.level.name ===
                                subject.batch.level.name &&
                            lessonPlanSubject.batch.section ===
                                subject.batch.section
                                ? ' text-zinc-800 bg-zinc-200/60  rounded-2xl'
                                : 'text-gray-800'
                        "
                        class="relative grid min-w-[4rem] place-items-center py-2 px-5 text-center text-sm font-medium"
                    >
                        <span class="flex items-end">
                            <span
                                >{{ parseLevel(subject.batch.level.name) }}
                                {{ subject.batch.section }}</span
                            >
                            &nbsp;
                            <span>{{ subject.subject.full_name }}</span>
                        </span>

                        <div
                            v-if="
                                lessonPlanSubject.batch.level.name ===
                                    subject.batch.level.name &&
                                lessonPlanSubject.batch.section ===
                                    subject.batch.section
                            "
                            class="absolute bottom-0 h-[2px] w-full translate-y-full"
                        ></div>
                    </Link>
                </li>
            </ul>
            <LessonPlanMonthViewer @select="selectBatchSession" />
        </div>
    </div>
    <LessonPlanFormModal
        v-model:view="openForm"
        :batch-session="selectedBatchSession"
    />
</template>

<script setup>
import { computed, ref, watch } from "vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import { parseLevel } from "@/utils.js";
import LessonPlanFormModal from "@/Views/Teacher/Views/LessonPlans/LessonPlanFormModal.vue";
import LessonPlanMonthViewer from "@/Views/Teacher/Views/LessonPlans/LessonPlanMonthViewer.vue";
import Header from "@/Views/Teacher/Views/Header.vue";

const batchSessions = computed(() => usePage().props["batch_sessions"]);
const batch = computed(() => usePage().props.batch);
const subjects = computed(() => usePage().props.subjects);
const lessonPlanSubject = computed(() => usePage().props.lesson_plan_subject);
const months = computed(() => usePage().props.months || []);
const selectedMonth = ref(usePage().props.selected_month);
const selectedSubject = computed(
    () => usePage().props.lesson_plan_subject.subject.full_name
);

function handleMonthChange(month) {
    selectedMonth.value = month;
    router.get(
        "/teacher/lesson-plan",
        {
            batch_subject_id: selectedSubject.value.id,
            month: selectedMonth.value,
        },
        { preserveState: true }
    );
}

watch(selectedMonth, handleMonthChange);

const openForm = ref(false);
const selectedBatchSession = ref(null);

function selectBatchSession(session) {
    selectedBatchSession.value = session;
    openForm.value = true;
}
</script>

<style scoped></style>
