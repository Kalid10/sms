<template>

    <Heading>Register Batches</Heading>

    <div class="md:grid-cols3 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

        <label v-for="(level, l) in updatedLevels" :key="l">

            <Card class="!min-w-full">

                <div class="flex flex-col gap-6">

                    <div class="flex items-center justify-between">

                        <h3 class="font-semibold">
                            <span v-if="level.name.length < 3">Grade</span>
                            {{ level.name }}
                        </h3>

                        <Checkbox
                            v-model="level.selected"
                            class="h-5 w-5 !rounded-full checked:ring-0 focus:ring-0" type="checkbox"
                        />

                    </div>

                    <div class="flex gap-3">
                        <span>Sections</span>
                    </div>
                </div>

            </Card>
        </label>

        <div class="flex flex-col items-center justify-center gap-2 rounded-md border-2 border-dashed bg-white/75">

            <PlusCircleIcon class="h-5 w-5 stroke-gray-500 stroke-2"/>
            <span class="text-sm font-semibold text-gray-500">Create a new Level</span>

        </div>

    </div>

</template>

<script setup>
import {computed, ref} from "vue";
import {usePage} from "@inertiajs/vue3";
import Heading from "@/Components/Heading.vue";
import Checkbox from "@/Components/Checkbox.vue";
import Card from "@/Components/Card.vue";
import {PlusCircleIcon} from "@heroicons/vue/24/outline"

const levels = computed(() => usePage().props.levels)
const updatedLevels = ref(levels.value.map(level => {
    return {...level, selected: true}
}))

</script>

<style scoped>
</style>
