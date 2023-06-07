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
                        openSideBar
                            ? 'min-w-full lg:min-w-0 lg:blur-0'
                            : ''
                    "
                    class="m-5 flex flex-col items-center overflow-x-hidden lg:w-full"
                >
                    <slot/>
                </div>
            </div>
        </div>
        <Notification/>
    </div>
</template>

<script setup>
import Notification from "@/Components/Notification.vue";
import SideBar from "@/Layouts/SideBar.vue";
import {computed, ref} from "vue";
import {ChatBubbleBottomCenterIcon, Cog6ToothIcon, PowerIcon, UserIcon,} from "@heroicons/vue/20/solid/index.js";
import {usePage} from "@inertiajs/vue3";
import {BookOpenIcon, QueueListIcon, UsersIcon,} from "@heroicons/vue/24/solid";

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
        name: "Dashboard",
        icon: QueueListIcon,
        route: "/admin",
        active: directory.value === undefined,
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
        name: "Subjects",
        icon: BookOpenIcon,
        route: "/admin/subjects",
        active: directory.value === "subjects",
    },
    {
        name: "Schedule",
        icon: QueueListIcon,
        route: "/admin/schedules",
        active: directory.value === "students",
    },
    {
        name: "Settings",
        icon: Cog6ToothIcon,
        route: "/user/profile",
        // active: directory.value === 'settings'
    },
]);

const footerItems = [
    {icon: ChatBubbleBottomCenterIcon, name: "Chat"},
    {icon: PowerIcon, name: "Logout"},
];
</script>

<style scoped></style>
