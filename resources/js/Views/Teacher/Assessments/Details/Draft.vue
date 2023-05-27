<template>
    <div class="flex h-fit w-10/12 flex-col items-center space-y-4"></div>
</template>
<script setup>
import { computed } from "vue";

const props = defineProps({
    assessment: {
        type: Object,
        required: true,
    },
});

const title = computed(
    () =>
        props.assessment.title +
        " " +
        props.assessment.batch_subject.batch.level.name +
        props.assessment.batch_subject.batch.section
);
</script>
<style scoped></style>
