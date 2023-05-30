<template>
    <div>
        <transition
            v-if="showNotification"
            name="notification"
            mode="out-in"
            enter-active-class="notification-enter-active"
            leave-active-class="notification-leave-active"
            enter-class="notification-enter"
            leave-class="notification-leave-to"
        >
            <div
                v-if="success || error || info"
                :key="success || error || info"
                :class="`fixed ${positionClass} w-fit px-2 z-50`"
            >
                <div
                    v-if="error"
                    class="m-10 rounded-lg bg-white p-4 shadow-lg sm:mb-10 sm:px-6 sm:py-4 md:h-auto"
                >
                    <div class="text-xs font-medium text-zinc-700 lg:text-sm">
                        <ExclamationTriangleIcon
                            class="mt-1.5 min-h-[1.75rem] w-5 min-w-[1.75rem] stroke-red-500"
                        />
                        <div>{{ error }}</div>
                    </div>
                </div>
                <div
                    v-else-if="success"
                    class="m-10 rounded-lg bg-white p-4 py-3 shadow-lg sm:mb-10 sm:px-6 sm:py-4 md:h-auto"
                >
                    <div class="text-xs font-medium text-zinc-700 lg:text-sm">
                        <CheckCircleIcon class="w-5 stroke-green-500" />
                        <div>{{ success }}</div>
                    </div>
                </div>
                <div
                    v-else
                    class="m-10 max-w-md rounded-lg bg-white p-4 py-3 shadow-lg sm:mb-10 sm:px-6 sm:py-4 md:h-auto"
                >
                    <div
                        class="flex flex-row items-center justify-center space-x-2"
                    >
                        <InformationCircleIcon class="w-5 stroke-sky-500" />
                        <div
                            class="text-xs font-medium text-zinc-700 lg:text-sm"
                        >
                            {{ info }}
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script setup>
import { computed, inject, ref, watch } from "vue";
import {
    CheckCircleIcon,
    ExclamationTriangleIcon,
    InformationCircleIcon,
} from "@heroicons/vue/24/outline";
import { usePage } from "@inertiajs/vue3";

const flashSuccess = computed(() => usePage().props.flash.success);
const flashError = computed(() => usePage().props.flash.error);
const flashInfo = computed(() => usePage().props.flash.info);

const localNotification = ref({ success: null, error: null, info: null });

const success = computed(
    () => flashSuccess.value || localNotification.value.success
);
const error = computed(() => flashError.value || localNotification.value.error);
const info = computed(() => flashInfo.value || localNotification.value.info);

const showNotification = ref(false);

const flash = computed(() => usePage().props.flash);

watch(flash, () => {
    showNotification.value = true;
    setTimeout(() => {
        showNotification.value = false;
        usePage().props.flash = { success: null, error: null, info: null };
    }, 4000);
});

const notificationData = inject("notificationData");

watch(notificationData, (newVal) => {
    if (newVal) {
        showNotification.value = true;
        switch (newVal.type) {
            case "success":
                localNotification.value.success = newVal.message;
                break;
            case "error":
                localNotification.value.error = newVal.message;
                break;
            case "info":
                localNotification.value.info = newVal.message;
                break;
        }
        setTimeout(() => {
            showNotification.value = false;
            localNotification.value = {
                success: null,
                error: null,
                info: null,
            };
            notificationData.value.position = null;
        }, newVal.timeout || 4000);
    }
});

const positionClass = computed(() => {
    switch (notificationData.value?.position) {
        case "top-left":
            return "top-0 left-5";
        case "top-right":
            return "top-0 right-0";
        case "top-center":
            return "top-0 left-1/2 transform -translate-x-1/2";
        case "bottom-left":
            return "bottom-0 left-0";
        case "bottom-right":
            return "bottom-0 right-0";
        default:
            return "top-0 right-0 lg:top-auto lg:right-0 md:bottom-0 md:top-auto lg:bottom-0 ";
    }
});
</script>

<style scoped>
.notification-enter-active,
.notification-leave-active {
    transition: opacity 2.3s;
}

.notification-enter,
.notification-leave-to {
    opacity: 0;
}

@media (max-width: 767px) {
    .fixed.top-0 {
        top: 0;
    }

    .fixed.right-0 {
        right: 0;
    }
}
</style>
