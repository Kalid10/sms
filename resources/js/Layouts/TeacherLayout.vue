<template>
    <div id="top-view"></div>

    <div class="relative flex max-h-screen w-full flex-col">
        <div class="hide-scrollbar w-full grow overflow-y-auto bg-white p-0">
            <div class="flex w-full">
                <SideBar
                    v-model:open="isOpen"
                    class="sticky top-0"
                    :header="auth"
                    :main-items="sidebarItems || []"
                    :footer-items="footerItems"
                />
                <div
                    :class="
                        isOpen ? 'min-w-full lg:min-w-0 lg:blur-0' : 'w-full'
                    "
                    class="flex flex-col items-center overflow-x-hidden bg-gray-50/50 lg:w-full"
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
import { computed, provide, ref } from "vue";
import { HomeIcon } from "@heroicons/vue/24/solid/index.js";
import {
    ArrowLeftOnRectangleIcon,
    CalendarDaysIcon,
    CalendarIcon,
    ChatBubbleBottomCenterIcon,
    ClipboardIcon,
    Cog6ToothIcon,
    PuzzlePieceIcon,
    UserIcon,
    UsersIcon,
} from "@heroicons/vue/20/solid/index.js";
import { usePage } from "@inertiajs/vue3";
import { useSidebarStore } from "@/Store/sidebar";

const props = defineProps({
    auth: {
        type: Object,
        required: true,
    },
});
const notificationData = ref(null);

const showNotification = (data) => {
    notificationData.value = data;
};

// TODO: Migrate the two providers
provide("showNotification", showNotification);
provide("notificationData", notificationData);

const isOpen = computed(() => useSidebarStore().isOpen);

const directory = computed(() => usePage().url.split("/")[2]);

// Populate sidebar items
const sidebarItems = computed(() => [
    {
        name: "Home",
        icon: HomeIcon,
        route: "/teacher",
        active: directory.value === undefined,
    },
    {
        name: "My Classes",
        icon: PuzzlePieceIcon,
        route: "/teacher/class",
        active: directory.value === "class",
    },
    {
        name: "My Students",
        icon: UsersIcon,
        route: "/teacher/students",
        active: directory.value === "students",
    },
    {
        name: "Lesson Plan",
        icon: CalendarIcon,
        route: "/teacher/lesson-plan",
        active: directory.value === "lesson-plan",
    },
    {
        name: "Assessments",
        icon: ClipboardIcon,
        route: "/teacher/assessments",
        active: directory.value === "assessments",
    },
    {
        name: "Homerooms",
        icon: UserIcon,
        route: "/teacher/homeroom",
        active: directory.value === "homeroom",
    },

    {
        name: "Announcements",
        icon: ChatBubbleBottomCenterIcon,
        route: "/teacher/announcements",
        active: directory.value === "announcements",
    },

    {
        name: "Schedule",
        icon: CalendarDaysIcon,
        route: "/teacher/school-schedule",
        active: directory.value === "school-schedule",
    },

    {
        name: "Settings",
        icon: Cog6ToothIcon,
        route: "/teacher/settings",
        active: directory.value === "settings",
    },
]);

const footerItems = [
    {
        icon: ArrowLeftOnRectangleIcon,
        name: "Logout",
        route: "/logout",
        method: "POST",
    },
];
</script>

<style scoped></style>
