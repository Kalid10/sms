<template>
    <component :is="steps[currentStep].component" @success="next" />
</template>

<script setup>
import { computed, defineAsyncComponent, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const steps = {
    1: {
        component: defineAsyncComponent(() =>
            import("@/Views/GettingStarted/RegisterSchoolYear.vue")
        ),
    },
    2: {
        component: defineAsyncComponent(() =>
            import("@/Views/GettingStarted/RegisterBatches.vue")
        ),
    },
    3: {
        component: defineAsyncComponent(() =>
            import("@/Views/GettingStarted/RegisterSubjects.vue")
        ),
    },
    4: {
        component: defineAsyncComponent(() =>
            import("@/Views/GettingStarted/AssignSubjects.vue")
        ),
    },
    // 5: {
    //     component: defineAsyncComponent(() =>
    //         import("@/Views/GettingStarted/SchoolSchedule.vue")
    //     ),
    // },
};

const step = computed(() => usePage().props.step);
const currentStep = ref(step.value);

function next() {
    currentStep.value += 1;
}

function updateStep(step) {
    currentStep.value = step;
}
</script>
