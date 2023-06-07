<template>
    <TableElement
        :columns="config"
        :data="levels"
        :filterable="false"
        :footer="false"
        :selectable="false"
        :header="false"
        title="Grades "
        subtitle="List of grades registered this year"
    >
        <template #level-column="{ data }">
            <Link :href="`admin/levels/${data.id}`" class="flex items-center gap-2">
                <div class="h-1.5 w-1.5 rounded-full" :class="categoryColors[data.category.name]"/>
                <span class="font-medium">{{ parseLevel(data.name) }}</span>
            </Link>
        </template>

        <template #batches-column="{ data }">
            <div class="flex items-center gap-1">
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
import TableElement from "@/Components/TableElement.vue";
import {Link} from "@inertiajs/vue3";
import {parseLevel} from "@/utils.js";

const props = defineProps({
    levels: {
        type: String,
        required: true,
    },
    config: {
        type: String,
        required: true,
    },
});

const categoryColors = {
    'Kindergarten': 'bg-blue-500',
    'ElementarySchool': 'bg-orange-500',
    'HighSchool': 'bg-green-500'
}
</script>

<style scoped>

</style>
