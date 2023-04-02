<template>
    <div>
        <transition
v-if="showNotification" name="notification" mode="out-in"
                    enter-active-class="notification-enter-active"
                    leave-active-class="notification-leave-active"
                    enter-class="notification-enter"
                    leave-class="notification-leave-to">
            <div
                v-if="success || error || info"
                :key="success || error || info"
                class="fixed right-0 top-0 w-full md:bottom-0 md:top-auto md:w-1/2 lg:bottom-0 lg:top-auto lg:right-0 lg:z-50 lg:w-1/3 xl:right-0 xl:w-1/4"
            >
                <div
                    v-if="error"
                    class="m-10 max-w-md rounded-lg bg-white p-4 font-medium text-gray-500 shadow-lg sm:mb-10 sm:px-6 sm:py-4 md:h-auto"
                >
                    <div class="flex flex-row gap-3">
                        <ExclamationTriangleIcon class="mt-1.5 h-7 min-h-[1.75rem] w-7 min-w-[1.75rem] stroke-red-500"/>
                        <div>{{ error }}</div>
                    </div>
                </div>
                <div
                    v-else-if="success"
                    class="m-10 max-w-md rounded-lg bg-white p-4 py-3 font-medium text-gray-500 shadow-lg sm:mb-10 sm:px-6 sm:py-4 md:h-auto"
                >
                    <div class="flex flex-row">
                        <CheckCircleIcon class="h-7 w-7 stroke-green-500"/>
                        <div class="pl-5">{{ success }}</div>
                    </div>
                </div>
                <div
                    v-else
                    class="m-10 max-w-xs rounded-lg bg-blue-500 p-4 py-3 font-medium text-white shadow-md"
                >
                    <div>
                        <EnvelopeIcon/>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script setup>
import {computed, ref, watch} from "vue";
import {CheckCircleIcon, EnvelopeIcon, ExclamationTriangleIcon,} from "@heroicons/vue/24/outline";
import {usePage} from "@inertiajs/vue3";

const success = computed(() => usePage().props.flash.success);
const error = computed(() => usePage().props.flash.error);
const info = computed(() => usePage().props.flash.info);

const showNotification = ref(false);

const flash = computed(() => usePage().props.flash);

watch(flash, () => {
    showNotification.value = true;
    setTimeout(() => {
        showNotification.value = false;
    }, 4000);
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
