<template>
    <div class="mx-auto w-11/12 space-y-3 px-3">
        <div class="flex w-full flex-col flex-wrap items-center text-center">
            <Title :title="gradeLevel" />
        </div>
        <TabElement v-model:active="activeTab" :tabs="tabs">
            <template #home>
                <div class="flex flex-col items-center justify-center">
                    <Home />
                </div>
            </template>
            <template #schedules>
                <div class="flex flex-col items-center justify-center">
                    <Schedules />
                </div>
            </template>
            <template #students>
                <div class="flex flex-col items-center justify-center">
                    <Students />
                </div>
            </template>
        </TabElement>
    </div>
</template>
<script setup>
import Title from "@/Views/Teacher/Views/Title.vue";
import TabElement from "@/Components/TabElement.vue";
import { computed, ref } from "vue";
import Schedules from "@/Views/Admin/Batches/Schedules.vue";
import Home from "@/Views/Admin/Batches/Index.vue";
import Students from "@/Views/Admin/Batches/Students.vue";
import { usePage } from "@inertiajs/vue3";

const batch = computed(() => usePage().props.batch);

const gradeLevel = computed(() => {
    return batch.value.level.name + " " + batch.value.section;
});

const tabs = ["Home", "Schedules", "Students"];

const activeTab = ref("Home");
</script>
<style scoped></style>
