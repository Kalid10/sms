<template>
    <TableElement
        :columns="config"
        :data="levels"
        :filterable="false"
        :footer="false"
        :selectable="false"
        :header="false"
        title="Levels list"
        subtitle="List of levels registered"
    >
        <template #level-column="{ data }">
            <Link :href="`levels/${data.id}`" class="flex items-center gap-2">
                <div class="h-2 w-2 rounded-full" :class="categoryColors[data.category.name]"/>
                <snap class="font-medium">{{ parseLevel(data.name) }}</snap>
                <span class="hidden text-xs text-gray-500 md:inline">(Class of 2026/2027)</span>

            </Link>
        </template>

        <template #batches-column="{ data }">
            <div class="flex items-center gap-1">
                <div class="h-1.5 w-1.5 rotate-45 bg-orange-300"/>
                <span class="text-xs">
                {{ data.length }}
                <span class="md:inline">
                    active sections
                </span>
            </span>
            </div>
        </template>

        <template #updated_at-column="{ data }">
            <div class="flex w-full justify-end">
                <span class="text-xs text-gray-500">{{ data }}</span>
            </div>
        </template>

    </TableElement>

</template>

<script setup>
import {computed} from "vue";
import TableElement from "@/Components/TableElement.vue";
import {Link, usePage} from "@inertiajs/vue3";
import moment from 'moment'
import {parseLevel} from "../../utils.js";

const levels = computed(() => {
    return usePage().props.levels.map((level) => {
        return {
            batches: level.batches,
            id: level.id,
            level: {
                id: level.id,
                name: level.name,
                category: level['level_category']
            },
            updated_at: moment(level.updated_at).fromNow(),
            created_at: level.created_at,
        }
    });
});

const config = [
    {
        name: 'Level Category',
        key: 'level',
        type: 'custom',
    },
    {
        name: 'Sections',
        key: 'batches',
        type: 'custom',
    },
    {
        name: 'Updated at',
        key: 'updated_at',
        type: 'custom',
    },
]

const categoryColors = {
    'Kindergarten': 'bg-blue-500',
    'ElementarySchool': 'bg-orange-500',
    'HighSchool': 'bg-green-500'
}
</script>

<style scoped>

</style>
