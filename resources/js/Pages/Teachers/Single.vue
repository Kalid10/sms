<template>
    <div class="flex w-full space-x-1.5">
        <div
            class="flex w-2/12 flex-col justify-between rounded-r-md bg-zinc-900 text-white lg:w-80"
        >
            <div>
                <div class="flex h-44 flex-col items-center justify-center">
                    <div
                        class="flex w-full items-center justify-center space-x-4 space-y-1 py-2 px-1"
                    >
                        <div class="h-full w-fit lg:w-24">
                            <img
                                :src="`https://xsgames.co/randomusers/avatar.php?g=${teacher.user.gender}`"
                                alt="avatar"
                                class="mx-auto h-full w-full rounded-md object-cover md:w-3/4"
                            />
                        </div>
                        <div class="flex flex-col space-y-2">
                            <Heading>{{ teacher.user.name }}</Heading>
                            <div class="flex space-x-1.5 text-sm font-light">
                                <EnvelopeIcon class="h-5" />
                                <span>{{ teacher.user.email }}</span>
                            </div>
                            <div class="flex space-x-1.5 text-sm font-light">
                                <PhoneIcon class="h-5" />
                                <span> 0943104396</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 space-y-2 lg:mt-10">
                    <div
                        v-for="(item, index) in sidebarItems"
                        :key="index"
                        class="flex h-20 w-full items-center justify-between"
                        :class="
                            index === 0
                                ? 'font-medium bg-zinc-800 rounded-lg'
                                : 'font-normal'
                        "
                    >
                        <div
                            class="flex h-20 w-full items-center justify-center"
                        >
                            <div
                                class="relative flex w-2/3 items-center text-center hover:cursor-pointer"
                            >
                                <div
                                    class="relative flex w-full items-center justify-between"
                                >
                                    <component :is="item.icon" class="h-6" />

                                    <div class="absolute inset-x-2">
                                        {{ item.name }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            :class="
                                index === 0
                                    ? 'h-full w-2 bg-neutral-50 pr-0.5 rounded-l-md'
                                    : ''
                            "
                        ></div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col items-center space-y-10 pb-10">
                <div
                    class="flex w-2/3 flex-col items-center space-y-10 text-center"
                >
                    <div
                        class="relative flex w-full items-center justify-between"
                    >
                        <ChatBubbleLeftRightIcon class="h-6" />
                        <div class="absolute inset-x-2">Support</div>
                    </div>
                    <div
                        class="relative flex w-full items-center justify-between"
                    >
                        <PowerIcon class="h-6" />
                        <div class="absolute inset-x-2">Logout</div>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="flex min-h-screen w-10/12 flex-col items-center bg-gray-50/30"
        >
            <div class="flex w-11/12 flex-col space-y-10 pt-3">
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
import Heading from "@/Components/Heading.vue";
// import Subjects from "@/Views/Teachers/Subjects.vue";
// import HomeroomClasses from "@/Views/Teachers/HomeroomClasses.vue";
import { usePage } from "@inertiajs/vue3";
import Assessments from "@/Views/Teachers/Assessments.vue";
import { HomeIcon } from "@heroicons/vue/24/solid";
import {
    CalendarDaysIcon,
    CalendarIcon,
    ChatBubbleBottomCenterIcon,
    ChatBubbleLeftRightIcon,
    ClipboardIcon,
    Cog6ToothIcon,
    EnvelopeIcon,
    PhoneIcon,
    PowerIcon,
    UserIcon,
} from "@heroicons/vue/20/solid";
import NextClass from "@/Views/Teachers/NextClass.vue";
import Students from "@/Views/Teachers/Students.vue";
import LessonPlans from "@/Views/Teachers/LessonPlans.vue";
import Grades from "@/Views/Teachers/Grades.vue";
import SchoolSchedule from "@/Views/Teachers/SchoolSchedule.vue";
import Feedbacks from "@/Views/Teachers/Feedbacks.vue";

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
</script>

<style scoped></style>
