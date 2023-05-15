<template>
    <div class="flex w-full flex-col px-2 py-3 lg:space-y-4 lg:px-12 lg:py-4">
        <!-- Header-->
        <div
            class="flex items-start rounded-lg py-3 text-xl font-light lg:py-4 lg:pl-0 lg:text-4xl"
        >
            <span class="font-semibold">
                {{ student.user.name }}
            </span>
        </div>

        <div
            class="flex w-full flex-col justify-center space-y-6 md:flex-row md:justify-between lg:space-y-0"
        >
            <div class="h-fit w-full rounded-lg md:w-1/2">
                <div class="flex w-full justify-between pr-2">
                    <div class="font-medium 2xl:text-2xl">
                        Recent Assessments
                    </div>

                    <LinkCell
                        class="flex w-fit items-center justify-center"
                        href="/teacher/assessments"
                        value="SEE ALL"
                    />
                </div>
                <Item class="mt-3" :assessments="assessments" view="student" />
            </div>

            <div
                class="flex h-full w-2/3 flex-col items-center justify-evenly rounded-xl text-white md:w-4/12 lg:w-3/12"
                :class="
                    isNextClassSubjectTeacher
                        ? 'bg-gradient-to-r from-neutral-900 to-stone-800'
                        : 'bg-black'
                "
            >
                <NextClass :next-class="upcomingSession" view="student" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import LinkCell from "@/Components/LinkCell.vue";
import Item from "@/Views/Assessments/Item.vue";
import NextClass from "@/Views/Teachers/Home/NextClass/NextClass.vue";

const student = computed(() => usePage().props.student);
const assessments = computed(() => usePage().props.assessments);
const batchSessions = computed(() => usePage().props.batchSessions);
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
