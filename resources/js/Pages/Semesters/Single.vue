<template>

    <TableElement
            :columns="config"
            :data="levels"
            :filterable="false"
            :footer="false"
            :header="false"
            :selectable="false"
            title="Batches list"
            subtitle="List of batches registered in Spring 2023, School Year 2022-2023"
        >
            <template #statistics-column="{ data }">
                <div class="flex items-center justify-start gap-2">
                    <div class="relative flex items-center gap-1">
                        <div class="h-1.5 w-1.5 bg-yellow-500" />
                        <span class="text-xs">
                        {{ data.sections.length }}
                        <span class="hidden md:inline">
                            sections
                        </span>
                    </span>
                    </div>
                    <div class="flex items-center gap-1">
                        <div class="h-1.5 w-1.5 rotate-45 bg-green-500" />
                        <span class="text-xs">
                        {{ data.subjects }}
                        <span class="hidden md:inline">
                            subjects
                        </span>
                    </span>
                    </div>
                    <div class="flex items-center gap-1">
                        <div class="h-1.5 w-1.5 rounded-full bg-blue-500" />
                        <span class="text-xs">
                        {{ data.students }}
                        <span class="hidden md:inline">
                            students
                        </span>
                    </span>
                    </div>
                </div>
            </template>
            <template #name-column="{ data }">
                <div class="flex items-center gap-2">
                    <LockClosedIcon class="h-4 w-4"/>
                    <div class="flex items-center gap-2">
                        <span class="font-medium">{{ data }}</span>
                        <span class="hidden text-xs text-gray-500 md:inline">(Class of 2026/2027)</span>
                    </div>
                </div>
            </template>
            <template #last_updated-column="{ data }">
                <div class="flex items-center justify-end gap-2">
                    <span class="text-gray-500">{{ data }}</span>
                </div>
            </template>
            <template #footer>
                <div class="h-4"></div>
            </template>
        </TableElement>

    <div class="flex flex-col gap-4">
        <Heading>
            Recent Activities
        </Heading>
    </div>

    <div class="flex flex-col gap-4">
        <Heading>
            Upcoming Schedule
        </Heading>
    </div>

</template>

<script setup>
import moment from "moment"
import {computed} from "vue";
import {LockClosedIcon} from "@heroicons/vue/24/outline";
import {levels as fake} from "@/fake";
import Heading from "@/Components/Heading.vue";
import TableElement from "@/Components/TableElement.vue";

const levels = computed(() => {
    return fake.map((level) => {
        return {
            id: level.id,
            name: level.name,
            statistics: level.statistics,
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
        key: 'statistics',
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
