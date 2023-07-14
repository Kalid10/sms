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

        <div
            class="flex w-full flex-col justify-between rounded-lg border py-6 lg:flex-row lg:pr-5"
        >
            <div
                class="flex h-full w-full flex-row gap-2 rounded-lg lg:w-2/12 lg:flex-col lg:gap-5"
            >
                <Card
                    :title="$t('common.assessments')"
                    :class="`lg:min-w-full cursor-pointer ${
                        selectedCard === 'assessments' ? 'bg-gray-200' : ''
                    }`"
                    icon
                    @click="openAssessment"
                >
                    <h3 class="text-sm text-gray-500">
                        {{ $t("batchesIndex.clickToViewScheduledAssessments") }}
                    </h3>
                    <template #icon>
                        <ArrowRightCircleIcon />
                    </template>
                </Card>

                <Card
                    title="Students Notes"
                    :class="`lg:min-w-full cursor-pointer ${
                        selectedCard === 'studentNotes' ? 'bg-gray-200' : ''
                    }`"
                    icon
                    @click="openStudentNotes"
                >
                    <h3 class="text-sm text-gray-500">
                        {{ $t("batchesIndex.clickToViewStudentsNotes") }}
                    </h3>
                    <template #icon>
                        <ArrowRightCircleIcon />
                    </template>
                </Card>

                <Card
                    title="Today's Absentees"
                    :class="`lg:min-w-full cursor-pointer ${
                        selectedCard === 'absentees' ? 'bg-gray-200' : ''
                    }`"
                    icon
                    @click="openAbsentees"
                >
                    <h3 class="text-sm text-gray-500">
                        Click here to view today's absentees
                    </h3>
                    <template #icon>
                        <ArrowRightCircleIcon />
                    </template>
                </Card>
            </div>
            <div class="flex w-full rounded border p-3 shadow-lg lg:w-10/12">
                <BatchAssessment v-if="showAssessment" />
                <StudentNotes v-if="showStudentNotes" />
                <Absentees v-if="showAbsentees" />
            </div>
        </div>
    </div>
</template>
<script setup>
import BatchPerformance from "@/Views/Teacher/Views/Batches/BatchPerformance/Index.vue";
import { computed, ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import Flags from "@/Views/Flag/Index.vue";
import BatchAssessment from "@/Views/Admin/Batches/Assessments.vue";
import Card from "@/Components/Card.vue";
import { ArrowRightCircleIcon } from "@heroicons/vue/24/solid";
import StudentNotes from "@/Views/Admin/Batches/StudentNotes.vue";
import Absentees from "@/Views/Admin/Batches/Absentees.vue";

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
.bg-gray-200 {
    background-color: #edf2f7;
}
</style>
