<template>

    <TableElement :columns="config" :filterable="false" :header="false" :selectable="false" subtitle="List of batches enrolled in semester of  Spring 2023" title="Batches of Spring 2023" :data="levels">
        <template #sections-column>
            <div class="flex items-center justify-center gap-1">
                <span class="text-gray-500">Sections</span>
                <span class="text-gray-700">3</span>
            </div>
        </template>
        <template #name-column="{ data }">
            <div class="flex items-center gap-2">
                <LockClosedIcon class="h-4 w-4" />
                <span class="font-bold text-gray-700">{{ data }}</span>
            </div>
        </template>
        <template #last_updated-column="{ data }">
            <div class="flex items-center justify-end gap-2">
                <span class="text-gray-500">{{ data }}</span>
            </div>
        </template>
    </TableElement>

</template>

<script setup>
import moment from "moment"
import {computed, ref} from "vue";
import {LockClosedIcon} from "@heroicons/vue/24/outline";
import {levels as fake} from "@/fake";
import TableElement from "@/Components/TableElement.vue";

const levels = computed(() => {
    return fake.map((level) => {
        return {
            id: level.id,
            name: level.name,
            sections: level.sections.length,
            last_updated: moment(level.last_updated).fromNow(),
        }
    })
})

const config = [
    {
        key: 'name',
        type: 'custom',
    },
    {
        key: 'sections',
        type: 'custom',
    },
    {
        key: 'last_updated',
        type: 'custom',
    },
]
</script>

<style scoped>

</style>
