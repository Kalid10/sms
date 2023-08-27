<template>
    <GeneralInfo
        :teacher-name="assessment.created_by.name"
        :total-students="assessment?.students.length"
        :status="capitalize(assessment.status)"
    />

    <div class="flex w-full items-center justify-center">
        <SecondaryButton
            :title="$t('published.startMarking')"
            class="w-44 rounded-xl bg-indigo-400 font-semibold text-white"
            @click="startMarking"
        />
    </div>
</template>
<script setup>
import { capitalize } from "vue";

import GeneralInfo from "@/Views/Teacher/Views/Assessments/Details/Views/Info.vue";
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
        student_id: null,
    });
}
</script>
<style scoped></style>
