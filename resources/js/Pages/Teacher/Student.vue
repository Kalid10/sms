<template>
    <div
        class="flex min-h-screen w-full items-start justify-between bg-gray-50 pl-4 2xl:px-5"
    >
        <!--        Left side-->
        <div
            class="ml-2 mr-5 flex w-7/12 grow flex-col items-center space-y-7 py-4 2xl:py-6"
        >
            <div class="flex w-full justify-between space-x-6">
                <!--        Assessments and NextClass section-->
                <div
                    class="flex h-full w-9/12 flex-col items-center justify-center space-y-6"
                >
                    <div
                        class="flex h-full w-full items-center justify-between space-x-4 divide-x rounded-lg bg-gradient-to-bl from-neutral-700 to-zinc-800 py-6 pl-4 text-gray-200 shadow-sm"
                    >
                        <div class="flex h-full w-6/12 space-x-5">
                            <img
                                :src="`https://xsgames.co/randomusers/avatar.php?g=male`"
                                alt="avatar"
                                class="w-20 rounded-md object-contain"
                            />
                            <Header :title="student.user.name + ' 11A'" />
                        </div>
                        <div class="w-4/12 px-1">
                            <CurrentClass />
                        </div>
                    </div>
                    <!--           Assessments section-->
                    <div
                        class="w-full rounded-lg bg-white py-3 pl-3 pr-10 lg:w-full"
                    >
                        <Assessments />
                    </div>
                </div>
                <div class="flex h-full w-3/12 flex-col">
                    <GeneralReport />
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
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import Assessments from "@/Views/Teacher/Student/Assessments.vue";
import Rank from "@/Views/Teacher/Student/Rank.vue";
import Notes from "@/Views/Teacher/Student/Notes.vue";
import Information from "@/Views/Teacher/Student/GuardianInformation.vue";
import { isSidebarOpenOnXlDevice } from "@/utils";
import Header from "@/Views/Teacher/Header.vue";
import CurrentClass from "@/Views/Teacher/Batches/CurrentClass.vue";
import GeneralReport from "@/Views/Teacher/Student/GeneralReport.vue";

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
