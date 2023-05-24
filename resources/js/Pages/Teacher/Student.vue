<template>
    <div
        class="flex min-h-screen w-full items-start justify-between pl-4 2xl:px-5"
    >
        <!--        Left side-->
        <div
            class="ml-2 mr-5 flex w-8/12 grow flex-col items-center space-y-7 py-4 2xl:py-6"
        >
            <div class="flex w-full justify-between">
                <!--        Header-->
                <Header :title="student.user.name + ' 11A'" />

                <CurrentClass />
            </div>

            <!--        Statistics-->
            <div class="flex h-24 w-full items-center justify-center">
                <!--                <Statistics />-->
                <BatchPerformance />
            </div>

            <!--        Assessments and NextClass section-->
            <div
                class="flex h-full w-full flex-col items-center justify-center space-y-6"
            >
                <div class="flex w-full items-center justify-between pb-2">
                    <!--           Assessments section-->
                    <div class="w-full rounded-md p-2 shadow-md lg:w-8/12">
                        <Assessments />
                    </div>
                </div>
            </div>
        </div>

        <!--        Right side-->
        <div
            class="flex min-h-screen flex-col items-center space-y-3 border-l pb-4 pt-2"
            :class="isSidebarOpenOnXlDevice ? 'w-4/12' : 'w-3/12'"
        >
            <Information />

            <div class="h-[0.02rem] w-11/12 bg-neutral-300"></div>

            <Rank />

            <div class="h-[0.02rem] w-11/12 bg-neutral-300"></div>

            <Notes />
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import Assessments from "@/Views/Teacher/Student/Assessments.vue";
import Rank from "@/Views/Teacher/Student/Rank.vue";
import Notes from "@/Views/Teacher/Student/Notes.vue";
import Information from "@/Views/Teacher/Student/GuardianInformation.vue";
import { isSidebarOpenOnXlDevice } from "@/utils";
import Header from "@/Views/Teacher/Header.vue";
import BatchPerformance from "@/Views/Teacher/Batches/BatchPerformance/Index.vue";
import CurrentClass from "@/Views/Teacher/Batches/CurrentClass.vue";

const student = computed(() => usePage().props.student);
const batchSessions = computed(() => usePage().props.batch_sessions);
const teacher = usePage().props.auth.user.teacher;

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
</script>

<style scoped></style>
