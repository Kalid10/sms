<template>
    <div
        class="flex h-full max-h-full w-full flex-col overflow-auto border-l border-zinc-500 bg-zinc-700 text-white"
    >
        <div
            class="min-h-16 my-4 flex w-full items-center justify-between gap-2 px-8"
        >
            <h3 class="font-semibold">
                <span class="font-normal"
                    >{{
                        months[parseInt(selectedMonth.split("-")[1]) - 1].label
                    }}&nbsp;</span
                >
                <span class="font-normal">Lesson Plans </span>
                <span class="font-normal">for </span>
                <span class="!bg-transparent"
                    >{{ parseLevel(batch.level.name) }}&nbsp;</span
                >
                <span class="!bg-transparent">{{ batch.section }}&nbsp;</span>
                <span class="">{{ selectedSubject }}</span>
            </h3>
            <SelectInput
                v-model="selectedMonth"
                class="min-w-[8rem] rounded-md border text-black"
                placeholder="Months of the school year"
                :options="months"
            />
        </div>

        <div class="flex h-full max-h-full w-full grow flex-col bg-zinc-800">
            <ul class="flex gap-3 border-b-2 px-8">
                <li v-for="(subject, s) in subjects" :key="s">
                    <Link
                        :href="`/teacher/lesson-plan?batch_subject_id=${subject.id}&month=${selectedMonth}`"
                        :class="
                            lessonPlanSubject.batch.level.name ===
                                subject.batch.level.name &&
                            lessonPlanSubject.batch.section ===
                                subject.batch.section
                                ? 'border-x-2 border-t-2 border-zinc-300 bg-zinc-700 text-zinc-200'
                                : 'text-gray-300'
                        "
                        class="relative grid min-w-[4rem] place-items-center rounded-t py-2.5 px-8 text-center text-sm font-medium"
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
                            class="absolute bottom-0 h-[2px] w-full translate-y-full bg-white"
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
import SelectInput from "@/Components/SelectInput.vue";
import LessonPlanMonthViewer from "@/Views/Teacher/Views/LessonPlans/LessonPlanMonthViewer.vue";

const batchSessions = computed(() => usePage().props["batch_sessions"]);
const batch = computed(() => usePage().props.batch);
const subjects = computed(() => usePage().props.subjects);
const lessonPlanSubject = computed(() => usePage().props.lesson_plan_subject);
const months = computed(() => usePage().props.months || []);
const selectedMonth = ref(usePage().props.selected_month);
const selectedSubject = computed(
    () => usePage().props.lesson_plan_subject.subject.full_name
);

function handleMonthChange() {
    router.get("/teacher/lesson-plan", {
        batch_subject_id: selectedSubject.value.id,
        month: selectedMonth.value,
    });
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
