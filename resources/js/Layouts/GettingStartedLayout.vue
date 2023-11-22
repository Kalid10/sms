<template>
    <div id="top-view"></div>

    <div
        v-if="
            activePage === '/getting-started/school-schedule' ||
            activePage === '/getting-started/class-schedule' ||
            activePage === '/login' ||
            activePage === '/signup'
        "
        class="relative flex h-screen w-full flex-col"
    >
        <slot />
    </div>
    <div v-else class="relative flex h-screen max-h-screen w-full flex-col">
        <Header />
        <div class="grow overflow-y-auto lg:w-full">
            <slot />
        </div>
    </div>

    <Notification />

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
</template>

<script setup>
import Header from "@/Views/Admin/Header.vue";
import { computed, provide, ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import Notification from "@/Components/Notification.vue";
import { useUIStore } from "@/Store/ui";
import { CheckCircleIcon, XCircleIcon } from "@heroicons/vue/20/solid";
import Loading from "@/Components/Loading.vue";

const activePage = computed(() => usePage().url);

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

Echo.private("students-import").listen(".students-import", (e) => {
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
