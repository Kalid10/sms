<template>
    <div id="top-view"></div>

    <div class="relative flex h-screen w-full flex-col">
        <div
            class="hide-scrollbar w-full grow overflow-y-auto bg-brand-50/30 p-0"
        >
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
import { computed, provide, ref } from "vue";
import {
    ChatBubbleBottomCenterIcon,
    Cog6ToothIcon,
    UserIcon,
} from "@heroicons/vue/20/solid/index.js";
import { usePage } from "@inertiajs/vue3";
import {
    AcademicCapIcon,
    BookOpenIcon,
    CalendarDaysIcon,
    FingerPrintIcon,
    HomeIcon,
    MegaphoneIcon,
    NewspaperIcon,
    UserGroupIcon,
    UsersIcon,
} from "@heroicons/vue/24/solid";
import { ArrowLeftOnRectangleIcon } from "@heroicons/vue/20/solid/index";
import { useI18n } from "vue-i18n";

const { t } = useI18n();
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
        name: t("adminLayout.home"),
        icon: HomeIcon,
        route: "/admin",
        active: directory.value === undefined,
    },
    {
        name: t("common.chat"),
        icon: ChatBubbleBottomCenterIcon,
        route: "/admin/chat",
        active: directory.value === "chat",
    },
    {
        name: t("common.teachers"),
        icon: UserIcon,
        route: "/admin/teachers",
        active: directory.value === "teachers",
    },
    {
        name: t("common.students"),
        icon: UsersIcon,
        route: "/admin/students",
        active: directory.value === "students",
    },
    {
        name: t("common.grades"),
        icon: AcademicCapIcon,
        route: "/admin/levels",
        active: directory.value === "levels",
    },
    {
        name: t("common.subjects"),
        icon: BookOpenIcon,
        route: "/admin/subjects",
        active: directory.value === "subjects",
    },
    {
        name: t("common.announcements"),
        icon: MegaphoneIcon,
        route: "/admin/announcements",
        active: directory.value === "announcements",
    },
    {
        name: t("common.schedule"),
        icon: CalendarDaysIcon,
        route: "/admin/schedules",
        active: directory.value === "schedules",
    },
    {
        name: t("common.assessments"),
        icon: NewspaperIcon,
        route: "/admin/assessments",
        active: directory.value === "assessments",
    },
    {
        name: t("common.users"),
        icon: UserGroupIcon,
        route: "/admin/users",
        active: directory.value === "users",
    },
    {
        name: t("common.absentees"),
        icon: FingerPrintIcon,
        route: "/admin/absentees",
        active: directory.value === "absentees",
    },
]);

const footerItems = [
    {
        name: t("adminLayout.settings"),
        icon: Cog6ToothIcon,
        route: "/user/profile",
        // active: directory.value === 'settings'
    },
    {
        icon: ArrowLeftOnRectangleIcon,
        name: t("adminLayout.logout"),
        route: "/logout",
        method: "POST",
    },
];

const notificationData = ref(null);

const showNotification = (data) => {
    notificationData.value = data;
};

// TODO: Migrate the two providers
provide("showNotification", showNotification);
provide("notificationData", notificationData);
</script>

<style scoped></style>
