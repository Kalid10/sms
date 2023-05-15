<template>
    <div class="flex h-full w-full gap-4 bg-white px-8 pt-4">
        <LessonPlanWeekCard
            v-for="w in weeksInMonth"
            :key="w"
            :batch-sessions="batchSessions[`week${w}`]"
            :index="w"
        >
            <LessonPlanCard
                v-for="(session, s) in batchSessions[`week${w}`]"
                :key="s"
                :session="session"
                @click="selectBatchSession(session)"
            >
            </LessonPlanCard>
        </LessonPlanWeekCard>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import LessonPlanWeekCard from "@/Views/Teacher/LessonPlans/LessonPlanWeekCard.vue";
import LessonPlanCard from "@/Views/Teacher/LessonPlans/LessonPlanCard.vue";

const emits = defineEmits(["select"]);

const weeksInMonth = computed(() => usePage().props.weeks_in_month);
const batchSessions = computed(() => usePage().props["batch_sessions"]);

function selectBatchSession(session) {
    emits("select", session);
}
</script>

<style scoped></style>
