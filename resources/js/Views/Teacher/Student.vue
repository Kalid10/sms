<template>
    <div
        class="flex min-h-screen w-full items-start justify-between bg-gray-50 pl-4 2xl:pr-5 2xl:pl-2"
    >
        <!--        Left side-->
        <div
            class="ml-2 mr-5 flex w-7/12 grow flex-col items-center space-y-7 p-4 2xl:py-6"
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

                <!--           General report section, i.e: grade, rank, conduct, ....-->
                <div class="flex h-full w-full flex-col">
                    <GeneralReport />
                </div>

                <!--           Assessments section-->
                <div class="flex w-full justify-between lg:w-full">
                    <div class="flex w-full justify-between space-x-7">
                        <div class="w-8/12 rounded-lg bg-white p-3">
                            <Assessments />
                        </div>

                        <div class="w-4/12">
                            <AssessmentBreakDown v-if="selectedBatchSubject" />
                        </div>
                    </div>
                </div>

                <div class="flex w-full justify-between space-x-5 pr-3">
                    <div class="w-1/2 rounded-lg bg-white p-3 shadow-sm">
                        <AbsenteeRecords />
                    </div>
                    <div class="w-1/2">
                        <Flag
                            :batch-subject-options="batchSubjectOptions"
                            :student="student"
                        />
                    </div>
                </div>
            </div>

            <Modal v-model:view="showGrade">
                <StudentGradeDetail
                    :student-name="student"
                    :student-grade="grade"
                />
            </Modal>
        </div>

        <!--        Right side-->
        <div
            class="flex min-h-screen flex-col items-center space-y-8 border-l border-gray-200 py-4 pl-5"
            :class="isSidebarOpenOnXlDevice ? 'w-4/12' : 'w-3/12'"
        >
            <div
                class="flex h-36 w-full flex-col items-center justify-evenly rounded-lg bg-gradient-to-tl from-purple-500 to-violet-500 shadow-sm"
            >
                <div class="px-4 text-center text-white">
                    Want to see how {{ student?.user.name }} is doing on other
                    classes?
                </div>

                <SecondaryButton
                    title="View Full Grade Report"
                    class="w-2/3 !rounded-2xl !border-none bg-purple-100 font-semibold"
                    @click="showGrade = true"
                />
            </div>

            <Information />

            <Notes />
        </div>
    </div>
</template>

<script setup>
import { computed, ref } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import Assessments from "@/Views/Teacher/Views/Student/Assessments.vue";
import Notes from "@/Views/Teacher/Views/Student/Notes.vue";
import Information from "@/Views/Teacher/Views/Student/GuardianInformation.vue";
import { isSidebarOpenOnXlDevice } from "@/utils";
import Header from "@/Views/Teacher/Views/Header.vue";
import AssessmentBreakDown from "@/Views/Teacher/Views/Assessments/AssessmentBreakDown.vue";
import StudentGradeDetail from "@/Views/Teacher/Views/Grade/StudentGradeDetail.vue";
import GeneralReport from "@/Views/Teacher/Views/Student/GeneralReport.vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import AbsenteeRecords from "@/Views/AbsenteeRecords.vue";
import Flag from "@/Views/Flag/Index.vue";

const student = computed(() => usePage().props.student);
const batchSessions = computed(() => usePage().props.batch_sessions);
const teacher = usePage().props.auth.user.teacher;
const batchSubject = computed(() => usePage().props.batch_subject);
const batchSubjects = usePage().props.batch_subjects ?? [];
const selectedBatchSubject = ref(batchSubject.value?.id);
const auth = computed(() => usePage().props.auth);
const grade = computed(() => usePage().props.grade);
const showGrade = ref(false);

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
