<template>
    <div class="flex w-full flex-col gap-6 p-6">
        <div class="flex w-full justify-between">
            <div class="flex w-9/12 flex-col">
                <Heading size="2xl"
                    >{{ parseLevel(selected["level"]["name"]) }}
                    {{ selected["section"] }} Timetable
                </Heading>
                <Heading size="sm" class="!font-normal"
                    >{{ selected["school_year"]["name"] }}
                </Heading>
            </div>
            <div class="flex w-3/12 items-center justify-end">
                <SecondaryButton
                    class="!h-fit !w-fit bg-brand-400 !font-medium text-white"
                    :title="
                        view === 'generated-schedule'
                            ? 'Switch To Setup View'
                            : 'Switch To Generated Schedule View'
                    "
                    @click="
                        view =
                            view === 'generated-schedule'
                                ? 'setup'
                                : 'generated-schedule'
                    "
                />
            </div>
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

            <div
                v-if="view === 'setup'"
                class="h-screen w-full flex-col justify-between space-y-5"
            >
                <div
                    v-if="errorBatchSubjectsMessage"
                    class="flex w-full items-center justify-center bg-red-600"
                >
                    <span
                        class="w-fit rounded-lg bg-red-600 p-4 text-center text-white"
                    >
                        {{ errorBatchSubjectsMessage }}
                    </span>
                </div>

                <BatchSubjects class="!overflow-y-auto" :batch="selected" />
                <div
                    v-if="isReady"
                    class="flex w-7/12 flex-col items-center justify-center rounded-lg border-2 border-black bg-brand-400 p-5"
                >
                    <div
                        class="mb-4 text-center text-lg font-medium text-white"
                    >
                        Caution: Please ensure all data is correctly entered and
                        saved before generating the schedule to avoid any
                        discrepancies or errors.
                    </div>
                    <SecondaryButton
                        title="Generate Class Schedule"
                        class="w-fit !rounded-2xl bg-brand-100 py-2 !px-10 !font-semibold"
                        @click="generateSchedule"
                    />
                </div>
            </div>
        </TabElement>
    </div>
    <Loading v-if="isLoading" is-full-screen />
</template>

<script setup>
import BatchScheduleView from "@/Views/BatchSchedule/BatchScheduleView.vue";
import { computed, ref, watch } from "vue";
import { parseLevel } from "@/utils.js";
import TabElement from "@/Components/TabElement.vue";
import Heading from "@/Components/Heading.vue";
import { router, usePage } from "@inertiajs/vue3";
import BatchSubjects from "@/Views/BatchSchedule/Setup/BatchSubjects.vue";
import Loading from "@/Components/Loading.vue";
import { useUIStore } from "@/Store/ui";
import SecondaryButton from "@/Components/SecondaryButton.vue";

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

const isLoading = ref(false);
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
    isLoading.value = true;
    router.get(
        `?batch_id=${selectedBatch.value}`,
        {},
        {
            preserveState: true,
            onFinish: () => {
                isLoading.value = false;
            },
        }
    );

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

const isReady = computed(() => usePage().props.isReadyForGeneratingSchedule);

const uiStore = useUIStore();
const errorBatchSubjects = ref(null);
const errorBatchSubjectsMessage = ref(null);
const generateSchedule = () => {
    isLoading.value = true;
    errorBatchSubjects.value = null;
    errorBatchSubjectsMessage.value = null;
    router.post(
        "/admin/batch-schedules/generate",
        {},
        {
            preserveState: true,
            onSuccess: () => {
                uiStore.setLoading(true, "Generating Class Schedule");
            },
            onError: (error) => {
                uiStore.setLoading(false);

                if (error.schedule_generator_error)
                    errorBatchSubjectsMessage.value =
                        error.schedule_generator_error;

                if (error.error_batch_subjects) {
                    errorBatchSubjects.value = error.error_batch_subjects;
                }
            },
            onFinish: () => (isLoading.value = false),
        }
    );
};
</script>

<style scoped></style>
