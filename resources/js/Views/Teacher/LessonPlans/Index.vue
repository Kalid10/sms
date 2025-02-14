<template>
    <div
        class="flex h-screen max-h-full w-full flex-col space-y-5 overflow-auto bg-white px-3 py-5 text-white"
    >
        <div class="flex w-full justify-evenly">
            <Header
                :title="$t('lessonPlansIndex.myLessonPlans')"
                :select-input-options="months"
                :selected-input="selectedMonth"
                :show-current-class="false"
                class="pr-5"
                @change="handleMonthChange"
            >
                <template #default>
                    <AdjustmentsHorizontalIcon
                        class="w-5 text-white"
                        @click="showFilter = true"
                    />
                </template>
            </Header>
        </div>

        <div class="flex h-full max-h-full w-full grow flex-col space-y-2">
            <ul
                class="scrollbar-hide flex h-fit items-center overflow-x-auto py-5"
            >
                <li v-for="(subject, s) in subjects" :key="s" class="w-full">
                    <Link
                        :href="getLink(subject)"
                        :class="
                            lessonPlanSubject.batch.level.name ===
                                subject.batch.level.name &&
                            lessonPlanSubject.batch.section ===
                                subject.batch.section
                                ? ' text-white bg-brand-400 rounded-2xl'
                                : 'text-brand-text-50'
                        "
                        class="grid min-w-[9rem] max-w-sm cursor-pointer place-items-center py-2 text-center text-sm font-medium"
                    >
                        <span class="flex w-full items-end justify-center px-2">
                            <span class=""
                                >{{ parseLevel(subject.batch.level.name) }}
                                {{ subject.batch.section }}</span
                            >
                            &nbsp;
                            <span>{{ subject.subject.full_name }}</span>
                        </span>
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

    <Modal v-model:view="showFilter">
        <Filters @filter="applyFilters" />
    </Modal>
</template>

<script setup>
import { computed, ref, watch } from "vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import { isAdmin, parseLevel } from "@/utils.js";
import LessonPlanFormModal from "@/Views/Teacher/Views/LessonPlans/LessonPlanFormModal.vue";
import LessonPlanMonthViewer from "@/Views/Teacher/Views/LessonPlans/LessonPlanMonthViewer.vue";
import Header from "@/Views/Teacher/Views/Header.vue";
import Filters from "@/Views/Filters.vue";
import Modal from "@/Components/Modal.vue";
import { AdjustmentsHorizontalIcon } from "@heroicons/vue/20/solid";

const showFilter = ref(false);

const batchSessions = computed(() => usePage().props["batch_sessions"]);
const batch = computed(() => usePage().props.batch);
const subjects = computed(() => usePage().props.subjects);
const lessonPlanSubject = computed(() => usePage().props.lesson_plan_subject);
const months = computed(() => usePage().props.months || []);
const selectedMonth = ref(usePage().props.selected_month);
const selectedSubject = computed(
    () => usePage().props.lesson_plan_subject.subject.full_name
);

const schoolYears = computed(() => usePage().props.school_years);
const semesters = computed(() => usePage().props.semesters);
const quarters = computed(() => usePage().props.quarters);

function applyFilters(params) {
    params.teacher_id = usePage().props.teacher.id;
    router.get(
        isAdmin() ? "/admin/teachers/lesson-plan" : "/teacher/lesson-plan",
        params,
        {
            onSuccess: () => {
                showFilter.value = false;
            },
            preserveState: true,
        }
    );
}

function handleMonthChange(month) {
    selectedMonth.value = month;
    router.get(
        isAdmin() ? "/admin/teachers/lesson-plan" : "/teacher/lesson-plan",
        {
            teacher_id: usePage().props.teacher.id,
            batch_subject_id: selectedSubject.value.id,
            month: selectedMonth.value,
        },
        { preserveState: true }
    );
}

const getLink = (subject) => {
    return isAdmin()
        ? `/admin/teachers/lesson-plan?teacher_id=${
              usePage().props.teacher.id
          }&batch_subject_id=${subject.id}&month=${selectedMonth.value}`
        : `/teacher/lesson-plan?batch_subject_id=${subject.id}&month=${selectedMonth.value}`;
};

watch(selectedMonth, handleMonthChange);

const openForm = ref(false);
const selectedBatchSession = ref(null);

function selectBatchSession(session) {
    selectedBatchSession.value = session;
    openForm.value = true;
}
</script>

<style scoped></style>
