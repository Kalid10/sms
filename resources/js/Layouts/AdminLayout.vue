<template>
    <div id="top-view"></div>

    <div class="scrollbar-hide relative flex h-screen w-full flex-col">
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
                    @show-logout-confirmation="showLogoutConfirmation"
                />
                <div
                    :class="
                        openSideBar ? 'min-w-full lg:min-w-0 lg:blur-0' : ''
                    "
                    class="scrollbar-hide flex flex-col items-center overflow-x-hidden p-2 lg:w-full"
                >
                    <slot />
                </div>
            </div>
        </div>
        <Notification />
    </div>

    <div
        v-if="isStoreLoading || storeResponseStatus"
        class="group absolute bottom-2 right-2 z-50 w-fit cursor-pointer text-white"
    >
        <div
            v-if="isStoreLoading"
            class="my-2 flex w-fit items-center justify-center space-x-2 rounded-full bg-violet-600 px-3 py-2 text-xs"
        >
            <Loading size="small" type="spinner" />
            <div
                class="hidden group-hover:inline-block group-hover:animate-fade-in group-hover:delay-700"
            >
                {{ storeLoadingMessage }}
            </div>
        </div>

        <div
            v-if="storeResponseStatus === 'success'"
            class="flex items-center justify-center space-x-4 rounded-lg bg-emerald-500 px-4 py-3 text-sm"
        >
            <CheckCircleIcon class="w-5 text-white" />
            <span> {{ storeResponseMessage }}</span>
        </div>
        <div
            v-if="storeResponseStatus === 'error'"
            class="flex items-center justify-center space-x-2 rounded-lg bg-red-600 px-4 py-3 text-sm"
        >
            <XCircleIcon class="w-5 text-white" />
            <span>{{ storeResponseMessage }}</span>
        </div>
    </div>

    <DialogBox
        :open="isLogoutDialogOpen"
        @abort="isLogoutDialogOpen = false"
        @confirm="handleLogoutConfirm"
    >
        <template #icon>
            <ArrowLeftOnRectangleIcon />
        </template>

        <template #title>
            {{ t("adminLayout.logout") }}
        </template>
        <template #description>
            {{ t("common.logoutConfirmation") }}
        </template>
        <template #action>
            {{ t("adminLayout.logout") }}
        </template>
    </DialogBox>

    <Loading v-if="isLoading" :is-full-screen="true" type="bounce">
        <template #description>
            <div
                class="flex flex-col items-center justify-center space-y-2 rounded-lg bg-brand-350 p-5 text-brand-150 shadow-md"
            >
                <FaceFrownIcon class="w-6 animate-bounce" />
                <div class="text-sm font-medium capitalize">
                    Logging you out. See you Soon!
                </div>
            </div>
        </template>
    </Loading>
</template>

<script setup>
import Notification from "@/Components/Notification.vue";
import SideBar from "@/Layouts/SideBar.vue";
import { computed, provide, ref } from "vue";
import { Cog6ToothIcon, UserIcon } from "@heroicons/vue/20/solid/index.js";
import { router, usePage } from "@inertiajs/vue3";
import {
    AcademicCapIcon,
    BanknotesIcon,
    BookOpenIcon,
    CalendarDaysIcon,
    CircleStackIcon,
    FaceFrownIcon,
    FingerPrintIcon,
    HomeIcon,
    MegaphoneIcon,
    NewspaperIcon,
    TableCellsIcon,
    UserGroupIcon,
    UsersIcon,
} from "@heroicons/vue/24/solid";
import {
    ArrowLeftOnRectangleIcon,
    CheckCircleIcon,
    XCircleIcon,
} from "@heroicons/vue/20/solid/index";
import { useI18n } from "vue-i18n";
import DialogBox from "@/Components/DialogBox.vue";
import Loading from "@/Components/Loading.vue";
import { useUIStore } from "@/Store/ui";

const { t } = useI18n();
const props = defineProps({
    auth: {
        type: Object,
        required: true,
    },
});

const openSideBar = ref(true);
const directory = computed(() => usePage().url.split("/")[2]);

const isLoading = ref(false);
const isLogoutDialogOpen = ref(false);

const showLogoutConfirmation = () => {
    isLogoutDialogOpen.value = true;
};

const handleLogoutConfirm = () => {
    isLoading.value = true;
    logout();
    isLogoutDialogOpen.value = false;
};

const isRouteActive = (routePattern) => {
    const currentURL = usePage().url.split("?")[0]; // Strip query parameters
    return routePattern.test(currentURL);
};

const logout = () => {
    router.post(
        "/logout",
        {},
        {
            onFinish: () => {
                isLoading.value = false;
            },
        }
    );
};

// Populate sidebar items
const sidebarItems = computed(() => [
    {
        name: t("adminLayout.home"),
        icon: HomeIcon,
        route: "/admin",
        active: isRouteActive(/^\/admin\/\?$/) || isRouteActive(/^\/admin\/?$/),
    },
    {
        name: t("common.teachers"),
        icon: UserIcon,
        route: "/admin/teachers",
        active:
            isRouteActive(/^\/admin\/teachers\/\d+\/?$/) ||
            isRouteActive(/^\/admin\/teachers\/?$/) ||
            isRouteActive(/^\/admin\/teachers\/class\/?$/) ||
            isRouteActive(/^\/admin\/teachers\/students\/?$/) ||
            isRouteActive(/^\/admin\/teachers\/lesson-plan\/?$/) ||
            isRouteActive(/^\/admin\/teachers\/assessments\/?$/) ||
            isRouteActive(/^\/teacher\/assessments\/?$/) ||
            isRouteActive(/^\/admin\/teachers\/homeroom\/?$/) ||
            isRouteActive(/^\/admin\/teachers\/announcements\/?$/),
    },
    {
        name: t("common.students"),
        icon: UsersIcon,
        route: "/admin/students",
        active:
            isRouteActive(/^\/admin\/students\/\d+\/?$/) ||
            isRouteActive(/^\/admin\/students\/?$/) ||
            isRouteActive(/^\/admin\/teachers\/students\/\d+\/?$/) ||
            // isRouteActive(/^\/admin\/teachers\/students\/?$/) ||
            isRouteActive(/^\/students\/\d+\/?$/) ||
            isRouteActive(/^\/teacher\/students\/\d+\/?$/) ||
            isRouteActive(/^\/teacher\/students\/?$/) ||
            isRouteActive(/^\/admin\/batches\/\d+\/?$/) ||
            isRouteActive(/^\/admin\/batches\/?$/),
    },
    {
        name: t("common.grades"),
        icon: AcademicCapIcon,
        route: "/admin/levels",
        active:
            isRouteActive(/^\/admin\/levels\/\d+\/?$/) ||
            isRouteActive(/^\/admin\/levels\/?$/) ||
            isRouteActive(/^\/levels\/level-categories\/\d+\/?$/) ||
            isRouteActive(/^\/levels\/level-categories\/?$/) ||
            isRouteActive(/^\/levels\/assessments\/\d+\/?$/) ||
            isRouteActive(/^\/levels\/ssessments\/?$/),
    },
    {
        name: t("common.subjects"),
        icon: BookOpenIcon,
        route: "/admin/subjects",
        active:
            isRouteActive(/^\/admin\/subjects\/\d+\/?$/) ||
            isRouteActive(/^\/admin\/subjects\/?$/) ||
            isRouteActive(/^\/batches\/subjects\/\d+\/\d+\/?$/) ||
            isRouteActive(/^\/batches\/subjects\/?$/),
    },
    {
        name: t("common.announcements"),
        icon: MegaphoneIcon,
        route: "/admin/announcements",
        active:
            isRouteActive(/^\/admin\/announcements\/\d+\/?$/) ||
            isRouteActive(/^\/admin\/announcements\/?$/),
    },
    {
        name: t("common.schedule"),
        icon: CalendarDaysIcon,
        route: "/admin/schedules",
        active:
            isRouteActive(/^\/admin\/schedules\/\d+\/?$/) ||
            isRouteActive(/^\/admin\/schedules\/?$/),
    },
    {
        name: t("common.timetable"),
        icon: TableCellsIcon,
        route: "/admin/batch-schedules",
        active:
            isRouteActive(/^\/admin\/batch-schedules\/\d+\/?$/) ||
            isRouteActive(/^\/admin\/batch-schedules\/?$/),
    },
    {
        name: t("common.assessments"),
        icon: NewspaperIcon,
        route: "/admin/assessments",
        active:
            isRouteActive(/^\/admin\/assessments\/\d+\/?$/) ||
            isRouteActive(/^\/admin\/assessments\/?$/),
    },
    {
        name: t("common.users"),
        icon: UserGroupIcon,
        route: "/admin/users",
        active:
            isRouteActive(/^\/admin\/users\/\d+\/?$/) ||
            isRouteActive(/^\/admin\/users\/?$/) ||
            isRouteActive(/^\/admin\/user\/register\/admin\/\d+\/?$/) ||
            isRouteActive(/^\/admin\/user\/register\/admin\/?$/) ||
            isRouteActive(/^\/admin\/user\/register\/teacher\/\d+\/?$/) ||
            isRouteActive(/^\/admin\/user\/register\/teacher\/?$/) ||
            isRouteActive(/^\/admin\/user\/register\/student\/\d+\/?$/) ||
            isRouteActive(/^\/admin\/user\/register\/student\/?$/) ||
            isRouteActive(/^\/admin\/user\/\d+\/?$/) ||
            isRouteActive(/^\/admin\/user\/?$/),
    },
    {
        name: t("common.absentees"),
        icon: FingerPrintIcon,
        route: "/admin/absentees",
        active:
            isRouteActive(/^\/admin\/absentees\/\d+\/?$/) ||
            isRouteActive(/^\/admin\/absentees\/?$/),
    },
    {
        name: t("adminLayout.finance"),
        icon: BanknotesIcon,
        route: "/admin/fees",
        active:
            isRouteActive(/^\/admin\/fees\/\d+\/?$/) ||
            isRouteActive(/^\/admin\/fees\/?$/),
    },
    {
        name: t("common.inventory"),
        icon: CircleStackIcon,
        route: "/admin/inventory",
        active:
            isRouteActive(/^\/admin\/inventory\/\d+\/?$/) ||
            isRouteActive(/^\/admin\/inventory\/?$/),
    },
    {
        name: t("adminLayout.settings"),
        icon: Cog6ToothIcon,
        route: "/admin/user/profile",
        active:
            isRouteActive(/^\/admin\/user\/profile\/\d+\/?$/) ||
            isRouteActive(/^\/admin\/user\/profile\/?$/),
    },
]);

const footerItems = [
    {
        icon: ArrowLeftOnRectangleIcon,
        name: t("adminLayout.logout"),
        action: "showLogoutConfirmation",
    },
];

const notificationData = ref(null);

const showNotification = (data) => {
    notificationData.value = data;
};

// TODO: Migrate the two providers
provide("showNotification", showNotification);
provide("notificationData", notificationData);

const uiStore = useUIStore();
const isStoreLoading = computed(() => uiStore.isLoading);
const storeResponseStatus = computed(() => uiStore.responseStatus);
const storeResponseMessage = computed(() => uiStore.responseMessage);
const storeLoadingMessage = computed(() => uiStore.loadingMessage);

// TODO:: Move websocket listeners to a separate file
Echo.private("mass-assessment").listen(".mass-assessment", (e) => {
    uiStore.setLoading(false);

    if (e.type === "success") {
        uiStore.setResponse("success", "Assessments created successfully!");
    }

    if (e.type === "error") {
        showNotification({
            type: "error",
            message: e.message,
            position: "top-center",
        });
        uiStore.setResponse("error", e.message);
    }

    setTimeout(() => {
        uiStore.setResponse(null, null);
    }, 8000);
});

Echo.private("student-fee").listen(".student-fee", (e) => {
    uiStore.setLoading(false);

    if (e.type === "success") {
        uiStore.setResponse("success", e.message);
    }

    if (e.type === "error") {
        showNotification({
            type: "error",
            message: e.message,
            position: "top-center",
        });
        uiStore.setResponse("error", e.message);
    }

    setTimeout(() => {
        uiStore.setResponse(null, null);
    }, 8000);
});

Echo.private("batch-schedule").listen(".batch-schedule", (e) => {
    uiStore.setLoading(false);

    if (e.type === "success") {
        uiStore.setResponse("success", e.message);
    }

    if (e.type === "error") {
        showNotification({
            type: "error",
            message: e.message,
            position: "top-center",
        });
        uiStore.setResponse("error", e.message);
    }

    setTimeout(() => {
        uiStore.setResponse(null, null);
    }, 8000);
});
</script>

<style scoped></style>
