<template>
    <div
        class="flex h-44 w-full flex-col items-center justify-evenly rounded-lg bg-gradient-to-b from-violet-500 to-purple-500 text-white shadow-sm"
    >
        <div
            class="-skew-x-3 bg-violet-300 px-3 text-xl font-bold uppercase text-brand-text-450"
        >
            {{ $t("ai.artificialIntelligence") }}
        </div>
        <div class="px-3 text-center text-purple-50">
            {{ $t("ai.empowerYourTeaching") }}
        </div>
        <SecondaryButton
            class="w-6/12 !rounded-2xl bg-violet-300 font-semibold text-brand-text-450"
            :title="$t('ai.exploreMore')"
            @click="router.get('/teacher/copilot')"
        />
    </div>
</template>
<script setup>
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { router } from "@inertiajs/vue3";
</script>
<style scoped></style>
