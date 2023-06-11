<template>
    <div
        class="flex min-h-screen w-full items-start justify-between bg-gray-50 pl-4 2xl:px-5"
    >
        <!--        Left side-->
        <div
            class="ml-2 mr-5 flex w-7/12 grow flex-col items-center space-y-7 py-4 2xl:py-6"
        >
            <!--        Assessments and NextClass section-->
            <div
                class="flex h-full w-full flex-col items-center justify-center space-y-6"
            >
                <Header
                    :title="student.user.name"
                    :select-input-options="batchSubjectOptions"
                    :selected-input="batchSubject?.id ?? null"
                    image="sd"
                    @change="updateBatchSubject"
                />

                <!--           Assessments section-->
                <div class="flex w-full justify-between lg:w-full">
                    <div class="flex w-9/12 flex-col space-y-6 pl-3 pr-10">
                        <div class="w-full rounded-lg bg-white py-3 pl-3 pr-10">
                            <Assessments />
                        </div>

                        <AssessmentBreakDown v-if="selectedBatchSubject" />

                        <div
                            class="w-full rounded-lg bg-white p-4 text-black shadow-sm"
                        >
                            <div class="pb-2 text-xl font-light">
                                {{ student.user.name }}'s General Quarterly
                                Statistics
                            </div>
                            <div
                                class="flex w-full justify-between divide-x py-2 text-center"
                            >
                                <div class="w-4/12">
                                    <div class="text-2xl font-bold">
                                        <span v-if="student.quarterly_grade">
                                            {{ student.quarterly_grade.score }}
                                        </span>
                                        <span v-else> - </span>
                                    </div>
                                    <div class="text-[0.65rem] font-light">
                                        Quarter Grade
                                    </div>
                                </div>
                                <div class="w-4/12">
                                    <div class="text-2xl font-bold">
                                        <span v-if="student.quarterly_grade">
                                            {{
                                                numberWithOrdinal(
                                                    student.quarterly_grade.rank
                                                )
                                            }}
                                        </span>
                                        <span v-else> - </span>
                                    </div>
                                    <div class="text-[0.65rem] font-light">
                                        Quarter Rank
                                    </div>
                                </div>
                                <div class="w-4/12">
                                    <div class="text-2xl font-bold">
                                        <span v-if="student.quarterly_grade">
                                            {{
                                                student.quarterly_grade.conduct
                                            }}
                                        </span>
                                        <span v-else> - </span>
                                    </div>
                                    <div class="text-[0.65rem] font-light">
                                        Quarter Conduct
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex h-full w-3/12 flex-col">
                        <GeneralReport />
                    </div>
                </div>
            </div>

            <div
                class="flex h-96 w-full items-center justify-center border-t text-8xl font-light text-gray-500"
            >
                Under construction
            </div>
        </div>

        <!--        Right side-->
        <div
            class="flex min-h-screen flex-col items-center space-y-8 border-l border-gray-200 bg-gray-100 py-4 pl-5"
            :class="isSidebarOpenOnXlDevice ? 'w-4/12' : 'w-3/12'"
        >
            <Rank />

            <Information />

            <Notes />
        </div>
    </div>
</template>

<script setup>
import { computed, ref } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import Assessments from "@/Views/Teacher/Student/Assessments.vue";
import Rank from "@/Views/Teacher/Student/Rank.vue";
import Notes from "@/Views/Teacher/Student/Notes.vue";
import Information from "@/Views/Teacher/Student/GuardianInformation.vue";
import { isSidebarOpenOnXlDevice, numberWithOrdinal } from "@/utils";
import Header from "@/Views/Teacher/Header.vue";
import GeneralReport from "@/Views/Teacher/Student/GeneralReport.vue";
import AssessmentBreakDown from "@/Views/Teacher/Assessments/AssessmentBreakDown.vue";

const student = computed(() => usePage().props.student);
const batchSessions = computed(() => usePage().props.batch_sessions);
const teacher = usePage().props.auth.user.teacher;
const batchSubject = computed(() => usePage().props.batch_subject);
const batchSubjects = usePage().props.batch_subjects ?? [];
const selectedBatchSubject = ref(batchSubject.value?.id);
const auth = computed(() => usePage().props.auth);

const batchSubjectOptions = computed(() => {
    let options = batchSubjects.map((batchSubject) => {
        return {
            value: batchSubject?.id,
            label:
                batchSubject.batch.level.name +
                " " +
                batchSubject.batch.section +
                " " +
                batchSubject.subject.full_name,
        };
    });

    // If the auth is admin, add the option to select all batch subjects
    if (auth.value.user.type === "admin") {
        options.unshift({
            value: null,
            label: "All",
        });
    }
    return options;
});

// Get the first batch session from batchSessions where batchSubject id is not null
const upcomingSession = computed(() => {
    const value = batchSessions.value.find(
        (session) => session.batch_schedule.batch_subject_id
    );

    // Map value to an object with the required properties
    return {
        id: value.id,
        batch_subject: value.batch_schedule.batch_subject,
        school_period: value.batch_schedule.school_period,
        status: value.status,
        date: value.date,
    };
});

const isNextClassSubjectTeacher = computed(
    () => upcomingSession.value.batch_subject.teacher.id === teacher.id
);

const updateBatchSubject = (batchSubjectId) => {
    selectedBatchSubject.value = batchSubjectId;
    router.get(
        "/students/" + student.value.id,
        {
            batch_subject_id: batchSubjectId,
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
};
</script>

<style scoped></style>
