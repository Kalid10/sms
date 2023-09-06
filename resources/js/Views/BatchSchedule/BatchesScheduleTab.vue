<template>
    <div class="flex w-full flex-col gap-6 p-6">
        <div class="flex flex-col">
            <Heading size="2xl"
                >{{ parseLevel(selected["level"]["name"]) }}
                {{ selected["section"] }} Timetable
            </Heading>
            <Heading size="sm" class="!font-normal"
                >{{ selected["school_year"]["name"] }}
            </Heading>
        </div>

        <TabElement
            v-model:active="selectedBatch"
            object-list
            :tabs="tabs"
            active-only
        >
            <BatchScheduleView
                v-if="view === 'generated-schedule'"
                :batch="selected"
            />
            <BatchSubjects
                v-if="view === 'setup'"
                class="!overflow-y-auto"
                :batch="selected"
            />
        </TabElement>
    </div>
</template>

<script setup>
import BatchScheduleView from "@/Views/BatchSchedule/BatchScheduleView.vue";
import { computed, ref, watch } from "vue";
import { parseLevel } from "@/utils.js";
import TabElement from "@/Components/TabElement.vue";
import Heading from "@/Components/Heading.vue";
import { router } from "@inertiajs/vue3";
import BatchSubjects from "@/Views/BatchSchedule/Setup/BatchSubjects.vue";

const props = defineProps({
    batches: {
        type: Array,
        required: true,
    },
    selected: {
        type: Object,
        required: true,
    },
    view: {
        type: String,
        default: "generated-schedule",
    },
});

const tabs = computed(() => {
    return props.batches.map((batch) => {
        return {
            key: batch.id,
            label: `Section ${batch.section}`,
        };
    });
});
const selectedBatch = ref(props.selected.id);

watch(selectedBatch, () => {
    if (selectedBatch.value === props.selected.id) return;

    if (props.view === "generated-schedule")
        router.get(`?batch_id=${selectedBatch.value}`);

    if (props.view === "setup") loadBatchSubjects();
});

watch(
    () => props.selected,
    () => {
        selectedBatch.value = props.selected.id;
    }
);

const loadBatchSubjects = () => {
    router.get(
        "/admin/batch-schedules",
        {
            batch_id: selectedBatch.value,
        },
        {
            preserveState: true,
            only: ["batchSubjects"],
        }
    );
};
</script>

<style scoped></style>
