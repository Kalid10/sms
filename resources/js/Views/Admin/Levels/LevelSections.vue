<template>

    <div class="grid grid-cols-4 gap-3 ">

        <Card
            v-for="(batch, b) in batches"
            :key="b"
            class="!w-full cursor-pointer"
            :title="`Section ${batch.section}`"
            @click="handleClick(batch.id)">

            <div class="flex flex-wrap gap-3">

                <div class="flex items-center gap-1">
                    <div class="h-1.5 w-1.5 rounded-full bg-red-600"/>
                    <span class="text-sm text-gray-500">{{ batch['students_count'] }} Students</span>
                </div>

                <div class="flex items-center gap-1">
                    <div class="h-1.5 w-1.5 rotate-45 bg-yellow-600"/>
                    <span class="text-sm text-gray-500">Teachers</span>
                </div>

                <div class="flex items-center gap-1">
                    <div class="h-1.5 w-1.5 bg-green-600"/>
                    <span class="text-sm text-gray-500">{{ batch['subjects_count'] }} Subjects</span>
                </div>

            </div>

        </Card>

    </div>
    <Section v-if="activeBatch" :batch="activeBatch"/>

</template>

<script setup>
import {computed, ref} from "vue";
import {usePage} from "@inertiajs/vue3";
import Card from "@/Components/Card.vue";
import Section from "@/Pages/Admin/Levels/Section.vue";

let activeBatch = ref(null); // default value is null

const level = computed(() => usePage().props.level);
const batches = computed(() => usePage().props.batches)

function handleClick(batchId) {
    const batchDetails = batches.value.find(batch => batch.id === batchId);
    if (batchDetails) {
        activeBatch.value = batchDetails;
        console.log(activeBatch.value)
    } else {
        console.log(`Batch with ID ${batchId} not found.`);
    }
}
</script>

<style scoped>

</style>
