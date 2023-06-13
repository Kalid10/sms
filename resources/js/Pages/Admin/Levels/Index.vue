<template>
    <div class="flex w-full items-center rounded-lg bg-white p-2 shadow-sm">
        <TableElement
            :columns="config"
            :data="levels"
            :filterable="false"
            :footer="false"
            :selectable="false"
            :header="false"
            class="!shadow-none"
            subtitle="List of grades registered this year"
        >
            <template #table-header>
                <div class="flex items-center justify-between p-2">
                    <div>
                        <h1 class="font-semibold">Grades</h1>
                        <p class="mt-0.5 text-xs font-light text-gray-500">
                            List of grades registered this year
                        </p>
                    </div>
                    <div class="flex w-5/12 flex-col items-end space-y-2">
                        <!--    TODO: Implement Search-->
                        <TextInput placeholder="Search Grades" class="w-full" />
                        <LinkCell
                            class="!text-xs"
                            href="/level-categories"
                            value=" Go To Level Categories"
                        />
                    </div>
                </div>
            </template>
            <template #level-column="{ data }">
                <Link
                    :href="`/admin/levels/${data.id}`"
                    class="flex items-center gap-2"
                >
                    <div
                        class="h-1.5 w-1.5 rounded-full"
                        :class="categoryColors[data.category.name]"
                    />
                    <span
                        class="cursor-pointer font-medium underline-offset-2 hover:scale-105 hover:font-semibold hover:underline"
                        >{{ parseLevel(data.name) }}</span
                    >
                </Link>
            </template>

            <template #batches-column="{ data }">
                <div class="flex items-center gap-1">
                    <span class="text-xs">
                        {{ data.length }}
                        <span class="md:inline"> active sections </span>
                    </span>
                </div>
            </template>

            <template #updated_at-column="{ data }">
                <div class="flex w-full justify-end">
                    <span class="text-xs text-gray-500">{{ data }}</span>
                </div>
            </template>
        </TableElement>
    </div>
</template>

<script setup>
import TableElement from "@/Components/TableElement.vue";
import { Link, usePage } from "@inertiajs/vue3";
import { parseLevel } from "@/utils.js";
import { computed } from "vue";
import moment from "moment/moment";
import LinkCell from "@/Components/LinkCell.vue";
import TextInput from "@/Components/TextInput.vue";

const levels = computed(() => {
    return usePage().props.levels.map((level) => {
        return {
            batches: level.batches,
            id: level.id,
            level: {
                id: level.id,
                name: level.name,
                category: level["level_category"],
            },
            updated_at: moment(level.updated_at).fromNow(),
            created_at: level.created_at,
        };
    });
});

const config = [
    {
        name: "Level Category",
        key: "level",
        type: "custom",
    },
    {
        name: "Sections",
        key: "batches",
        type: "custom",
    },
    {
        name: "Updated at",
        key: "updated_at",
        type: "custom",
    },
];

const categoryColors = {
    Kindergarten: "bg-blue-500",
    ElementarySchool: "bg-orange-500",
    HighSchool: "bg-green-500",
};
</script>

<style scoped></style>
