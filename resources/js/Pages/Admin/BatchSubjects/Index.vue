<template>

    <div class="my-5 flex w-10/12 flex-col space-y-5">
        <div class="flex items-center justify-start gap-8">
            <div class="flex items-center gap-2">
                <div
                    :class="subjectPriorityLabels[subject['priority'] - 1]"
                    class="h-7 w-0.5"
                />
                <h3 class="text-xl font-semibold lg:text-4xl">
                    {{ parseLevel(level["name"]) }}
                    {{ subject["full_name"] }}
                </h3>
            </div>
            <div class="flex flex-col items-baseline">
                <h3 class="text-md text-gray-500">{{ subject["category"] }}</h3>
                <h3 class="text-xs text-gray-500">
                    {{
                        subject["tags"]
                            .map((tag) => toHashTag(tag))
                            .join("&nbsp;&nbsp;")
                    }}
                </h3>
            </div>
        </div>

        <TabElement v-model:active="activeTab" active-only :tabs="batchTabs">

            <div class="flex w-full flex-col gap-8 p-4">

                <WeeklySessions />

                <BatchSubjectTeacher />

                <Heading size="md">Batch Schedule</Heading>

            </div>

        </TabElement>
    </div>

</template>

<script setup>
import {parseLevel, subjectPriorityLabels, toHashTag} from "@/utils.js";
import TabElement from "@/Components/TabElement.vue";
import {computed, ref, watch} from "vue";
import {router, usePage} from "@inertiajs/vue3";
import Heading from "@/Components/Heading.vue";
import BatchSubjectTeacher from "@/Views/Admin/BatchSubjects/BatchSubjectTeacher.vue";
import WeeklySessions from "@/Views/Admin/BatchSubjects/WeeklySessions.vue";

const subject = computed(() => usePage().props.subject)
const level = computed(() => usePage().props.batch.level)
const selectedBatch = computed(() => usePage().props.batch)
const batches = computed(() => usePage().props.batches)
const batchSubject = computed(() => usePage().props.batchSubject)

const batchTabs = computed(() => {
    return batches.value.map(batch => `Section ${batch.section}`)
})

const batchSectionIds = computed(() => {
    return new Map(batches.value.map(batch => {
        return [`Section ${batch.section}`, batch.id]
    }))
})

const activeTab = ref(`Section ${selectedBatch.value.section}`)

watch(activeTab, () => {
    router.get(`/batches/subjects/${batchSectionIds.value.get(activeTab.value)}/${subject.value.id}`)
})
</script>

<style scoped>

</style>
