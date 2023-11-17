<template>
    <GeneralInfo
        :teacher-name="assessment.created_by.name"
        :total-students="assessment?.students.length"
        :status="capitalize(assessment.status)"
    />

    <div
        class="flex w-full flex-col items-center justify-center space-y-4 rounded-lg bg-indigo-400 p-4"
    >
        <div class="w-full rounded-lg p-2 text-center text-xs text-white">
            Setting an assessment as
            <span class="bg-indigo-200 px-1 font-semibold text-black"
                >' MARKING '</span
            >
            will trigger immediate notifications to guardians and principals.
            Detailed information about the assessment can be accessed for
            further insight.
        </div>
        <SecondaryButton
            :title="$t('published.startMarking')"
            class="w-44 rounded-xl border-2 border-indigo-800 bg-indigo-200 font-semibold"
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
