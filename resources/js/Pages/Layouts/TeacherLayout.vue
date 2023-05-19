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
                    class="flex flex-col items-center overflow-x-hidden lg:w-full"
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
import SideBar from "@/Pages/Layouts/SideBar.vue";
import { computed } from "vue";
import { HomeIcon } from "@heroicons/vue/24/solid/index.js";
import {
    CalendarDaysIcon,
    CalendarIcon,
    ChatBubbleBottomCenterIcon,
    ClipboardIcon,
    Cog6ToothIcon,
    PowerIcon,
    UserIcon,
} from "@heroicons/vue/20/solid/index.js";
import { usePage } from "@inertiajs/vue3";
import { useSidebarStore } from "@/Store/sidebar";

const props = defineProps({
    auth: {
        type: Object,
        required: true,
    },
});

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
        icon: UserIcon,
        route: "/teacher/class",
        active: directory.value === "class",
    },
    {
        name: "My Students",
        icon: UserIcon,
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
        route: "/teacher/homeroom-classes",
        active: directory.value === "homerooms",
    },

    {
        name: "Schedule",
        icon: CalendarDaysIcon,
        route: "/teacher/subjects",
        active: directory.value === "subjects",
    },
    {
        name: "Feedbacks",
        icon: ChatBubbleBottomCenterIcon,
        route: "/teacher/feedbacks",
        active: directory.value === "feedbacks",
    },
    {
        name: "Settings",
        icon: Cog6ToothIcon,
        route: "/teacher/settings",
        active: directory.value === "settings",
    },
]);

const footerItems = [
    { icon: PowerIcon, name: "Logout", route: "/logout", method: "POST" },
];
</script>

<style scoped></style>
