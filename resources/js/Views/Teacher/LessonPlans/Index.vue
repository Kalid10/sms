<template>
    <div
        class="flex h-screen max-h-full w-full flex-col space-y-5 overflow-auto border-l border-zinc-500 bg-white py-5 px-3 text-white"
    >
        <FilterModal v-model:view="showFilter">
            <Filters
                :school-years="schoolYears"
                :semesters="semesters"
                :quarters="quarters"
                @filter="applyFilters"
            />
        </FilterModal>

        <div class="flex w-full justify-evenly">
            <Header
                title="My Lesson Plans"
                :select-input-options="months"
                :selected-input="selectedMonth"
                :show-current-class="false"
                class="pr-5"
                @change="handleMonthChange"
            >
                <template #default>
                    <div
                        class="mx-4 flex w-3/12 cursor-pointer justify-center !rounded-2xl bg-zinc-200 px-2 font-semibold"
                        @click="showFilter = true"
                    >
                        <AdjustmentsHorizontalIcon class="w-4 text-black" />
                        <SecondaryButton
                            title="Filter lesson plans"
                            class="w-fit p-0"
                        />
                    </div>
                </template>
            </Header>
        </div>

        <div class="flex h-full max-h-full w-full grow flex-col">
            <ul class="flex gap-3 py-4">
                <li v-for="(subject, s) in subjects" :key="s">
                    <Link
                        :href="getLink(subject)"
                        :class="
                            lessonPlanSubject.batch.level.name ===
                                subject.batch.level.name &&
                            lessonPlanSubject.batch.section ===
                                subject.batch.section
                                ? ' text-zinc-800 bg-zinc-200/60  rounded-2xl'
                                : 'text-gray-800'
                        "
                        class="relative grid min-w-[4rem] cursor-pointer place-items-center py-2 px-5 text-center text-sm font-medium"
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
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Filters from "@/Views/Filters.vue";
import Modal from "@/Components/Modal.vue";
import { AdjustmentsHorizontalIcon } from "@heroicons/vue/20/solid";
import FilterModal from "@/Components/Modal.vue";

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
