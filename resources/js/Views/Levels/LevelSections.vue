<template>

    <div class="grid grid-cols-4 gap-3">

        <Card v-for="(batch, b) in batches" :key="b" class="!w-full" :title="`Section ${batch.section}`">

            <div class="flex gap-3">

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

</template>

<script setup>
import {computed} from "vue";
import {usePage} from "@inertiajs/vue3";
import Card from "@/Components/Card.vue";

const batches = computed(() => usePage().props.batches)
</script>

<style scoped>

</style>
