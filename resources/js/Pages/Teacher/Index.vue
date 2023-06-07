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
                <div
                    class="col-span-12 h-fit text-start font-light lg:text-3xl 2xl:text-4xl"
                    :class="
                        isSidebarOpenOnXlDevice
                            ? ' lg:col-span-8'
                            : ' lg:col-span-7'
                    "
                >
                    Welcome back,

                    <span class="ml-0.5 font-semibold lg:ml-2"
                        >{{ teacher.user.name }}!</span
                    >
                </div>
                <div
                    class="col-start-9 row-span-6 hidden lg:inline-flex"
                    :class="
                        isSidebarOpenOnXlDevice
                            ? 'lg:col-span-4'
                            : 'lg:col-span-5'
                    "
                >
                    <div
                        class="hidden h-full w-full items-center justify-center lg:flex"
                    >
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
                class="flex h-full flex-col justify-between space-y-2 lg:flex-row"
            >
                <StudentsTable
                    class="h-fit w-full rounded-lg bg-white px-3 py-1 shadow-sm 2xl:mt-10 2xl:w-6/12"
                    :class="
                        isSidebarOpenOnXlDevice
                            ? 'lg:w-5/12 lg:mt-[-3rem]'
                            : 'lg:w-5/12 lg:mt-[-1rem]'
                    "
                />

                <SchoolSchedule
                    class="hidden 2xl:hidden"
                    :class="isSidebarOpenOnXlDevice ? 'lg:hidden' : 'lg:w-2/12'"
                />
                <LessonPlans
                    class="w-full 2xl:w-5/12"
                    :class="isSidebarOpenOnXlDevice ? 'lg:w-6/12' : 'lg:w-5/12'"
                />

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
import { usePage } from "@inertiajs/vue3";
import NextClass from "@/Views/Teacher/NextClass/Index.vue";
import LessonPlans from "@/Views/Teacher/Home/LessonPlans.vue";
import Grades from "@/Views/Teacher/Home/Grades.vue";
import SchoolSchedule from "@/Views/Teacher/Home/SchoolSchedule/Index.vue";
import moment from "moment/moment";
import Feedbacks from "@/Views/Teacher/Home/Feedbacks.vue";
import { ref } from "vue";
import Assessments from "@/Views/Teacher/Home/Assessments.vue";
import { isSidebarOpenOnXlDevice } from "@/utils";
import StudentsTable from "@/Views/Teacher/StudentsTable.vue";

const teacher = usePage().props.teacher;

const nextClass = usePage().props.teacher.next_batch_session;
const nextClassSection = ref(null);
const scrollToNextClass = () => {
    nextClassSection.value.$el.scrollIntoView({ behavior: "smooth" });
};
</script>

<style scoped></style>
