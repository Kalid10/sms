<template>
    <div class="flex w-full">
        <div
            class="bg-zinc-800 text-white"
            :class="[
                isSideBarOpen ? 'w-7/12 lg:w-80' : 'w-1/12 lg:w-20',
                'transition-all duration-300 ease-in-out',
                isSideBarOpen ? 'backdrop-blur-md' : '',
            ]"
            @click="isSideBarOpen = !isSideBarOpen"
        >
            <SideBar
                class="sticky top-0"
                :header="teacher"
                :main-items="sidebarItems"
                :footer-items="footerItems"
                :is-open="isSideBarOpen"
            />
        </div>

        <div
            class="flex w-full flex-col items-center"
            :class="isSideBarOpen ? 'w-5/12 lg:w-10/12' : 'w-10/12 lg:w-full'"
        >
            <div class="flex w-full flex-col space-y-10 pt-3 lg:px-5">
                <div
                    class="flex items-center rounded-lg py-7 text-4xl font-light"
                >
                    Welcome back,

                    <span class="ml-2 font-semibold"
                        >{{ teacher.user.name }}!</span
                    >
                </div>
                <div class="flex w-full justify-between">
                    <div class="w-6/12">
                        <Assessments class="" />
                    </div>
                    <div class="w-5/12">
                        <NextClass />
                    </div>
                </div>
                <div class="flex h-full w-full justify-between pt-8">
                    <Students class="w-3/12" />
                    <LessonPlans class="w-4/12" />

                    <div class="flex h-full w-4/12 flex-col justify-between">
                        <div class="w-full">
                            <Grades />
                        </div>

                        <div class="mt-5 flex w-full justify-between">
                            <div class="w-6/12">
                                <div class="w-full text-center font-medium">
                                    Feedbacks
                                </div>
                                <Feedbacks />
                            </div>
                            <div class="flex w-5/12 flex-col items-center">
                                <div
                                    class="mb-4 w-full text-center font-medium"
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
