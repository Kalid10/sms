<template>
    <div class="my-5 flex min-h-full w-10/12 flex-col rounded-lg">
        <Title class="pb-8" :title="$t('common.grades')" />
        <TableElement
            :columns="config"
            :data="levels"
            :filterable="false"
            :footer="false"
            :selectable="false"
            :header="false"
            class="!shadow-none"
        >
            <template #table-header>
                <div class="flex items-center justify-between">
                    <div
                        class="flex w-full items-center justify-between space-x-5 pb-4"
                    >
                        <!--    TODO: Implement Search-->
                        <TextInput :placeholder="$t('levelIndex.searchGrades')" class="w-6/12" />
                        <SecondaryButton
                            :title="$t('levelIndex.goToLevelCategories')"
                            value=" Go To Level Categories"
                            class="!rounded-2xl bg-zinc-700 text-white"
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
import TextInput from "@/Components/TextInput.vue";
import Title from "@/Views/Teacher/Views/Title.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import {useI18n} from "vue-i18n";
const {t} = useI18n()
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
        name: t('levelIndex.levelCategory'),
        key: "level",
        type: "custom",
    },
    {
        name: t('common.sections'),
        key: "batches",
        type: "custom",
    },
    {
        name: t('levelIndex.updatedAt'),
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
