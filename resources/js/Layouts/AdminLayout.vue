<template>
    <div id="top-view"></div>

    <div class="relative flex h-screen w-full flex-col">
        <div class="hide-scrollbar w-full grow overflow-y-auto bg-white p-0">
            <div class="flex w-full">
                <SideBar
                    v-model:open="openSideBar"
                    class="sticky top-0 h-screen"
                    :header="auth"
                    :main-items="sidebarItems || []"
                    :footer-items="footerItems"
                />
                <div
                    :class="
                        openSideBar ? 'min-w-full lg:min-w-0 lg:blur-0' : ''
                    "
                    class="flex flex-col items-center overflow-x-hidden p-2 lg:w-full"
                >
                    <slot />
                </div>
            </div>
        </div>
        <Notification />
    </div>
</template>

<script setup>
import Notification from "@/Components/Notification.vue";
import SideBar from "@/Layouts/SideBar.vue";
import { computed, ref } from "vue";
import {
    ChatBubbleBottomCenterIcon,
    Cog6ToothIcon,
    UserIcon,
} from "@heroicons/vue/20/solid/index.js";
import { usePage } from "@inertiajs/vue3";
import { BookOpenIcon, UsersIcon } from "@heroicons/vue/24/solid";
import {
    ArrowLeftOnRectangleIcon,
    CalendarDaysIcon,
} from "@heroicons/vue/20/solid/index";
import { HomeIcon } from "@heroicons/vue/24/solid/index";
import { UserGroupIcon } from "@heroicons/vue/24/outline";

const props = defineProps({
    auth: {
        type: Object,
        required: true,
    },
});

const openSideBar = ref(true);
const directory = computed(() => usePage().url.split("/")[2]);

// Populate sidebar items
const sidebarItems = computed(() => [
    {
        name: "Home",
        icon: HomeIcon,
        route: "/admin",
        active: directory.value === undefined,
    },
    {
        name: "Chat",
        icon: ChatBubbleBottomCenterIcon,
        route: "/admin/chat",
        active: directory.value === "chat",
    },
    {
        name: "Teachers",
        icon: UserIcon,
        route: "/admin/teachers",
        active: directory.value === "teachers",
    },
    {
        name: "Students",
        icon: UsersIcon,
        route: "/admin/students",
        active: directory.value === "students",
    },
    {
        name: "Grades",
        icon: UsersIcon,
        route: "/admin/levels",
        active: directory.value === "levels",
    },
    {
        name: "Subjects",
        icon: BookOpenIcon,
        route: "/admin/subjects",
        active: directory.value === "subjects",
    },
    {
        name: "Announcements",
        icon: ChatBubbleBottomCenterIcon,
        route: "/admin/announcements",
        active: directory.value === "announcements",
    },
    {
        name: "Schedule",
        icon: CalendarDaysIcon,
        route: "/admin/schedules",
        active: directory.value === "schedules",
    },
    {
        name: "Users",
        icon: UserGroupIcon,
        route: "/admin/users",
        active: directory.value === "users",
    },
]);

const footerItems = [
    {
        name: "Settings",
        icon: Cog6ToothIcon,
        route: "/user/profile",
        // active: directory.value === 'settings'
    },
    {
        icon: ArrowLeftOnRectangleIcon,
        name: "Logout",
        route: "/logout",
        method: "POST",
    },
];
</script>

<style scoped></style>
