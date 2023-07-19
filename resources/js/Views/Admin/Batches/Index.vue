<template>
    <div class="flex w-full flex-col justify-start space-y-6">
        <div class="rounded-lg border">
            <BatchPerformance :grade="selectedBatch.grade" />
        </div>
        <div
            class="flex w-full flex-col justify-between space-x-5 py-3 lg:flex-row"
        >
            <div class="flex w-full rounded-lg border lg:w-7/12">
                <Flags :title="$t('batchesIndex.flagList')" view="homeroom" />
            </div>
            <div class="flex w-full justify-between lg:w-4/12">
                <div
                    class="flex min-h-full w-full flex-col items-center justify-center rounded-lg bg-gradient-to-bl from-violet-500 to-purple-500 py-2 text-center uppercase text-white shadow-sm"
                >
                    <div
                        v-if="activeSession.length > 0"
                        class="flex h-full flex-col items-center space-y-1.5"
                    >
                        <div class="text-[0.65rem] font-light">
                            {{ $t("batchesIndex.attending") }}
                        </div>

                        <div class="text-3xl font-semibold uppercase">
                            {{
                                activeSession[0].batch_subject?.subject
                                    ?.full_name
                            }}
                        </div>
                        <div class="text-[0.65rem] font-light">
                            {{ $t("common.with") }}
                        </div>

                        <div
                            class="cursor-pointer text-xs font-semibold uppercase underline-offset-2 hover:scale-105 hover:underline"
                        >
                            {{ activeSession[0].teacher.user.name }}
                        </div>
                    </div>
                    <div v-else>{{ $t("batchesIndex.noActiveSession") }}</div>
                </div>
            </div>
        </div>

        <TabElement v-model:active="activeTab" :tabs="tabs">
            <template #[assessmentsTab]>
                <Assessments />
            </template>
            <template #[StudentNotesTab]>
                <StudentNotes />
            </template>
            <template #[absenteesTab]>
                <Absentees />
            </template>
        </TabElement>
    </div>
</template>
<script setup>
import BatchPerformance from "@/Views/Teacher/Views/Batches/BatchPerformance/Index.vue";
import { computed, ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import Flags from "@/Views/Flag/Index.vue";
import Absentees from "@/Views/Admin/Batches/Absentees.vue";
import StudentNotes from "@/Views/Admin/Batches/StudentNotes.vue";
import Assessments from "@/Views/Admin/Batches/Assessments.vue";
import TabElement from "@/Components/TabElement.vue";
import { toUnderscore } from "@/utils";
import { useI18n } from "vue-i18n";

const { t } = useI18n();

const assessmentsTab = toUnderscore(t("common.assessments"));
const StudentNotesTab = toUnderscore(t("common.studentNotes"));
const absenteesTab = toUnderscore(t("common.absentees"));

const tabs = [assessmentsTab, StudentNotesTab, absenteesTab];

const activeTab = ref(assessmentsTab);

const selectedBatch = computed(() => usePage().props.selected_batch);

const activeSession = computed(() => usePage().props.active_session);

const showAssessment = ref(true);
const showStudentNotes = ref(false);
const showAbsentees = ref(false);
const selectedCard = ref("assessments");

function openAssessment() {
    showAssessment.value = true;
    showStudentNotes.value = false;
    showAbsentees.value = false;
    selectedCard.value = "assessments";
}

function openStudentNotes() {
    showStudentNotes.value = true;
    showAssessment.value = false;
    showAbsentees.value = false;
    selectedCard.value = "studentNotes";
}

function openAbsentees() {
    showAbsentees.value = true;
    showAssessment.value = false;
    showStudentNotes.value = false;
    selectedCard.value = "absentees";
}
</script>
<style scoped>
.bg-brand-150 {
    background-color: #edf2f7;
}
</style>
