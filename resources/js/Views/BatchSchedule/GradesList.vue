<template>
    <div
        class="flex h-full w-56 flex-col items-center overflow-auto rounded-lg bg-brand-100 py-6"
    >
        <Heading class="pb-4 pl-2 !font-bold text-brand-text-50" size="lg"
            >Grades List
        </Heading>

        <ul class="flex flex-col overflow-auto">
            <li v-for="(level, l) in levels" :key="l" class="my-1">
                <div
                    :class="{
                        'rounded-md bg-brand-550 text-white ':
                            level.id === activeLevelId,
                    }"
                    class="group flex cursor-pointer items-center justify-between p-3 text-center hover:rounded-md hover:bg-brand-550 hover:text-white"
                    @click="
                        $emit('batch-change', level['batches'][0]['id']);
                        loadBatchSchedule
                            ? getBatchSchedule(
                                  `?batch_id=${level['batches'][0]['id']}`
                              )
                            : '';
                    "
                >
                    <Heading
                        :class="[
                            !(level.id === activeLevelId)
                                ? 'text-brand-text-50 group-hover:text-white'
                                : 'text-white',
                        ]"
                        size="sm"
                    >
                        {{ parseLevel(level.name) }}
                    </Heading>
                </div>
            </li>
        </ul>
        <Loading v-if="isLoading" is-full-screen />
    </div>
</template>

<script setup>
import Heading from "@/Components/Heading.vue";
import { router, usePage } from "@inertiajs/vue3";
import { parseLevel } from "@/utils.js";
import { computed, ref } from "vue";
import Loading from "@/Components/Loading.vue";

const emit = defineEmits(["batch-change"]);
const props = defineProps({
    levels: {
        type: Array,
        required: true,
    },
    loadBatchSchedule: {
        type: Boolean,
        default: true,
    },
});
const activeLevelId = computed(() => {
    return usePage().props.selectedBatch?.level?.id;
});

const isLoading = ref(false);
const getBatchSchedule = (url) => {
    isLoading.value = true;
    router.visit(url, {
        preserveState: true,
        onFinish: () => {
            isLoading.value = false;
        },
    });
};
</script>

<style scoped></style>
