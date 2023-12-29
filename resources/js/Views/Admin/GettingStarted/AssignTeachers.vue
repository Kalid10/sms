<template>
    <div
        class="container mx-auto flex h-full max-h-full w-10/12 flex-col space-y-10 px-2 pt-6 md:px-6 md:pt-6"
    >
        <div class="flex w-full items-center justify-between">
            <span class="w-11/12 text-4xl font-semibold"
                >Now Let Assign Teachers</span
            >
            <div class="flex space-x-5">
                <PrimaryButton
                    title="Back"
                    class="h-fit w-fit !bg-brand-200 !text-black"
                    @click="$emit('back')"
                />
                <PrimaryButton
                    title="Skip"
                    class="h-fit w-fit !bg-brand-100 !text-black"
                    @click="router.visit('/getting-started/school-period')"
                />
            </div>
        </div>

        <div class="flex h-full w-full grow justify-between overflow-hidden">
            <div class="flex h-full w-10/12 flex-col rounded-lg bg-white p-3">
                <BatchSubjects
                    teacher-search-url="/getting-started"
                    update-batch-subjects-url="/getting-started/batch-subjects/update"
                    :batch="selectedBatch"
                    :show-teacher-schedule="false"
                >
                    <template #EmptySlot>
                        <PrimaryButton
                            title="Go Back Register Teachers"
                            @click="$emit('back')"
                        />
                    </template>
                </BatchSubjects>
            </div>

            <div
                class="scrollbar-hide mb-5 flex w-1/12 flex-col overflow-y-auto bg-brand-100"
            >
                <div
                    v-for="(item, index) in batches"
                    :key="index"
                    class="w-full"
                >
                    <div
                        :class="
                            selectedBatch?.id === item.id
                                ? 'bg-brand-300 text-white'
                                : ''
                        "
                        class="w-full cursor-pointer border border-black py-3 text-center font-semibold hover:bg-brand-300 hover:text-white"
                        @click="loadBatchSubjects(item)"
                    >
                        {{ item.level.name }} - {{ item.section }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { router, usePage } from "@inertiajs/vue3";
import { computed, onMounted, ref } from "vue";

import BatchSubjects from "@/Views/BatchSchedule/Setup/BatchSubjects.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

onMounted(() => {
    getBatches();
});

defineEmits(["back"]);

function getBatches() {
    router.visit("/getting-started", {
        only: ["batches"],
        preserveState: true,
    });
}

const batches = computed(() => usePage().props.batches);
const selectedBatch = ref(null);

function loadBatchSubjects(batch) {
    selectedBatch.value = batch;
    router.get(
        "/getting-started",
        {
            batch_id: batch.id,
        },
        {
            only: ["batchSubjects"],
            preserveState: true,
        }
    );
}
</script>

<style scoped></style>
