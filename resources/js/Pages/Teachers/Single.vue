<template>
    <div class="flex w-full">
        <div
            class="bg-zinc-800 text-white"
            :class="[
                isSideBarOpen
                    ? 'min-w-[16rem] bg-blue-200 lg:w-80'
                    : 'w-3/12 lg:w-16',
                'transition-all duration-300 ease-in-out ',
            ]"
            @click="isSideBarOpen = !isSideBarOpen"
        >
            <SideBar
                class="sticky top-0 h-screen"
                :header="teacher"
                :main-items="sidebarItems"
                :footer-items="footerItems"
                :is-open="isSideBarOpen"
            />
        </div>

        <div
            :class="isSideBarOpen ? 'min-w-full lg:min-w-0 blur lg:blur-0' : ''"
            class="flex flex-col items-center overflow-x-hidden lg:w-full"
            @click="isSideBarOpen = false"
        >
            <div
                class="flex w-full flex-col space-y-3 px-5 py-3 lg:space-y-10 lg:px-12"
            >
                <!--                 Welcome header-->
                <div
                    class="flex items-center rounded-lg py-5 text-xl font-light lg:py-7 lg:pl-0 lg:text-4xl"
                >
                    Welcome back,

                    <span class="ml-1 font-semibold lg:ml-2"
                        >{{ teacher.user.name }}!</span
                    >
                </div>

                <!--                Assessments and Next class-->
                <div
                    class="flex w-full flex-col justify-between space-y-5 lg:flex-row lg:space-y-0"
                >
                    <div class="w-full lg:w-6/12">
                        <Assessments class="" />
                    </div>
                    <div class="hidden lg:inline-block lg:w-5/12">
                        <NextClass />
                    </div>
                </div>

                <!--                Students, LessonPlan, Feedback, Grades and SchoolSchedule-->
                <div
                    class="flex h-full w-full flex-col justify-between space-y-2 pt-8 lg:flex-row"
                >
                    <Students class="w-full lg:w-3/12" />
                    <LessonPlans class="w-full lg:w-4/12" />

                    <div
                        class="w-ful flex h-full flex-col justify-between lg:w-4/12"
                    >
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
                                <div
                                    class="mb-4 w-full font-medium lg:text-center"
                                >
                                    School Schedule
                                </div>
                                <SchoolSchedule />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--                            <Feedbacks class="col-span-12"/>-->
            <!--                            <Subjects/>-->

            <!--                            <HomeroomClasses/>-->
        </div>
    </div>
</template>

<script setup>
// import Subjects from "@/Views/Teachers/Subjects.vue";
// import HomeroomClasses from "@/Views/Teachers/HomeroomClasses.vue";
import { usePage } from "@inertiajs/vue3";
import Assessments from "@/Views/Teachers/Assessments.vue";
import { HomeIcon } from "@heroicons/vue/24/solid";
import {
    CalendarDaysIcon,
    CalendarIcon,
    ChatBubbleBottomCenterIcon,
    ClipboardIcon,
    Cog6ToothIcon,
    PowerIcon,
    UserIcon,
} from "@heroicons/vue/20/solid";
import NextClass from "@/Views/Teachers/NextClass.vue";
import Students from "@/Views/Teachers/Students.vue";
import LessonPlans from "@/Views/Teachers/LessonPlans.vue";
import Grades from "@/Views/Teachers/Grades.vue";
import SchoolSchedule from "@/Views/Teachers/SchoolSchedule.vue";
import Feedbacks from "@/Views/Teachers/Feedbacks.vue";
import SideBar from "@/Pages/Layouts/SideBar.vue";
import { ref } from "vue";

const teacher = usePage().props.teacher;

// Populate sidebar items
const sidebarItems = [
    {
        name: "Home",
        icon: HomeIcon,
        route: "/teacher",
    },
    {
        name: "Lesson Plan",
        icon: CalendarIcon,
        route: "/teacher/feedbacks",
    },
    {
        name: "Homerooms",
        icon: UserIcon,
        route: "/teacher/homeroom-classes",
    },
    {
        name: "Assessments",
        icon: ClipboardIcon,
        route: "/teacher/assessments",
    },
    {
        name: "Schedule",
        icon: CalendarDaysIcon,
        route: "/teacher/subjects",
    },
    {
        name: "Feedbacks",
        icon: ChatBubbleBottomCenterIcon,
        route: "/teacher/feedbacks",
    },
    {
        name: "Settings",
        icon: Cog6ToothIcon,
        route: "/teacher/settings",
    },
];

const footerItems = [
    { icon: ChatBubbleBottomCenterIcon, name: "Chat" },
    { icon: PowerIcon, name: "Logout" },
];

const isSideBarOpen = ref(false);
</script>

<style scoped></style>
