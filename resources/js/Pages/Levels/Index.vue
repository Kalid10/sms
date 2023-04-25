<template>
    <TableElement
        :columns="config"
        :data="levels"
        :filterable="false"
        :footer="false"
        :selectable="false"
        title="Levels list"
        subtitle="List of levels registered"
    >
        <template #level_category-column="{ data }">
            {{ data.name }}
        </template>

        <template #batches-column="{ data }">
            {{ data.length }}
        </template>

    </TableElement>

</template>

<script setup>
import {computed, ref} from "vue";
import TableElement from "@/Components/TableElement.vue";
import {usePage} from "@inertiajs/vue3";

const levels = computed(() => {
    return usePage().props.levels;
});

const config = [
    {
        name: 'Levels',
        key: 'name',
        link: '/levels/{id}',
    },
    {
        name: 'Level Category',
        key: 'level_category',
        type: 'custom',
    },
    {
        name: 'Sections',
        key: 'batches',
        type: 'custom',
    },
]

const selectedLevel = ref();

function selectLevel(level) {
    selectedLevel.value = level;
    if (!level) {
        return (selectedLevel.value = null);
    }
}

</script>

<style scoped>

</style>
