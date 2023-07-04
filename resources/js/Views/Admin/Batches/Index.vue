<template>
    <div class="flex w-full flex-col justify-start space-y-6">
        <div class="rounded-lg border">
            <BatchPerformance :grade="selectedBatch.grade" />
        </div>
        <div
            class="flex w-full justify-between space-x-5 rounded-lg border py-3"
        >
            <div class="w-6/12">
                <Flags title="Flag List" view="homeroom" />
            </div>
            <div class="flex w-4/12 justify-between">
                <div
                    class="flex min-h-full w-full flex-col items-center justify-center rounded-lg bg-gradient-to-bl from-violet-500 to-purple-500 py-2 text-center uppercase text-white shadow-sm"
                >
                    <div
                        v-if="activeSession.length > 0"
                        class="flex h-full flex-col items-center space-y-1.5"
                    >
                        <div class="text-[0.65rem] font-light">Attending</div>

                        <div class="text-3xl font-semibold uppercase">
                            {{
                                activeSession[0].batch_subject.subject.full_name
                            }}
                        </div>
                        <div class="text-[0.65rem] font-light">with</div>

                        <div
                            class="cursor-pointer text-xs font-semibold uppercase underline-offset-2 hover:scale-105 hover:underline"
                        >
                            {{ activeSession[0].teacher.user.name }}
                        </div>
                    </div>
                    <div v-else>No active session</div>
                </div>
            </div>
        </div>

        <Heading value="Title" class="pl-5" />
        <div class="flex w-full justify-between py-6 pr-5">
            <div class="flex h-full w-3/12 flex-col gap-5 rounded-lg px-3">
                <Card
                    title="Assessments"
                    class="min-w-full cursor-pointer"
                    icon
                    @click="openAssessment"
                >
                    <h3 class="text-sm text-gray-500">
                        Click here to view scheduled assessments
                    </h3>
                    <template #icon>
                        <ArrowRightCircleIcon />
                    </template>
                </Card>

                <Card
                    title="Students Notes"
                    class="min-w-full"
                    icon
                    @click="openStudentNotes"
                >
                    <h3 class="text-sm text-gray-500">
                        Click here to view students notes
                    </h3>
                    <template #icon>
                        <ArrowRightCircleIcon />
                    </template>
                </Card>
            </div>
            <div class="flex w-8/12 border p-3 shadow-lg">
                <BatchAssessment v-if="showAssessment" />
                <StudentNotes v-if="showStudentNotes" />
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
import Heading from "@/Components/Heading.vue";
import StudentNotes from "@/Views/Admin/Batches/StudentNotes.vue";

const selectedBatch = computed(() => usePage().props.selected_batch);

const activeSession = computed(() => usePage().props.active_session);

const showAssessment = ref(true);
const showStudentNotes = ref(false);

function openAssessment() {
    showAssessment.value = true;
    showStudentNotes.value = false;
}

function openStudentNotes() {
    showStudentNotes.value = true;
    showAssessment.value = false;
}
</script>
<style scoped></style>
