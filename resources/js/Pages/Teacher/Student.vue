<template>
    <div
        class="flex min-h-screen w-full justify-between bg-stone-100/70 2xl:px-5"
    >
        <!--        Left side-->
        <div class="mx-10 flex w-8/12 grow flex-col space-y-4 py-4 2xl:py-6">
            <!--        Header-->
            <div
                class="flex flex-col items-start justify-between space-y-1 rounded-lg"
            >
                <span class="text-xl font-semibold lg:text-4xl">
                    {{ student.user.name }}</span
                >
                <span class="text-sm">{{
                    moment().format(" dddd MMMM D YYYY")
                }}</span>
            </div>

            <!--        Statistics-->
            <div class="flex h-32 w-full items-center justify-between">
                <Statistics />
            </div>

            <!--        Assessments and NextClass section-->
            <div
                class="flex h-full w-full flex-col items-center justify-center space-y-6"
            >
                <div class="flex w-full justify-between pt-10 pb-2">
                    <!--           Assessments section-->
                    <div class="w-full lg:w-7/12">
                        <Assessments />
                    </div>

                    <!--            NextClass section-->
                    <div
                        class="flex h-full w-full flex-col items-center justify-evenly rounded-lg text-white md:w-9/12 lg:w-4/12"
                        :class="
                            isNextClassSubjectTeacher
                                ? 'bg-gradient-to-r from-neutral-900 to-stone-800'
                                : 'bg-black'
                        "
                    >
                        <NextClass
                            :next-class="upcomingSession"
                            view="student"
                        />
                    </div>
                </div>
                <div
                    class="w-full cursor-pointer text-end text-sm font-light underline underline-offset-2 hover:font-medium"
                    @click="showClassSchedule = !showClassSchedule"
                >
                    <span v-if="showClassSchedule">Hide</span>
                    <span v-else>Show</span> Class Schedule
                </div>
                <div class="w-full grow">
                    <StudentSemesterSchedule
                        v-if="showClassSchedule"
                        class="h-full w-full"
                    />
                </div>
            </div>
        </div>

        <!--        Right side-->
        <div
            class="flex min-h-screen flex-col items-center space-y-7 border-l pb-4 pt-5"
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
import { computed, ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import NextClass from "@/Views/Teacher/NextClass/NextClass.vue";
import Assessments from "@/Views/Teacher/Student/Assessments.vue";
import Statistics from "@/Views/Teacher/Student/Statistics.vue";
import moment from "moment";
import Rank from "@/Views/Teacher/Student/Rank.vue";
import StudentSemesterSchedule from "@/Views/Students/StudentSemesterSchedule.vue";
import Notes from "@/Views/Teacher/Student/Notes.vue";
import Information from "@/Views/Teacher/Student/GuardianInformation.vue";
import { isSidebarOpenOnXlDevice } from "@/utils";

const student = computed(() => usePage().props.student);
const batchSessions = computed(() => usePage().props.batch_sessions);
const teacher = usePage().props.auth.user.teacher;
const showClassSchedule = ref(false);

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
