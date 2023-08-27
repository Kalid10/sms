<template>
    <div class="flex w-full items-center justify-center">
        <SecondaryButton
            :title="$t('marking.continueMarking')"
            class="w-44 rounded-xl bg-indigo-400 font-semibold text-white lg:w-56"
            @click="startMarking"
        />
    </div>
</template>
<script setup>
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
