<template>

    <div class="flex h-full w-56 flex-col overflow-auto rounded-lg bg-brand-100 py-6 pl-2">

        <Heading class="pb-4 pl-2 !font-bold text-brand-text-50" size="lg">Grades List</Heading>

        <ul class="flex flex-col overflow-auto">
            <li v-for="(level, l) in levels" :key="l">
                <Link :class="{ 'rounded-l-md bg-brand-550 text-white': level.id === activeLevelId }" class="flex items-center justify-between py-3 pl-4" :href="`?batch_id=${level['batches'][0]['id']}`">
                    <Heading :class="[ !(level.id === activeLevelId) ? 'text-brand-text-50' : 'text-white' ]" size="sm">
                        {{ parseLevel(level.name) }}
                    </Heading>
                </Link>
            </li>
        </ul>

    </div>

</template>

<script setup>
import Heading from "@/Components/Heading.vue";
import {Link, usePage} from "@inertiajs/vue3";
import {parseLevel} from "@/utils.js";
import {computed} from "vue";

const props = defineProps({
    levels: {
        type: Array,
        required: true
    }
})
const activeLevelId = computed(() => {
    return usePage().props.selectedBatch?.level?.id
})
</script>

<style scoped>

</style>
