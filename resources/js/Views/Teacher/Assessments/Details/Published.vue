<template>
    <GeneralInfo
        teacher-name="Bereket Gobeze"
        :count="assessment.total_students"
        :status="capitalize(assessment.status)"
    />

    <div class="flex w-full items-center justify-center">
        <SecondaryButton
            title="START MARKING"
            class="w-44 rounded-xl bg-emerald-400 font-semibold"
            @click="startMarking"
        />
    </div>
</template>
<script setup>
import { capitalize } from "vue";

import GeneralInfo from "@/Views/Teacher/Assessments/Details/Views/Info.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    assessment: {
        type: Object,
        required: true,
    },
});

function startMarking() {
    router.get("/teacher/assessments/mark/" + props.assessment.id, {
        student_id: props.assessment.id,
    });
}
</script>
<style scoped></style>
