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
                :class="`fixed ${positionClass} w-fit  px-2 z-50`"
            >
                <div
                    :class="{
                        ' from-emerald-400 to-green-400 text-white':
                            notificationType.type === 'success',
                        'from-red-500 to-red-500':
                            notificationType.type === 'error',
                        'from-sky-500 to-cyan-500':
                            notificationType.type === 'info',
                    }"
                    class="m-10 flex items-center justify-center space-x-3 rounded-lg bg-gradient-to-tr p-4 text-xs font-medium text-white shadow-lg sm:mb-10 sm:px-6 md:h-auto lg:text-sm"
                >
                    <component
                        :is="notificationType.icon"
                        class="h-6 w-6 text-white"
                    />
                    <div>{{ notificationType.message }}</div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script setup>
import { computed, defineAsyncComponent, inject, ref, watch } from "vue";
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

const notificationType = computed(() => {
    return {
        type: !!success.value
            ? "success"
            : !!error.value
            ? "error"
            : !!info.value
            ? "info"
            : "",
        message: success.value || error.value || info.value,
        icon: success.value
            ? defineAsyncComponent(() =>
                  import("@heroicons/vue/24/outline/CheckCircleIcon.js")
              )
            : error.value
            ? defineAsyncComponent(() =>
                  import("@heroicons/vue/24/outline/ExclamationTriangleIcon.js")
              )
            : info.value
            ? defineAsyncComponent(() =>
                  import("@heroicons/vue/24/outline/InformationCircleIcon")
              )
            : "",
        style: "text-green-500",
    };
});

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
        usePage().props.flash = { success: null, error: null, info: null };
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
    switch (notificationData?.position) {
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
            return "top-0 left-1/2 transform -translate-x-1/2  ";
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
</style>
