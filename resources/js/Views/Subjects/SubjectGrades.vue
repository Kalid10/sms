<template>

    <div class="flex flex-col gap-8">

        <div v-for="(category, c) in levelCategories" :key="c" class="flex flex-col gap-3">

            <h3 class="text-sm font-semibold text-gray-500">{{ category }} Levels</h3>

            <div class="grid grid-cols-4 gap-3">

                <Card v-for="(level, l) in levels.filter(l => l['level']['level_category'] === category)" :key="l" class="!w-full" :title="`${parseLevel(level['level']['name'])}`">

                    <div class="flex flex-col gap-1">

                        <h3 class="text-sm font-semibold text-gray-500">Sections</h3>
                        <div class="flex gap-3">

                            <div
                                v-for="(section, s) in level.batches"
                                :key="s"
                                class="grid h-6 w-6 place-items-center rounded-full bg-brand-50 text-sm font-semibold text-brand-100"
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
import {computed, ref} from "vue";
import {usePage, Link} from "@inertiajs/vue3";
import TableElement from "@/Components/TableElement.vue";
import {parseLevel} from "@/utils.js";
import RadioGroup from "@/Components/RadioGroup.vue";
import Card from "@/Components/Card.vue";

const levels = computed(() => usePage().props.levels)
const levelCategories = computed(() => levels.value.reduce((acc, level) => {
    if (! acc.includes(level['level']['level_category'])) {
        acc.push(level['level']['level_category'])
    }
    return acc
}, []))

</script>

<style scoped>

</style>
