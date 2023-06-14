<template>
    <div
        class="flex min-h-screen w-full flex-col space-y-3 bg-gray-50 p-1 lg:space-y-3 lg:px-1 2xl:pl-4 2xl:pr-12"
    >
        <!--                 Next Class Header On Mobile Devices-->
        <div
            v-if="nextClass"
            class="flex h-16 w-full flex-col justify-center rounded-sm bg-black px-2 text-center text-xs font-light leading-5 text-white lg:hidden"
            @click="scrollToNextClass"
        >
            <div class="w-full">
                Your next class is
                <span class="font-semibold">
                    {{ nextClass.batch_subject.subject.full_name }}
                </span>
                with grade
                <span class="font-semibold">
                    {{ nextClass.batch_subject.batch.level.name }}
                    {{ nextClass.batch_subject.batch.section }}
                </span>
                during
                <span class="font-semibold">
                    period {{ nextClass.school_period.name }} , </span
                >approximately
                <span class="font-semibold"
                    >{{ moment(nextClass.date).fromNow() }}.
                </span>
            </div>
        </div>

        <div class="flex h-screen w-full justify-between py-3 px-5 pt-5">
            <div class="flex w-6/12 flex-col space-y-8">
                <WelcomeHeader />

                <Announcements view="teacher" />

                <div class="rounded-lg bg-white shadow-sm 2xl:px-2">
                    <Assessments />
                </div>
            </div>
            <div class="flex h-full w-5/12 flex-col space-y-8 px-5">
                <div
                    class="hidden h-fit w-full items-center justify-center lg:flex lg:flex-col"
                >
                    <CurrentDaySchedule
                        :schedule="teacherSchedule"
                        class-style="px-4 py-2 space-y-2"
                    />
                </div>
                <NextClass />
                <div
                    class="h-fit w-full rounded-lg bg-white p-4 shadow-sm"
                    :class="isSidebarOpenOnXlDevice ? 'lg:w-full' : 'lg:w-full'"
                >
                    <LessonPlans view="class" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { router, usePage } from "@inertiajs/vue3";
import NextClass from "@/Views/Teacher/Views/NextClass/Index.vue";
import LessonPlans from "@/Views/Teacher/Views/Home/LessonPlans.vue";
import moment from "moment/moment";
import { computed, ref } from "vue";
import { isSidebarOpenOnXlDevice } from "@/utils";
import WelcomeHeader from "@/Views/WelcomeHeader.vue";
import CurrentDaySchedule from "@/Views/CurrentDaySchedule.vue";
import Announcements from "@/Views/Announcements/Index.vue";
import Assessments from "@/Views/Teacher/Views/Home/Assessments.vue";

const teacher = usePage().props.teacher;
const filters = computed(() => usePage().props.filters);
const nextClass = usePage().props.teacher.next_batch_session;
const nextClassSection = ref(null);
const teacherSchedule = computed(() => usePage().props.teacher_schedule);
const scrollToNextClass = () => {
    nextClassSection.value.$el.scrollIntoView({ behavior: "smooth" });
};

function fetchStudent(studentId) {
    router.get(
        "/teacher/students/" +
            studentId +
            "?batch_subject_id=" +
            filters.value.batch_subject_id
    );
}
</script>

<style scoped></style>
