<template>
    <div>
        <div
            v-if="showNotification"
            tabindex="-1"
            class="fixed top-0 right-0 z-50 flex h-full w-full items-start justify-end overflow-y-auto overflow-x-hidden p-4 md:inset-0 md:h-fit"
        >
            <div class="relative h-full w-full max-w-md md:h-auto">
                <div
                    class="relative rounded-lg px-4 py-3 font-medium text-white shadow-sm sm:px-6 sm:py-4"
                    :class="{
              'bg-red-400': type === 'error',
              'bg-green-400': type === 'success',
              'bg-blue-400': type === 'default',
            }"
                >
                    <button
                        type="button"
                        class="absolute top-0 right-0 mr-2 mt-2 rounded-md focus:outline-none"
                        @click="showNotification = false"
                    >
                        <span class="sr-only">Close</span>
                        <XMarkIcon class="h-5 w-5 stroke-2"></XMarkIcon>
                    </button>
                    <div class="flex items-center space-x-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full">
                            <slot name="icon">
                                <slot name="icon" class="text-black">
                                    <template v-if="type === 'error'">
                                        <ExclamationTriangleIcon class="h-6 w-6 stroke-white" />
                                    </template>
                                    <template v-else-if="type === 'success'">
                                        <CheckCircleIcon class="h-7 w-7 stroke-white" />
                                    </template>
                                    <template v-else>
                                        <EnvelopeIcon class="h-6 w-6 stroke-white" />
                                    </template>
                                </slot>
                            </slot>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="text-base">
                                {{ title }}
                            </div>
                            <div class="mt-0.5 text-sm">
                                {{ subtitle }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, defineProps } from 'vue';
import {XMarkIcon, ExclamationTriangleIcon, CheckCircleIcon,EnvelopeIcon} from '@heroicons/vue/24/outline';

defineProps({
    title: {
        type: String,
        required: true,
    },
    subtitle: {
        type: String,
        default: null,
    },
    type: {
        type: String,
        default: 'default',
        validator: (value) =>
            ['notification', 'error', 'success'].indexOf(value) !== -1,

    },
});
const showNotification = ref(true);
watch(showNotification, (newVal) => {
    if (newVal) {
        setTimeout(() => {
            showNotification.value = false;
        }, 3000);
    }
});
</script>

