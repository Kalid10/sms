<template>
    <div class="flex h-full w-full gap-4 overflow-hidden">
        <GradesList :levels="levels" />
        <BatchesScheduleTab
            v-if="!!selectedBatch"
            :selected="selectedBatch"
            :batches="selectedLevelBatches"
        />
    </div>
</template>

<script setup>
import GradesList from "@/Views/BatchSchedule/GradesList.vue";
import BatchesScheduleTab from "@/Views/BatchSchedule/BatchesScheduleTab.vue";
import {computed} from "vue";
const props = defineProps({
    levels: {
        type: Array,
        required: true
    },
    selectedBatch: {
        type: Object,
        default: null
    },
    schoolPeriods: {
        type: Array,
        required: true
    }
})

const batches = computed(() => props.levels.map(function (level) {
    return level.batches.map((batch) => {
        return {
            id: batch.id,
            section: batch.section,
            level: level.name
        }
    })
}))

const selectedLevelBatches = computed(() => {
    if (!props.selectedBatch) return null;
    return batches.value.find((batch) => batch.map(b => b.id).includes(props.selectedBatch.id))
})
</script>

<style scoped>

</style>
