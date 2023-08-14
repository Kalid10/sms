<template>
    <div
        class="scrollbar-hide mt-1 flex w-full items-center justify-evenly overflow-y-scroll lg:mt-7 2xl:mt-10"
        :class="isOpen ? 'lg:space-y-1 space-y-3' : 'space-y-3'"
    >
        <div v-if="isOpen" class="items-start">
            <ArrowsRightLeftIcon class="h-5 w-5" />
        </div>

        <select
            class="cursor-pointer appearance-none rounded-lg border-brand-550 bg-brand-550 text-brand-50"
            :class="isOpen ? 'w-fit' : 'w-full px-4'"
            @change="changeLanguage($event)"
        >
            <option value="en">
                <span v-if="isOpen">
                    {{ $t("common.english") }}
                </span>
                <span v-else>
                    {{ $t("common.en") }}
                </span>
            </option>
            <option value="am">
                <span v-if="isOpen">
                    {{ $t("common.amharic") }}
                </span>
                <span v-else>{{ $t("common.am") }}</span>
            </option>
        </select>
    </div>
</template>

<script setup>
import { useI18n } from "vue-i18n";
import { computed } from "vue";
import { useSidebarStore } from "@/Store/sidebar";
import { ArrowsRightLeftIcon } from "@heroicons/vue/24/solid";

const { locale } = useI18n();
const isOpen = computed(() => useSidebarStore().isOpen);

const changeLanguage = (event) => {
    locale.value = event.target.value;
};
</script>

<style scoped></style>
