<template>
    <div class="mt-5 flex flex-col gap-8">
        <div
            v-for="(category, c) in levelCategories"
            :key="c"
            class="flex flex-col gap-3"
        >
            <div class="flex items-center gap-1.5">
                <div
                    :class="levelCategoryLabels[c]"
                    class="grid h-5 w-5 place-items-center rounded-full border text-xs font-semibold"
                >
                    {{ category[0] }}
                </div>
                <h3 class="text-sm font-semibold text-black">
                    {{ category }} {{ $t("subjectGrades.levels") }}
                </h3>
            </div>

            <div class="grid grid-cols-1 gap-3 lg:grid-cols-4">
                <Card
                    v-for="(level, l) in levels.filter(
                        (l) => l['level']['level_category'] === category
                    )"
                    :key="l"
                    class="!w-full"
                    :title="`${parseLevel(level['level']['name'])}`"
                    @click="openBatchPage(level)"
                >
                    <div class="flex flex-col gap-1">
                        <h3 class="text-sm font-semibold text-gray-500">
                            {{ $t("subjectGrades.sections") }}
                        </h3>
                        <div class="flex gap-3">
                            <div
                                v-for="(section, s) in level.batches"
                                :key="s"
                                class="grid h-6 w-6 place-items-center rounded-full border border-purple-100 bg-brand-150 text-sm font-semibold text-white"
                            >
                                {{ section.section }}
                            </div>
                        </div>
                    </div>
                </Card>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import { levelCategoryLabels, parseLevel } from "@/utils.js";
import Card from "@/Components/Card.vue";

const levels = computed(() => usePage().props.levels);
const subject = computed(() => usePage().props.subject);
const levelCategories = computed(() =>
    levels.value.reduce((acc, level) => {
        if (!acc.includes(level["level"]["level_category"])) {
            acc.push(level["level"]["level_category"]);
        }
        return acc;
    }, [])
);

function openBatchPage(level) {
    router.get(
        `/batches/subjects/${level.batches[0].batch_id}/${subject.value.id}`
    );
}
</script>

<style scoped></style>
