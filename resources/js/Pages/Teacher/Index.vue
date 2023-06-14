<template>
    <div
        class="flex w-full flex-col space-y-3 bg-gray-50 p-1 lg:space-y-0 lg:px-1 2xl:pl-4 2xl:pr-12"
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

        <!--                 Content-->
        <div
            class="flex w-full flex-col space-y-3 px-5 py-3 lg:space-y-1 2xl:space-y-2"
        >
            <div
                class="grid h-fit w-full grid-cols-12 grid-rows-6 gap-4 lg:gap-3 2xl:gap-2"
            >
                <!--                 Welcome header-->
                <WelcomeHeader />
                <div
                    class="col-start-9 row-span-6 hidden lg:inline-flex"
                    :class="
                        isSidebarOpenOnXlDevice
                            ? 'lg:col-span-4'
                            : 'lg:col-span-5'
                    "
                >
                    <div
                        class="hidden h-full w-full items-center justify-center pt-3 lg:flex lg:flex-col"
                    >
                        <div class="mb-8 h-fit w-full">
                            <CurrentDaySchedule
                                :schedule="teacherSchedule"
                                class-style="px-4 py-2 space-y-2"
                            />
                        </div>
                        <NextClass />
                    </div>
                </div>
                <div
                    class="col-span-12 row-span-6 rounded-lg bg-white shadow-sm 2xl:px-2"
                    :class="
                        isSidebarOpenOnXlDevice
                            ? 'lg:col-span-7'
                            : 'lg:col-span-6'
                    "
                >
                    <Assessments />
                </div>
            </div>

            <!--                Students, LessonPlan, Feedback, Grades and SchoolSchedule-->
            <div
                class="flex h-full flex-col justify-between space-y-4 lg:flex-row lg:space-y-0 2xl:pt-8"
            >
                <StudentsTable
                    class="h-fit w-full rounded-lg bg-white px-3 shadow-sm 2xl:w-6/12"
                    :class="isSidebarOpenOnXlDevice ? 'lg:w-5/12' : 'lg:w-5/12'"
                    @click="fetchStudent"
                />

                <SchoolSchedule
                    class="hidden 2xl:hidden"
                    :class="isSidebarOpenOnXlDevice ? 'lg:hidden' : 'lg:w-2/12'"
                />
                <div
                    class="h-fit w-full rounded-lg bg-white p-4 shadow-sm 2xl:w-5/12"
                    :class="isSidebarOpenOnXlDevice ? 'lg:w-6/12' : 'lg:w-5/12'"
                >
                    <LessonPlans view="class" />
                </div>

                <div
                    class="flex h-full w-full flex-col justify-between space-y-5 lg:hidden 2xl:w-4/12"
                >
                    <div class="w-full lg:hidden">
                        <NextClass ref="nextClassSection" />
                    </div>

                    <div class="w-full">
                        <Grades />
                    </div>

                    <div
                        class="mt-5 flex w-full flex-col justify-between space-y-2 lg:flex-row lg:space-y-0"
                    >
                        <div class="w-full lg:w-6/12">
                            <div class="w-full font-medium lg:text-center">
                                Feedbacks
                            </div>
                            <Feedbacks />
                        </div>
                        <div
                            class="flex w-full flex-col items-center lg:w-5/12"
                        >
                            <div class="mb-4 w-full font-medium lg:text-center">
                                School Schedule
                            </div>
                            <SchoolSchedule />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { router, usePage } from "@inertiajs/vue3";
import NextClass from "@/Views/Teacher/NextClass/Index.vue";
import LessonPlans from "@/Views/Teacher/Home/LessonPlans.vue";
import Grades from "@/Views/Teacher/Home/Grades.vue";
import SchoolSchedule from "@/Views/SchoolSchedule/Index.vue";
import moment from "moment/moment";
import Feedbacks from "@/Views/Teacher/Home/Feedbacks.vue";
import { computed, ref } from "vue";
import Assessments from "@/Views/Teacher/Home/Assessments.vue";
import { isSidebarOpenOnXlDevice } from "@/utils";
import StudentsTable from "@/Views/Teacher/StudentsTable.vue";
import WelcomeHeader from "@/Views/WelcomeHeader.vue";
import CurrentDaySchedule from "@/Views/CurrentDaySchedule.vue";

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
