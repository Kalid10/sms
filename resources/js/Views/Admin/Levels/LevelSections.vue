<template>
    <div class="grid grid-cols-4 gap-3 py-4">
        <!--        <Card-->
        <!--            v-for="(batch, index) in batches"-->
        <!--            :key="index"-->
        <!--            class="!w-full cursor-pointer"-->
        <!--            :class="-->
        <!--                selectedBatch === batch.id-->
        <!--                    ? '!border-2 border-zinc-700 text-black'-->
        <!--                    : 'bg-red-400'-->
        <!--            "-->
        <!--            :title="`Section ${batch.section}`"-->
        <!--            @click="handleClick(batch.id)"-->
        <!--        >-->
        <!--            <div class="flex flex-wrap gap-3">-->
        <!--                <div class="flex items-center gap-1">-->
        <!--                    <div class="h-1.5 w-1.5 rounded-full bg-red-600" />-->
        <!--                    <span-->
        <!--                        :class="-->
        <!--                            selectedBatch === batch.id-->
        <!--                                ? 'text-gray-700'-->
        <!--                                : 'text-gray-500'-->
        <!--                        "-->
        <!--                        class="text-sm"-->
        <!--                        >{{ batch["students_count"] }} Students</span-->
        <!--                    >-->
        <!--                </div>-->

        <!--                <div class="flex items-center gap-1">-->
        <!--                    <div class="h-1.5 w-1.5 rotate-45 bg-yellow-400" />-->
        <!--                    <span-->
        <!--                        :class="-->
        <!--                            selectedBatch === batch.id-->
        <!--                                ? 'text-gray-700'-->
        <!--                                : 'text-gray-500'-->
        <!--                        "-->
        <!--                        class="text-sm"-->
        <!--                        >Teachers</span-->
        <!--                    >-->
        <!--                </div>-->

        <!--                <div class="flex items-center gap-1">-->
        <!--                    <div class="h-1.5 w-1.5 bg-green-600" />-->
        <!--                    <span-->
        <!--                        :class="-->
        <!--                            selectedBatch === batch.id-->
        <!--                                ? 'text-gray-700'-->
        <!--                                : 'text-gray-500'-->
        <!--                        "-->
        <!--                        class="text-sm"-->
        <!--                        >{{ batch["subjects_count"] }} Subjects</span-->
        <!--                    >-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </Card>-->
    </div>
    <Section v-if="activeBatch" :batch="activeBatch" />
</template>

<script setup>
import { computed, onMounted, ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import Section from "@/Pages/Admin/Levels/Section.vue";

let activeBatch = ref(null);

const level = computed(() => usePage().props.level);
const batches = computed(() => usePage().props.batches);
const selectedBatch = ref();

onMounted(() => {
    selectedBatch.value = batches.value[0].id;
    activeBatch.value = batches.value[0];
});

function handleClick(batchId) {
    selectedBatch.value = batchId;
    activeBatch.value = batches.value.find((batch) => batch.id === batchId);
}
</script>

<style scoped></style>
