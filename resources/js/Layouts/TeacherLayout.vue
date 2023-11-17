<template>
    <div id="top-view"></div>

    <div class="flex max-h-screen w-full flex-col p-0">
        <div
            class="hide-scrollbar min-h-screen grow overflow-y-auto bg-white p-0"
        >
            <div class="flex w-full">
                <SideBar
                    v-model:open="isOpen"
                    class="sticky top-0"
                    :header="auth"
                    :main-items="sidebarItems || []"
                    :footer-items="footerItems"
                    @show-logout-confirmation="showLogoutConfirmation"
                />
                <div
                    :class="
                        isOpen ? 'min-w-full lg:min-w-0 lg:blur-0' : 'w-full'
                    "
                    class="flex flex-col items-center overflow-x-hidden bg-brand-50/50 lg:w-full"
                >
                    <slot />
                </div>
            </div>
        </div>

        <Notification class="!my-3" />

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
                class="flex items-center justify-center space-x-4 rounded-lg bg-emerald-500 px-4 py-2 text-sm"
                @click="routeToQuestionsPage()"
            >
                <span> {{ storeResponseMessage }}</span>
                <SecondaryButton
                    title="View"
                    class="cursor-pointer !rounded-2xl bg-emerald-100 !px-6 !py-1.5 hover:scale-105"
                    @click="
                        uiStore.setLoading(false);
                        uiStore.setResponse(null);
                    "
                />
            </div>
            <div
                v-if="storeResponseStatus === 'error'"
                class="flex items-center justify-center space-x-2 rounded-lg bg-red-600 px-4 py-2 text-sm"
                @click="routeToQuestionsPage()"
            >
                <XCircleIcon class="w-6 text-white" />
                <span>{{ storeResponseMessage }}</span>
            </div>
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
            {{ t("teacherLayout.logout") }}
        </template>
        <template #description>
            {{ t("common.logoutConfirmation") }}
        </template>
        <template #action>
            {{ t("teacherLayout.logout") }}
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
import { HomeIcon } from "@heroicons/vue/24/solid/index.js";
import {
    ArrowLeftOnRectangleIcon,
    CalendarDaysIcon,
    CalendarIcon,
    Cog6ToothIcon,
    MegaphoneIcon,
    NewspaperIcon,
    PuzzlePieceIcon,
    QuestionMarkCircleIcon,
    SparklesIcon,
    UserIcon,
    UsersIcon,
    XCircleIcon,
} from "@heroicons/vue/20/solid/index.js";
import { router, usePage } from "@inertiajs/vue3";
import { useSidebarStore } from "@/Store/sidebar";
import Loading from "@/Components/Loading.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { useUIStore } from "@/Store/ui";

import { useI18n } from "vue-i18n";
import DialogBox from "@/Components/DialogBox.vue";
import { FaceFrownIcon } from "@heroicons/vue/24/solid";

const { t } = useI18n();
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
const isLoading = ref(false);
const directory = computed(() => usePage().url.split("/")[2]);

const isLogoutDialogOpen = ref(false);

const showLogoutConfirmation = () => {
    isLogoutDialogOpen.value = true;
};

const handleLogoutConfirm = () => {
    isLoading.value = true;
    logout();
    isLogoutDialogOpen.value = false;
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

const isRouteActive = (routePattern) => {
    const currentURL = usePage().url.split("?")[0]; // Strip query parameters
    return routePattern.test(currentURL);
};

const sidebarItems = computed(() => [
    {
        name: t("teacherLayout.home"),
        icon: HomeIcon,
        route: "/teacher",
        active:
            isRouteActive(/^\/teacher\/\d+\/?$/) ||
            isRouteActive(/^\/teacher\/?$/),
    },
    {
        name: t("teacherLayout.myClasses"),
        icon: PuzzlePieceIcon,
        route: "/teacher/class",
        active:
            isRouteActive(/^\/teacher\/class\/\d+\/?$/) ||
            isRouteActive(/^\/teacher\/class\/?$/),
    },
    {
        name: t("teacherLayout.myStudents"),
        icon: UsersIcon,
        route: "/teacher/students",
        active:
            isRouteActive(/^\/teacher\/students\/\d+\/?$/) ||
            isRouteActive(/^\/teacher\/students\/?$/),
    },
    {
        name: t("teacherLayout.lessonPlan"),
        icon: CalendarIcon,
        route: "/teacher/lesson-plan",
        active:
            isRouteActive(/^\/teacher\/lesson-plan\/\d+\/?$/) ||
            isRouteActive(/^\/teacher\/lesson-plan\/?$/),
    },
    {
        name: t("teacherLayout.questionBank"),
        icon: QuestionMarkCircleIcon,
        route: "/teacher/questions",
        active:
            isRouteActive(/^\/teacher\/questions\/\d+\/?$/) ||
            isRouteActive(/^\/teacher\/questions\/?$/),
    },
    {
        name: t("teacherLayout.assessments"),
        icon: NewspaperIcon,
        route: "/teacher/assessments",
        active:
            isRouteActive(/^\/teacher\/assessments\/\d+\/?$/) ||
            isRouteActive(/^\/teacher\/assessments\/?$/) ||
            isRouteActive(/^\/teacher\/assessments\/mark\/\d+\/?$/) ||
            isRouteActive(/^\/teacher\/assessments\/mark\/\d+\/?$/),
    },
    {
        name: t("common.copilot"),
        icon: SparklesIcon,
        route: "/teacher/copilot",
        active:
            isRouteActive(/^\/teacher\/copilot\/\d+\/?$/) ||
            isRouteActive(/^\/teacher\/copilot\/?$/),
    },
    {
        name: t("teacherLayout.homeRooms"),
        icon: UserIcon,
        route: "/teacher/homeroom",
        active:
            isRouteActive(/^\/teacher\/homeroom\/\d+\/?$/) ||
            isRouteActive(/^\/teacher\/homeroom\/?$/),
    },

    {
        name: t("teacherLayout.announcements"),
        icon: MegaphoneIcon,
        route: "/teacher/announcements",
        active:
            isRouteActive(/^\/teacher\/announcements\/\d+\/?$/) ||
            isRouteActive(/^\/teacher\/announcements\/?$/),
    },

    {
        name: t("teacherLayout.schedule"),
        icon: CalendarDaysIcon,
        route: "/teacher/school-schedule",
        active:
            isRouteActive(/^\/teacher\/school-schedule\/\d+\/?$/) ||
            isRouteActive(/^\/teacher\/school-schedule\/?$/),
    },
    {
        name: t("common.extras"),
        icon: PuzzlePieceIcon,
        route: "/teacher/extras",
        active:
            isRouteActive(/^\/teacher\/extras\/\d+\/?$/) ||
            isRouteActive(/^\/teacher\/extras\/?$/),
    },
    {
        name: t("teacherLayout.settings"),
        icon: Cog6ToothIcon,
        route: "/teacher/user/profile",
        active: directory.value === "user",
    },
]);

const footerItems = [
    {
        icon: ArrowLeftOnRectangleIcon,
        name: t("teacherLayout.logout"),
        action: "showLogoutConfirmation",
    },
];

const uiStore = useUIStore();
const isStoreLoading = computed(() => uiStore.isLoading);
const storeResponseStatus = computed(() => uiStore.responseStatus);
const storeResponseMessage = computed(() => uiStore.responseMessage);
const storeLoadingMessage = computed(() => uiStore.loadingMessage);

const routeToQuestionsPage = () => {
    router.get("/teacher/questions", {}, { preserveState: true });
};
</script>

<style scoped></style>
