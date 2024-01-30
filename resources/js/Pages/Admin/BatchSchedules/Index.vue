<template>
    <div class="w-11/12">
        <div class="flex h-full w-full items-center gap-4 overflow-hidden">
            <BatchesScheduleTab
                v-if="!!selectedBatch"
                :selected="selectedBatch"
                :batches="selectedLevelBatches"
                :view="view"
            />
            <div v-else class="h-full w-full p-6">
                <div
                    class="flex h-full w-full flex-col items-center justify-center gap-4"
                >
                    <Heading class="!text-4xl"> Select a Grade</Heading>
                    <Heading class="text-center" size="lg">
                        Select <span class="lowercase">a</span> grade from the
                        list on the right. All sections will
                        <span class="block"
                            >be available once you choose a grade</span
                        >
                    </Heading>
                </div>
            </div>

            <GradesList :levels="levels" />
        </div>
    </div>
</template>

<script setup>
import GradesList from "@/Views/BatchSchedule/GradesList.vue";
import BatchesScheduleTab from "@/Views/BatchSchedule/BatchesScheduleTab.vue";
import { computed, ref, watch } from "vue";
import Heading from "@/Components/Heading.vue";
import { usePage } from "@inertiajs/vue3";
import { useUIStore } from "@/Store/ui";

const props = defineProps({
    levels: {
        type: Array,
        required: true,
    },
    selectedBatch: {
        type: Object,
        default: null,
    },
    schoolPeriods: {
        type: Array,
        required: true,
    },
});

const batches = computed(() =>
    props.levels.map(function (level) {
        return level.batches.map((batch) => {
            return {
                id: batch.id,
                section: batch.section,
                level: level.name,
            };
        });
    })
);

const selectedLevelBatches = computed(() => {
    if (!props.selectedBatch) return null;
    return batches.value.find((batch) =>
        batch.map((b) => b.id).includes(props.selectedBatch.id)
    );
});

const batchScheduleConfiguration = computed(() => {
    return usePage().props.batchScheduleConfig;
});

const view = computed(() => {
    return props.selectedBatch.schedule?.length > 0
        ? "generated-schedule"
        : "setup";
});

const showScheduleGenerating = ref(false);
const uiStore = useUIStore();
const isStoreLoading = computed(() => uiStore.isLoading);
const storeLoadingMessage = computed(() => uiStore.loadingMessage);
watch(
    isStoreLoading,
    () => {
        if (
            isStoreLoading.value &&
            storeLoadingMessage.value === "Generating Class Schedule"
        ) {
            showScheduleGenerating.value = true;
        }

        if (!isStoreLoading.value) {
            showScheduleGenerating.value = false;
        }
    },
    { immediate: true }
);
</script>

<style scoped></style>
