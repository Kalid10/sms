<template>
    <div
        v-if="show"
        class="scrollbar-hide relative isolate flex items-center gap-x-6 overflow-hidden bg-gray-50 px-6 py-2.5 sm:px-3.5 sm:before:flex-1"
    >
        <div
            class="absolute left-[max(-7rem,calc(50%-52rem))] top-1/2 -z-10 -translate-y-1/2 transform-gpu blur-2xl"
            aria-hidden="true"
        >
            <div
                class="aspect-[577/310] w-[36.0625rem] bg-gradient-to-r"
                :class="
                    customStyle
                        ? customStyle
                        : 'from-[#ff80b5] to-[#9089fc] opacity-30'
                "
                style="
                    clip-path: polygon(
                        74.8% 41.9%,
                        97.2% 73.2%,
                        100% 34.9%,
                        92.5% 0.4%,
                        87.5% 0%,
                        75% 28.6%,
                        58.5% 54.6%,
                        50.1% 56.8%,
                        46.9% 44%,
                        48.3% 17.4%,
                        24.7% 53.9%,
                        0% 27.9%,
                        11.9% 74.2%,
                        24.9% 54.1%,
                        68.6% 100%,
                        74.8% 41.9%
                    );
                "
            />
        </div>
        <div
            class="absolute left-[max(45rem,calc(50%+8rem))] top-1/2 -z-10 -translate-y-1/2 transform-gpu blur-2xl"
            aria-hidden="true"
        >
            <div
                class="aspect-[577/310] w-[36.0625rem] bg-gradient-to-r from-[#ff80b5] to-[#9089fc] opacity-30"
                style="
                    clip-path: polygon(
                        74.8% 41.9%,
                        97.2% 73.2%,
                        100% 34.9%,
                        92.5% 0.4%,
                        87.5% 0%,
                        75% 28.6%,
                        58.5% 54.6%,
                        50.1% 56.8%,
                        46.9% 44%,
                        48.3% 17.4%,
                        24.7% 53.9%,
                        0% 27.9%,
                        11.9% 74.2%,
                        24.9% 54.1%,
                        68.6% 100%,
                        74.8% 41.9%
                    );
                "
            />
        </div>
        <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
            <p class="text-sm leading-6 text-gray-900">
                <span class="font-semibold">{{ title }}</span>
            </p>
            <div
                v-if="isEmit"
                class="flex-none cursor-pointer rounded-full bg-brand-400 px-3.5 py-1 text-sm font-semibold text-white shadow-sm hover:bg-brand-350 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900"
                @click="$emit('buttonClicked')"
            >
                {{ buttonText }}
                <span aria-hidden="true">&rarr;</span>
            </div>
            <Link
                v-else
                :href="buttonUrl"
                :preserve-state="true"
                class="flex-none cursor-pointer rounded-full bg-brand-400 px-3.5 py-1 text-sm font-semibold text-white shadow-sm hover:bg-brand-350 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900"
                >{{ buttonText }} <span aria-hidden="true">&rarr;</span></Link
            >
        </div>
        <div class="flex flex-1 justify-end">
            <button
                type="button"
                class="-m-3 p-3 focus-visible:outline-offset-[-4px]"
                @click="show = false"
            >
                <span class="sr-only">Dismiss</span>
                <XMarkIcon
                    v-if="showCloseButton"
                    class="h-5 w-5 cursor-pointer text-gray-900 hover:scale-125 hover:text-red-500"
                    aria-hidden="true"
                />
            </button>
        </div>
    </div>
</template>

<script setup>
import { XMarkIcon } from "@heroicons/vue/20/solid";
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";

const show = ref(true);
defineEmits(["buttonClicked"]);
defineProps({
    title: {
        type: String,
        required: true,
    },
    buttonText: {
        type: String,
        default: "",
    },
    buttonUrl: {
        type: String,
        default: "",
    },
    isEmit: {
        type: Boolean,
        default: false,
    },
    customStyle: {
        type: String,
        default: null,
    },
    showCloseButton: {
        type: Boolean,
        default: true,
    },
});
</script>
<style scoped></style>
