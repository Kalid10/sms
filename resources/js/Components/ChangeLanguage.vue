<template>
    <div
        class="scrollbar-hide flex items-center justify-evenly overflow-y-scroll py-1"
        :class="isOpen ? 'lg:space-y-1 space-y-3 w-11/12' : 'space-y-3 w-full'"
    >
        <div v-if="isOpen" class="flex justify-center">
            <ArrowsRightLeftIcon :class="isOpen ? 'h-4' : 'h-5'" />
        </div>

        <div
            class="flex items-center justify-end"
            :class="isOpen ? 'w-8/12' : 'w-full'"
        >
            <select
                v-model="locale"
                class="cursor-pointer appearance-none rounded-lg border-none bg-brand-550 py-2 text-center text-xs text-brand-50 lg:text-sm"
                :class="
                    isOpen
                        ? 'w-10/12 focus:outline-none focus:ring-1 focus:ring-brand-200'
                        : 'w-full px-4 hide-arrow !bg-brand-550 focus:ring-0'
                "
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
    const selectedLanguage = event.target.value;
    locale.value = selectedLanguage;
    localStorage.setItem("selectedLanguage", selectedLanguage);
    location.reload();
};
</script>

<style scoped>
.hide-arrow {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background: none;
}
</style>
