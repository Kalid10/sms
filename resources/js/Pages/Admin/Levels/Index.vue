<template>
    <div class="relative flex w-full">
        <PrimaryButton class="absolute right-0 mt-4" @click="levelCategoryLink">
            Go To Level Categories
        </PrimaryButton>
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
    </div>

</template>

<script setup>
import TableElement from "@/Components/TableElement.vue";
import {Link, router, usePage} from "@inertiajs/vue3";
import {parseLevel} from "@/utils.js";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {computed} from "vue";
import moment from "moment/moment";


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
    'Kindergarten': 'bg-blue-500',
    'ElementarySchool': 'bg-orange-500',
    'HighSchool': 'bg-green-500'
}

function levelCategoryLink() {
    router.get("/level-categories");
}
</script>

<style scoped>

</style>
