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
                    class="flex flex-col items-center overflow-x-hidden bg-brand-50/50 lg:w-full"
                >
                    <slot />
                </div>
            </div>
        </div>

        <Notification class="!my-3" />

        <div
            v-if="isQuestionGenerationLoading || questionGenerationStatus"
            class="group absolute bottom-2 right-2 z-50 w-fit cursor-pointer text-white"
        >
            <div
                v-if="isQuestionGenerationLoading"
                class="my-2 flex w-fit items-center justify-center space-x-2 rounded-full bg-violet-600 px-3 py-2 text-xs"
            >
                <Loading size="small" type="spinner" />
                <div
                    class="hidden group-hover:inline-block group-hover:animate-fade-in group-hover:delay-700"
                >
                    Generating Questions
                </div>
            </div>

            <div
                v-if="questionGenerationStatus === 'success'"
                class="flex items-center justify-center space-x-4 rounded-lg bg-emerald-500 py-2 px-4 text-sm"
                @click="routeToQuestionsPage()"
            >
                <span> Generated Questions Successfully!</span>
                <SecondaryButton
                    title="View"
                    class="cursor-pointer !rounded-2xl bg-emerald-100 !py-1.5 !px-6 hover:scale-105"
                    @click="
                        uiStore.setQuestionGenerationLoading(false);
                        uiStore.setQuestionGenerationStatus(null);
                    "
                />
            </div>
            <div
                v-if="questionGenerationStatus === 'error'"
                class="flex items-center justify-center space-x-2 rounded-lg bg-red-600 py-2 px-4 text-sm"
                @click="routeToQuestionsPage()"
            >
                <XCircleIcon class="w-6 text-white" />
                <span>{{ questionGenerationMessage }}</span>
            </div>
        </div>
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

const directory = computed(() => usePage().url.split("/")[2]);

// Populate sidebar items
const sidebarItems = computed(() => [
    {
        name: t("teacherLayout.home"),
        icon: HomeIcon,
        route: "/teacher",
        active: directory.value === undefined,
    },
    {
        name: t("common.chat"),
        icon: ChatBubbleBottomCenterIcon,
        route: "/teacher/chat",
        active: directory.value === "chat",
    },
    {
        name: t("teacherLayout.myClasses"),
        icon: PuzzlePieceIcon,
        route: "/teacher/class",
        active: directory.value === "class",
    },
    {
        name: t("teacherLayout.myStudents"),
        icon: UsersIcon,
        route: "/teacher/students",
        active: directory.value === "students",
    },
    {
        name: t("teacherLayout.lessonPlan"),
        icon: CalendarIcon,
        route: "/teacher/lesson-plan",
        active: directory.value === "lesson-plan",
    },
    {
        name: t("teacherLayout.questionBank"),
        icon: QuestionMarkCircleIcon,
        route: "/teacher/questions",
        active: directory.value === "questions",
    },
    {
        name: t("teacherLayout.assessments"),
        icon: NewspaperIcon,
        route: "/teacher/assessments",
        active: directory.value === "assessments",
    },
    {
        name: t("common.copilot"),
        icon: SparklesIcon,
        route: "/teacher/copilot",
        active: directory.value === "copilot",
    },
    {
        name: t("teacherLayout.homeRooms"),
        icon: UserIcon,
        route: "/teacher/homeroom",
        active: directory.value === "homeroom",
    },

    {
        name: t("teacherLayout.announcements"),
        icon: MegaphoneIcon,
        route: "/teacher/announcements",
        active: directory.value === "announcements",
    },

    {
        name: t("teacherLayout.schedule"),
        icon: CalendarDaysIcon,
        route: "/teacher/school-schedule",
        active: directory.value === "school-schedule",
    },
]);

const footerItems = [
    {
        name: t("teacherLayout.settings"),
        icon: Cog6ToothIcon,
        route: "/user/profile",
        active: directory.value === "settings",
    },
    {
        icon: ArrowLeftOnRectangleIcon,
        name: t("teacherLayout.logout"),
        route: "/logout",
        method: "POST",
    },
];

const uiStore = useUIStore();
const isQuestionGenerationLoading = computed(
    () => uiStore.isQuestionGenerationLoading
);
const questionGenerationStatus = computed(
    () => uiStore.questionGenerationStatus
);
const questionGenerationMessage = computed(
    () => uiStore.questionGenerationMessage
);
const routeToQuestionsPage = () => {
    router.get("/teacher/questions", {}, { preserveState: true });
};
</script>

<style scoped></style>
