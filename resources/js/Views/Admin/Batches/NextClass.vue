<template>
    <div
        class="flex w-full flex-col items-center justify-center rounded-lg bg-gradient-to-bl from-pink-400 to-purple-500 py-2 text-center uppercase text-white shadow-sm"
    >
        <div
            v-if="nextClassSession.school_period"
            class="flex h-full flex-col items-center space-y-1.5"
        >
            <div class="text-[0.65rem] font-light">Next Class</div>

            <div class="text-2xl font-semibold uppercase">
                {{ nextClassSession.school_period.name }}
            </div>
            <div class="text-[0.65rem] font-light">
                {{ $t("common.with") }}
            </div>

            <div
                class="cursor-pointer text-xs font-semibold uppercase underline-offset-2 hover:scale-105 hover:underline"
            >
                <span v-if="nextClassSession.teacher">{{
                    nextClassSession.teacher.user.name
                }}</span>
                <span v-else> Custom Period</span>
            </div>
        </div>
        <div v-else>No Next Class</div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const nextClassSession = computed(() => usePage().props.next_class_session);
</script>

<style scoped></style>
